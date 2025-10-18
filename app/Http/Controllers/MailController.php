<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailConfirmationMail;
use App\Models\User;
use Illuminate\Support\Str;

class MailController extends Controller
{
    public function email_confirmation(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        Mail::to($user->email)->send(new SendEmailConfirmationMail(
            Str::of($user->name)->explode(' ')->first(), 
            env("APP_URL") . "/email_verification/user?token={$user->user_token}"
        ));
    
        return response()->json([
            'msg' => 'Mail Sent!',
            'status' => 'success'
        ]);
    }
}
