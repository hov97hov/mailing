<?php

namespace App\Http\Controllers;

use App\Http\Requests\sendEmailImgBtnRequest;
use App\Http\Requests\sendEmailImgLinkRequest;
use App\Http\Requests\sendEmailImgTextButtonImgRequest;
use App\Http\Requests\sendEmailImgTextButtonRequest;
use App\Http\Requests\sendEmailImgTextRequest;
use App\Http\Requests\sendEmailTextButtonRequest;
use App\Jobs\SendEmailJob;
use App\Models\Attachment;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function sendEmailImgLink(sendEmailImgLinkRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'from' => $request->from,
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                    'image_link' => $request->imgLink,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'image' => $image ?? '',
                'imgLink' => $request->imgLink ?? '',
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template1'
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function sendEmailImgBtn(sendEmailImgBtnRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'image' => $image ?? '',
                'btnName' => $request->btnName ?? '',
                'btnLink' => $request->btnLink ?? '',
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template2',
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function sendEmailImgText(sendEmailImgTextRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'image' => $image,
                'text' => $request->text,
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template3',
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function sendEmailImgTextButton(sendEmailImgTextButtonRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'image' => $image,
                'text' => $request->text,
                'btnName' => $request->btnName,
                'btnLink' => $request->btnLink,
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template4',
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function sendEmailTextButton(sendEmailTextButtonRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'text' => $request->text,
                'btnName' => $request->btnName,
                'btnLink' => $request->btnLink,
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template5',
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function sendEmailImgTextImgButton(sendEmailImgTextButtonImgRequest $request)
    {
        $categoryIds = explode(',', $request->categoryIds);
        $categories = Group::whereIn('id', $categoryIds)->with('contact')->get();
        $emails = [];

        if (isset($request->to)) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'message' => $request->text,
                'email' => $request->to,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;
        }

        foreach ($categories as $category) {
            $createMessages = Message::create([
                'subject' => $request->subject,
                'group_id' => $category->id,
                'message' => $request->text,
                'from' => $request->from,
                'mailing_image' => $request->mailingImg === '/images/mailing.png' ? 1 : 0,
                'btn_name' => $request->btnName,
                'btn_link' => $request->btnLink,
            ]);
            $messageIds[] = $createMessages->id;

            foreach ($category->contact as $contact) {
                $emails[] = $contact->email;
            }
        }

        if($request->hasFile('file')) {
            $name = $request->file->getClientOriginalName();
            $request->file->move(public_path() . '/storage/mails/category/', $name);
            $filePath = '/storage/mails/category/'. $name;

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
        if($request->hasFile('image')) {
            $imgName = $request->image->getClientOriginalName();
            $request->image->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_path' => $imgFilePath,
                    'image_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image[] = [$imgName,$imgFilePath];
        }
        if($request->hasFile('image2')) {
            $imgName = $request->image2->getClientOriginalName();
            $request->image2->move(public_path() . '/storage/mails/category/', $imgName);
            $imgFilePath = '/storage/mails/category/'. $imgName;

            foreach ($messageIds as $id) {
                Attachment::create([
                    'message_id' => $id,
                    'image_bottom_path' => $imgFilePath,
                    'image_bottom_name' => $imgName,
                    'from' => $request->from,
                ]);
            }

            $image2[] = [$imgName,$imgFilePath];
        }

        if ($request->to) {
            $emails[] = $request->to;
        }

        foreach ($emails as $email) {
            $details = [
                'from' => $request->from,
                'email' => $email,
                'subject' => $request->subject,
                'file' => $filesData ?? '',
                'text' => $request->text,
                'btnName' => $request->btnName,
                'btnLink' => $request->btnLink,
                'image' => $image,
                'image2' => $image2,
                'mailingImg' => $request->mailingImg ?? '',
                'template' => 'template6',
            ];
            dispatch(new SendEmailJob($details));
        }

    }

    public function getMessages(): JsonResponse
    {
        return response()->json([
            'messages' =>  Message::with(['group', 'file'])->get()
        ]);
    }

    public function deleteSelectedMessage(Request $request)
    {
        return Message::whereIn('id', $request->ids)->delete();
    }
}
