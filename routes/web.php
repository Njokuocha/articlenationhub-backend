<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GoogleAuthController;

// not found - page
Route::get('/lost/404_page', function () {
    return view('lost');
})->name('lost.page');

// email verification completed - page
Route::get('/email_verification/status/completed', function () {
    return view('signup_complete');
})->name('verified.page');

// email verification auth - page (no ui page)
Route::get('/email_verification/user', function(Request $request){
    $user = User::where('user_token', $request->input('token'))->first();

    if(!$request->token || !$user) return redirect()->route('lost.page');
    if($user->hasVerifiedEmail()) return redirect()->route('lost.page');

    $user->markEmailAsVerified();

    return redirect()->route('verified.page');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);


