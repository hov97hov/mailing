<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Emails');
    }

    public function addEmailSetting(): Response
    {
        return Inertia::render('AddEmailsPage');
    }
}
