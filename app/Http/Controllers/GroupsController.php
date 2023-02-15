<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddContactGroupRequest;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\SendGroupMessageRequest;
use App\Http\Resources\ContactResource;
use App\Interfaces\Group\GroupInterface;
use App\Jobs\SendEmailJob;
use App\Models\Attachment;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use function GuzzleHttp\Promise\all;

class GroupsController extends Controller
{
    protected $group;

    public function __construct(GroupInterface $group)
    {
        $this->group = $group;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Groups');
    }

    /**
     * @return Response
     */
    public function groupPage($id): Response
    {
        $category = Group::where('id', $id)->with('contact')->first();
        return Inertia::render('Group', [
            'id' => $category->id
        ]);
    }

    /**
     * @param CreateGroupRequest $request
     */
    public function createCategory(CreateGroupRequest $request)
    {
        $this->group->createGroup($request);
    }

    public function updateCategory(CreateGroupRequest $request)
    {
        return Group::where('id', $request->id)->update([
            'name' => $request->name,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getCategory(): JsonResponse
    {
        return \response()->json([
           'categories' => Group::orderBy('sorting', 'ASC')->with('contact')->get()
        ]);
    }

    public function getFirstCategory(Request $request): JsonResponse
    {
        return \response()->json([
            'category' => Group::where('id', $request->id)->orderBy('sorting', 'ASC')->with('contact')->first()
        ]);
    }


    /**
     * @param Request $request
     */
    public function deleteCategory(Request $request)
    {
        $category = Group::where('id', $request->id)->first();
        unlink(public_path($category->image));

        return Group::where('id', $request->id)->delete();
    }

    public function deleteSelectedGroup(Request $request)
    {
        return Group::whereIn('id', $request->ids)->delete();
    }

    public function addEmailCategory(AddContactGroupRequest $request)
    {
        $this->group->createContactGroup($request);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getGroupUsers(Request $request): JsonResponse
    {
        return response()->json([
            'groupContacts' => Contact::where()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getGroupContacts(Request $request): JsonResponse
    {
       $contacts = Group::where('id', $request->id)->with('contact')->first();

        return \response()->json([
            'contacts' => $contacts
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteContactGroup(Request $request): JsonResponse
    {
        $this->group->deleteContactGroup($request);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getGroupContactsMessage(Request $request): JsonResponse
    {
        return \response()->json([
            'contacts' => Group::where('id', $request->id)->with('contact')->first()
        ]);
    }

    /**
     * @param SendGroupMessageRequest $request
     */
    public function sendGroupMessage(SendGroupMessageRequest $request)
    {
        $contactsIds = explode(',', $request->contacts);
        $contacts = Contact::whereIn('id', $contactsIds)->get();

       foreach ($contacts as $contact) {
           $createMessages = Message::create([
               'subject' => $request->subject,
               'contact_id' => $contact->id,
               'user_id' => 1,
               'message' => $request->text,
           ]);

           $messageIds[] = $createMessages->id;
       }

       if($request->hasFile('files')) {
            foreach ($request->files as $file) {
                foreach ($file as $item) {
                    $name = $item->getClientOriginalName();
                    if ($request->groupId === 'contact') {
                        $item->move(public_path() . '/storage/mails/contacts/', $name);
                        $filePath = '/storage/mails/contacts/'. $name;
                    }else {
                        $item->move(public_path() . '/storage/mails/'.$request->groupId, $name);
                        $filePath = '/storage/mails/'.$request->groupId.'/'. $name;
                    }
                    foreach ($messageIds as $id) {
                        Attachment::create([
                            'message_id' => $id,
                            'file_path' => $filePath,
                            'file_name' => $name,
                            'from' => $request->from,
                        ]);
                    }

                    $filesData[] = [$name,$filePath];
                }
            }
        }

        foreach ($contacts as $cnt) {
            $details = [
                'subject' => $request->subject,
                'email' => $cnt->email,
                'text' => $request->text,
                'files' => $filesData ?? null,
            ];

            dispatch(new SendEmailJob($details));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteSelectedContactGroup(Request $request): JsonResponse
    {
        $this->group->deleteSelectedContactGroup($request);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * @param CreateContactRequest $request
     * @return JsonResponse
     */
    public function addNewContactGroup(CreateContactRequest $request): JsonResponse
    {
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => Auth::id()
        ]);

        ContactGroup::create([
            'contact_id' => $contact->id,
            'group_id' => $request->group_id,
        ]);

        return \response()->json([
            'status' => 200
        ]);
    }

    public function addContactsGroups(Request $request)
    {
        foreach ($request->group_ids as $groupId) {
            foreach ($request->contacts as $contact) {
                ContactGroup::firstOrCreate([
                    'group_id' => $groupId,
                    'contact_id' => $contact,
                ]);
            }
        }

        return \response()->json([
            'status' => 200
        ]);
    }

    public function updateCategoryImage(Request $request)
    {
        if($request->hasFile('image')) {

            $group = Group::where('id', $request->id)->first();
            unlink(public_path($group->image));

            $name = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/groups/', $name);
            $filePath = '/storage/groups/'. $name;

            Group::where('id', $request->id)->update([
                'image' => $filePath
            ]);
        }

        return \response()->json([
            'success' =>  200
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $category = Group::where('name', 'like', '%' . $request['query'] . '%')->get();

        return response()->json([
            'categories' => $category
        ]);
    }

    public function searchMessages(Request $request): JsonResponse
    {
        $messages = Message::where('subject', 'like', '%' . $request['query'] . '%')->with('group')->get();

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function searchEmails(Request $request): JsonResponse
    {
        $emails = Contact::where('name', 'like', '%' . $request['query'] . '%')->with('group')->get();

        return response()->json([
            'emails' => $emails
        ]);
    }

    public function deleteSelectedCategories(Request $request)
    {
        return Group::whereIn('id', $request->ids)->delete();
    }

    public function deleteSelectedEmailGroup(Request $request)
    {
        foreach ($request->ids as $key => $item) {
            ContactGroup::where(['id' => $item, 'group_id' => $request->categoryId])->delete();
        }
    }
}
