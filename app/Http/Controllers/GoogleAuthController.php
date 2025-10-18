<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // stateless redirect for google auth
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Handling both Login and Signup at one go 
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $isUser = User::where('email', $googleUser->getEmail())->first();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'fname' => $googleUser->user['given_name'],
                'lname' => $googleUser->user['family_name'],
                // 'avatar' => $googleUser->getAvatar(),
                'signup_method' => 'google'
            ]
        );

        if($isUser && $user->signup_method === 'articlenation') 
            return redirect("http://localhost:3000/login?is_user=true&auth=cancelled");

        if(!$isUser){
            $base = $user->id + 9;
            $username = Str::substr(Str::lower(Str::of($googleUser->getName())->explode(' ')->first()), 0, 7) . $base;
            $user->update([
                'user_token' => Str::random(60) . time(),
                'username' => $username,
            ]);
            
            $user->markEmailAsVerified();
        }

        $token = $user->createToken('ArticleNation_XXWebtoken', ['*'], now()->addHours(48))->plainTextToken;
        return redirect("http://localhost:3000/auth/google/callback?auth_token=$token&user_token={$user->user_token}");
    }
}
