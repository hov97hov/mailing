<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Attachment;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAllMessages(): JsonResponse
    {
        return response()->json([
            'allMessages' => Message::where(['user_id' => '1', 'archived' => '0'])->whereHas('contact')->with(['contact' => function($query) {
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
            'oneMessage' => Message::where('id', $request->id)->first()
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

    public function sendMessage(Request $request)
    {
        $emails = explode(',', $request->to);
        foreach ($emails as $email) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'email' => $email,
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
                        ]);
                    }

                    $filesData[] = [$name,$filePath];
                }
            }
        }

        foreach ($emails as $cnt) {
            $details = [
                'subject' => $request->subject,
                'email' => $cnt,
                'text' => $request->text,
                'files' => $filesData ?? '',
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
