<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddContactGroupRequest;
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
        return Inertia::render('Group', [
            'groupId' => $id
        ]);
    }

    /**
     * @param CreateGroupRequest $request
     */
    public function createGroup(CreateGroupRequest $request)
    {
        $this->group->createGroup($request);
    }

    /**
     * @return JsonResponse
     */
    public function getGroups(): JsonResponse
    {
        return \response()->json([
           'groups' => Group::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * @param Request $request
     */
    public function deleteGroup(Request $request)
    {
        return Group::where('id', $request->id)->delete();
    }

    public function addContactGroup(AddContactGroupRequest $request)
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

    public function deleteSelectedContactGroup(Request $request): JsonResponse
    {
        $this->group->deleteSelectedContactGroup($request);

        return response()->json([
            'status' => 200
        ]);
    }
}
