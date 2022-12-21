<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmailRequest;
use App\Http\Requests\CreateSettingsRequest;
use App\Models\Email;
use App\Models\Setting;
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
            'email' => $request->email
        ]);

        UserEmail::create([
            'email_id' => $createEmail->id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'status' => 200
        ]);
    }
}
