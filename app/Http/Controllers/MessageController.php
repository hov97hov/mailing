<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageContactRequest;
use App\Jobs\SendEmailJob;
use App\Models\Attachment;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAllMessages(): JsonResponse
    {
        return response()->json([
            'allMessages' => Message::where(['user_id' => Auth::id(), 'archived' => '0'])->whereHas('contact')->with(['contact' => function($query) {
                $query->with('group');
            }])->get()
        ]);
    }

    public function addArchiveMessage(Request $request)
    {
        return Message::where('id', $request->id)->update([
            'archived' => $request->archived
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getArchiveMessages(): JsonResponse
    {
        return response()->json([
            'archiveMessages' => Message::where(['user_id' => '1', 'archived' => '1'])->with(['contact' => function($query) {
                $query->with('group');
            }])->get()
        ]);
    }

    public function deleteMessage(Request $request)
    {
        return Message::where('id', $request->id)->delete();
    }

    public function getOneMessage(Request $request)
    {
        return response()->json([
            'oneMessage' => Message::where('id', $request->id)->with('file')->first()
        ]);
    }

    public function deleteSelectedMessage(Request $request)
    {
        return Message::whereIn('id', $request->message_ids)->delete();
    }

    public function addSelectedArchived(Request $request)
    {
        return Message::whereIn('id', $request->message_ids)->update([
            'archived' => 1
        ]);
    }

    public function sendMessage(SendMessageContactRequest $request)
    {
        $contacts = [];

        if (isset($request->contacts)) {
            foreach (explode(',', $request->contacts) as $contactItem) {
                $getContacts = Contact::where('id', $contactItem)->first();
                $contacts[] = $getContacts;
            }
        }

        if(isset($request->groupId)) {
            $group = Group::where('id', $request->groupId)->whereHas('contact')->with('contact')->first();
            foreach ($group->contact as $groupContact) {
                $contacts[] = $groupContact;
            }
        }

        if ($request->groups) {
            $groupContacts = explode(',', $request->groups);
            $contact = Contact::whereIn('id', $groupContacts)->get();
            foreach ($contact as $item) {
                $contacts[] = $item;
            }
        }

        if (isset($request->to)) {
            $email = Contact::where('email', $request->to)->first();
            if ($email) {
                $contacts[] = $email;
            }
            if (!$email) {
                $email = Contact::create([
                    'name' => $request->to,
                    'email' => $request->to,
                    'user_id' => Auth::id()
                ]);
                $contacts[] = $email;
            }
        }


        $createMessagesId = [];
        foreach ($contacts as $contactEmail) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'email' => $contactEmail->email,
                'user_id' => Auth::id(),
                'contact_id' => $contactEmail->id,
                'message' => $request->text,
            ]);
            $createMessagesId[] = $createMessages->id;
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
                    foreach ($createMessagesId as $id) {
                        Attachment::create([
                            'message_id' => $id,
                            'file_path' => $filePath,
                            'file_name' => $name,
                        ]);
                    }

                    $filesData[] = [$name,$filePath];
                }
            }
        }

        foreach ($contacts as $contactEmail) {
            $details = [
                'subject' => $request->subject,
                'from' => $request->from,
                'email' => $contactEmail,
                'text' => $request->text,
                'design' => $request->design,
                'files' => $filesData ?? '',
                'link' => $request->link ?? null,
            ];

            dispatch(new SendEmailJob($details));
        }
    }

    public function getNoRegisterContactMessages()
    {
        return response()->json([
            'noRegisterContactData' => Message::where(['user_id' => 1, 'contact_id' => null])->get()
        ]);
    }
}
