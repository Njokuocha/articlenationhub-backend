<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;


// Testing routes
Route::get('/items', function(){
    return response()->json([
        'items' => ['hola', 'greet', 'ball']
    ]);
});
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/mail/email_confirmation', [MailController::class, 'email_confirmation']);
Route::post('/user/verify', [AuthController::class, 'verify_user']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/auth/user', [AuthController::class, 'user_auth']);
    Route::post('/auth/user/logout', [AuthController::class, 'user_auth_logout']);
    Route::post('/check/username', [UserController::class, 'check_username']);

    Route::post('/settings/names', [UserController::class, 'settings_names']);
    Route::post('/settings/phone_number', [UserController::class, 'settings_phone_number']);
    Route::post('/settings/country_and_bio', [UserController::class, 'settings_country_and_bio']);
});

