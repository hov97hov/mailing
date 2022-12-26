<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmailRequest;
use App\Http\Requests\CreateSettingsRequest;
use App\Http\Requests\UpdateEmailrequest;
use App\Models\Email;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings');
    }

    public function createSettings(CreateSettingsRequest $request)
    {
        return Setting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'host' => $request->host,
                'from' => $request->from,
                'header' => $request->header,
                'port' => $request->port,
                'encryption' => $request->encryption,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );
    }

    public function getSettings()
    {
        return response()->json([
            'settings' => Setting::where('user_id', Auth::id())->first()
        ]);
    }

    public function emails() {
        return Inertia::render('AddEmail');
    }

    public function createMail(CreateEmailRequest $request)
    {
        $createEmail = Email::create([
            'email' => $request->email,
            'host' => $request->host,
            'from' => $request->from,
            'header' => $request->header,
            'port' => $request->port,
            'encryption' => $request->encryption,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function getEmails()
    {
        return response()->json([
            'emails' => Email::query()->get()
        ]);
    }

    public function updateEmail(UpdateEmailrequest $request)
    {
        return Email::where('id', $request->id)->update(
            [
                'email' => $request->email,
                'host' => $request->host,
                'from' => $request->from,
                'header' => $request->header,
                'port' => $request->port,
                'encryption' => $request->encryption,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );
    }

    public function deleteEmail(Request $request)
    {
        return Email::where('id', $request->id)->delete();
    }

    public function createEmailUser(Request $request)
    {
        foreach ($request->email as $email) {
            UserEmail::firstOrCreate([
                'email_id' => $email,
                'user_id' => $request->id,
            ]);
        }
    }
}
