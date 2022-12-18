<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home');
    }

    public function getUsers(): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'users' => User::all()
        ]);
    }
}
