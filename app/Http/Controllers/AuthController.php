<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\SendEmailConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            "fname" => "required|string",
            "lname" => "nullable|string",
            "email" => "required|email|unique:users",
            "password" => "required|min:4|confirmed"
        ]);

        $user = User::create([
            "fname" => ucfirst($request->input('fname')),
            "lname" => ucfirst($request->input('lname')),
            "name" => ucfirst($request->input('fname')) . " " . ucfirst($request->input('lname')),
            "email" => Str::lower($request->input("email")),
            "password" => $request->input("password"),
            'user_token' => Str::random(60) . time(),
        ]);

        $base = $user->id + 9;
        $user->update([
            'username' => Str::substr(Str::lower($request->input('fname')), 0, 7) . $base,
        ]);

        Mail::to($request->input('email'))->send(new SendEmailConfirmationMail(
            ucfirst($request->input('fname')),
            env("APP_URL") . "/email_verification/user?token={$user->user_token}"
        ));

        return response()->json([
            'status' => 'success',
            'msg' => 'User Created'
        ]);
        
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $identifier = $request->input('username');

        if(Str::contains($identifier, '@')){
            $user = User::where('email', $identifier)->first();
        } else{
            $user = User::where('username', $identifier)->first();
        }

        if($user && !$user->hasVerifiedEmail()) return response()->json([
            'msg' => 'Account Unverified!',
        ], 401);

        if(!$user || !Hash::check($request->password, $user->password)) return response()->json([
            'msg' => 'Invalid Credentials',
            'status' => 'failed'
        ], 401);

        $token = $user->createToken('ArticleNation_XXWebtoken', ['*'], now()->addHours(48))->plainTextToken;

        return response()->json([
            'token' => $token,
            'status' => 'success',
            'msg' => 'Login Successful'
        ]);
    }

    public function verify_user(Request $request)
    {
        $token = $request->input('token');
        $user = User::where('user_token', $token)->first();

        if(!$user || !$token){
            return response()->json([
                'status' => 'failed',
                'msg' => "Couldn't authenticate user"
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function user_auth(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function user_auth_logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();
        return response()->noContent();
    }
}
