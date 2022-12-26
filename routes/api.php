<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//LOGIN
Route::post('register', [AuthController::class, 'register']);
Route::post('check-login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
//CONTACT
Route::get('get-contacts', [ContactController::class, 'getContacts'])->name('getContacts');
Route::post('create-contact', [ContactController::class, 'createContact'])->name('createContact');
Route::post('update-contact', [ContactController::class, 'updateContact'])->name('updateContact');
Route::post('delete-contact', [ContactController::class, 'deleteContact'])->name('deleteContact');
Route::post('delete-contacts', [ContactController::class, 'deleteContacts'])->name('deleteContacts');
Route::post('delete-selected-contacts', [ContactController::class, 'deleteSelectedContact'])->name('deleteSelectedContact');
Route::post('get-user-emails', [ContactController::class, 'getUserEmails'])->name('getUserEmails');
Route::post('get-auth-user', [ContactController::class, 'getAuthUser'])->name('getAuthUser');
//GROUP
Route::post('create-group', [GroupsController::class, 'createGroup'])->name('createGroup');
Route::post('get-groups', [GroupsController::class, 'getGroups'])->name('getGroups');
Route::post('delete-group', [GroupsController::class, 'deleteGroup'])->name('deleteGroup');
Route::post('add-contact-group', [GroupsController::class, 'addContactGroup'])->name('addContactGroup');
Route::post('get-group-contacts', [GroupsController::class, 'getGroupContacts'])->name('getGroupContacts');
Route::post('delete-contact-group', [GroupsController::class, 'deleteContactGroup'])->name('deleteContactGroup');
Route::post('get-group-contacts', [GroupsController::class, 'getGroupContactsMessage'])->name('getGroupContactsMessage');
Route::post('send-group-message', [GroupsController::class, 'sendGroupMessage'])->name('sendGroupMessage');
Route::post('delete-selected-contact-group', [GroupsController::class, 'deleteSelectedContactGroup'])->name('deleteSelectedContactGroup');
//MESSAGES
Route::get('get-all-messages', [MessageController::class, 'getAllMessages'])->name('getAllMessages');
Route::post('add-archive-message', [MessageController::class, 'addArchiveMessage'])->name('addArchiveMessage');
Route::get('get-archive-message', [MessageController::class, 'getArchiveMessages'])->name('getArchiveMessages');
Route::post('delete-message', [MessageController::class, 'deleteMessage'])->name('deleteMessage');
Route::post('get-message', [MessageController::class, 'getOneMessage'])->name('getOneMessage');
Route::post('delete-selected-messages', [MessageController::class, 'deleteSelectedMessage'])->name('deleteSelectedMessage');
Route::post('add-archive-selected-message', [MessageController::class, 'addSelectedArchived'])->name('addSelectedArchived');
Route::post('send-message', [MessageController::class, 'sendMessage'])->name('sendMessage');
Route::get('get-no-register-message-contact', [MessageController::class, 'getNoRegisterContactMessages'])->name('getNoRegisterContactMessages');
//SETTINGS
Route::post('/create-settings', [SettingController::class, 'createSettings'])->name('createSettings');
Route::post('/get-settings', [SettingController::class, 'getSettings'])->name('getSettings');
Route::post('/create-email', [SettingController::class, 'createMail'])->name('createMail');
Route::post('/get-emails', [SettingController::class, 'getEmails'])->name('getEmails');
Route::post('/update-email', [SettingController::class, 'updateEmail'])->name('updateEmail');
Route::post('/delete-email', [SettingController::class, 'deleteEmail'])->name('deleteEmail');
Route::post('/create-email-user', [SettingController::class, 'createEmailUser'])->name('createEmailUser');
//USERS
Route::post('/get-users', [UserController::class, 'getUsers'])->name('getUsers');
Route::post('/update-user', [UserController::class, 'updateUser'])->name('updateUser');
Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('/delete-selected-users', [UserController::class, 'deleteSelectedUser'])->name('deleteSelectedUser');
