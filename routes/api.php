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
Route::get('get-emails', [ContactController::class, 'getEmails'])->name('getEmails');
Route::post('create-email', [ContactController::class, 'createEmail'])->name('createEmail');
Route::post('update-email', [ContactController::class, 'updateEmail'])->name('updateEmail');
Route::post('delete-email', [ContactController::class, 'deleteEmail'])->name('deleteEmail');
Route::post('delete-selected-emails', [ContactController::class, 'deleteSelectedEmails'])->name('deleteSelectedEmails');
Route::post('delete-contacts', [ContactController::class, 'deleteContacts'])->name('deleteContacts');
Route::post('delete-selected-contacts', [ContactController::class, 'deleteSelectedContact'])->name('deleteSelectedContact');
Route::post('get-user-emails', [ContactController::class, 'getUserEmails'])->name('getUserEmails');
Route::post('get-auth-user', [ContactController::class, 'getAuthUser'])->name('getAuthUser');
Route::post('search-contacts', [ContactController::class, 'searchContact'])->name('search');
//GROUP
Route::post('create-category', [GroupsController::class, 'createCategory'])->name('createCategory');
Route::post('update-category', [GroupsController::class, 'updateCategory'])->name('updateCategory');
Route::post('get-category', [GroupsController::class, 'getCategory'])->name('getCategory');
Route::post('get-first-category', [GroupsController::class, 'getFirstCategory'])->name('getFirstCategory');
Route::post('get-selected-groups', [GroupsController::class, 'getSelectedGroups'])->name('getSelectedGroups');
Route::post('delete-category', [GroupsController::class, 'deleteCategory'])->name('deleteCategory');
Route::post('delete-categories', [GroupsController::class, 'deleteSelectedCategories'])->name('deleteSelectedCategories');
Route::post('delete-selected-group', [GroupsController::class, 'deleteSelectedGroup'])->name('deleteSelectedGroup');
Route::post('add-email-category', [GroupsController::class, 'addEmailCategory'])->name('addEmailCategory');
Route::post('get-group-contacts', [GroupsController::class, 'getGroupContacts'])->name('getGroupContacts');
Route::post('delete-contact-group', [GroupsController::class, 'deleteContactGroup'])->name('deleteContactGroup');
Route::post('get-group-contacts', [GroupsController::class, 'getGroupContactsMessage'])->name('getGroupContactsMessage');
Route::post('send-group-message', [GroupsController::class, 'sendGroupMessage'])->name('sendGroupMessage');
Route::post('delete-selected-contact-group', [GroupsController::class, 'deleteSelectedContactGroup'])->name('deleteSelectedContactGroup');
Route::post('create-new-contact-group', [GroupsController::class, 'addNewContactGroup'])->name('addNewContactGroup');
Route::post('add-contacts-groups', [GroupsController::class, 'addContactsGroups'])->name('addContactsGroups');
Route::post('update-category-image', [GroupsController::class, 'updateCategoryImage'])->name('updateCategoryImage');
Route::post('search-categories', [GroupsController::class, 'search'])->name('search');
Route::post('search-messages', [GroupsController::class, 'searchMessages'])->name('search');
Route::post('search-emails', [GroupsController::class, 'searchEmails'])->name('searchEmails');
Route::post('delete-selected-email-group', [GroupsController::class, 'deleteSelectedEmailGroup'])->name('deleteSelectedEmailGroup');
//MESSAGES
Route::post('send-email-img-link', [MessageController::class, 'sendEmailImgLink'])->name('sendMessage');
Route::post('send-email-img-btn', [MessageController::class, 'sendEmailImgBtn'])->name('sendEmailImgBtn');
Route::post('send-email-img-text', [MessageController::class, 'sendEmailImgText'])->name('sendEmailImgText');
Route::post('send-email-img-text-btn', [MessageController::class, 'sendEmailImgTextButton'])->name('sendEmailImgText');
Route::post('send-email-text-btn', [MessageController::class, 'sendEmailTextButton'])->name('sendEmailTextButton');
Route::post('send-email-img-text-btn-img', [MessageController::class, 'sendEmailImgTextImgButton'])->name('sendEmailTextButton');
Route::get('get-messages', [MessageController::class, 'getMessages'])->name('getMessages');
Route::post('delete-selected-messages', [MessageController::class, 'deleteSelectedMessage'])->name('deleteSelectedMessage');
//SETTINGS
Route::post('/create-settings', [SettingController::class, 'createSettings'])->name('createSettings');
Route::post('/get-settings', [SettingController::class, 'getSettings'])->name('getSettings');
Route::post('/create-email-setting', [SettingController::class, 'createMail'])->name('createMail');
Route::post('/get-emails-setting', [SettingController::class, 'getEmails'])->name('getEmails');
Route::post('/update-email-setting', [SettingController::class, 'updateEmail'])->name('updateEmail');
Route::post('/delete-email-setting', [SettingController::class, 'deleteEmail'])->name('deleteEmail');
Route::post('/create-email-user', [SettingController::class, 'createEmailUser'])->name('createEmailUser');
//USERS
Route::post('/get-users', [UserController::class, 'getUsers'])->name('getUsers');
Route::post('/update-user', [UserController::class, 'updateUser'])->name('updateUser');
Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('/delete-selected-users', [UserController::class, 'deleteSelectedUser'])->name('deleteSelectedUser');
