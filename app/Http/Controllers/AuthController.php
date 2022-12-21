<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 0,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['status' => 200]);
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                if (Auth::user()) {
                    return response()->json(['status' => 200]);
                }
            }
        } catch (\Exception $exception) {
            return redirect("login");
        }
    }

    public function logout(){
        Session::flush();
        Auth::logout();

        return response()->json(['status' => 200]);
    }

}
