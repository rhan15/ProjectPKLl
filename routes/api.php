<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
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

Route::prefix('me')->group(function () {
    Route::get('information', [UserController::class, 'information']);
});

Route::prefix('user')->group(function () {
    Route::get('index', [UserController::class, 'index']);
    Route::post('store', [UserController::class, 'store']);
    Route::patch('update/{id}', [UserController::class, 'update']);
    Route::get('show/{id}', [UserController::class, 'show']);
});


Route::prefix('auth')->group(function () {
    Route::get('get-otp', [AuthController::class, 'getOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('register', [AuthController::class, 'register']);
});


/*- profile -*/
Route::resource('profiles', 'App\Http\Controllers\Profile\ProfileController', ['except' => ['create', 'edit']]);

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::get('users/login', 'App\Http\Controllers\User\UserController@login');
