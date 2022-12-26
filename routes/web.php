<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserPermision;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/login', [LoginController::class, 'loginPage'])->name('loginPage');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    //Groups
    Route::get('/groups', [GroupsController::class, 'index'])->name('index.groups');
    Route::get('/group/{id}', [GroupsController::class, 'groupPage'])->name('group');
    //contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('index.contacts');
    Route::get('/add-contact', [ContactController::class, 'createContactPage'])->name('createContactPage');

    //settings
    Route::get('/emails', [SettingController::class, 'emails'])->name('index.emails');
    Route::get('/users', [UserController::class, 'users'])->name('index.users');

    Route::middleware([UserPermision::class])->group(function () {
        //register
        Route::get('/register', [LoginController::class, 'register'])->name('register');

    });

});
