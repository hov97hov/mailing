<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function users()
    {
        return Inertia::render('Users');
    }

    public function getUsers()
    {
        return response()->json([
            'users' => User::query()->with('emails')->get()
        ]);
    }

    public function updateUser(Request $request)
    {
        return User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
    }

    public function deleteUser(Request $request)
    {
        return User::where('id', $request->id)->delete();
    }

    public function deleteSelectedUser(Request $request)
    {
        return User::whereIn('id', $request->ids)->delete();
    }
}
