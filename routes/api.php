<?php

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

/*- user -*/
// Route::get('/users', [App\Http\Controllers\User\UserController::class, 'index']);
// Route::get('/users/{id}', [App\Http\Controllers\User\UserController::class, 'show']);
// Route::post('/users', [App\Http\Controllers\User\UserController::class, 'store']);
// Route::patch('/users', [App\Http\Controllers\User\UserController::class, 'update']);

Route::resource('users', 'App\Http\Controllers\User\UserController', ['except' => ['create', 'edit']]);
Route::resource('user.profile', 'App\Http\Controllers\User\UserProfileController', ['except' => ['create', 'edit']]);

/*- profile -*/
Route::resource('profiles', 'App\Http\Controllers\Profile\ProfileController', ['except' => ['create', 'edit']]);

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
