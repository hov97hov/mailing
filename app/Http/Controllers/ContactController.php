<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Interfaces\Contact\ContactInterface;
use App\Models\Contact;
use App\Models\Email;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    protected $contact;

    public function __construct(ContactInterface $contact)
    {
        $this->contact = $contact;
    }

    public function index(): Response
    {
        return Inertia::render('Contacts');
    }

    /**
     * @return Response
     */
    public function createContactPage(): Response
    {
        return Inertia::render('AddContact');
    }

    /**
     * @param CreateContactRequest $request
     */
    public function createEmail(CreateContactRequest $request)
    {
        return $this->contact->createContact($request);
    }

    /**
     * @return JsonResponse
     */
    public function getEmails(): JsonResponse
    {
        return response()->json([
            'emails' => Contact::query()->with('group')->get()
        ]);
    }

    /**
     * @param UpdateContactRequest $request
     */
    public function updateEmail(UpdateContactRequest $request)
    {
        $this->contact->updateContact($request->all());
    }

    /**
     * @param Request $request
     */
    public function deleteEmail(Request $request)
    {
        $this->contact->deleteContact($request->id);
    }

    /**
     * @param Request $request
     */
    public function deleteContacts(Request $request)
    {
        $this->contact->deleteContacts($request->ids);
    }

    public function deleteSelectedContact(Request $request)
    {
        $this->contact->deleteSelectedContact($request->ids);
    }

    public function getUserEmails(): JsonResponse
    {
        return \response()->json([
           'userEmails' => Email::with(['user' => function($query) {
               $query->where('user_id', Auth::id());
           }])->get()
        ]);
    }

    public function getAuthUser()
    {
        return \response()->json([
            'authUser' => User::where('id', Auth::id())->first()
        ]);
    }

    public function searchContact(Request $request): JsonResponse
    {
        $emails = Contact::whereHas('group', function($query) use ($request){
            $query->where('group_id',$request->categoryId);
        })->where('email', 'like', '%' . $request['query'] . '%')->get();

        return response()->json([
            'categories' => $emails
        ]);
    }

    public function deleteSelectedEmails(Request $request)
    {
        return Contact::whereIn('id', $request->ids)->delete();
    }
}
