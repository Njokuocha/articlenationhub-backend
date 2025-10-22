<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // scan available usernames
    public function check_username(Request $request)
    {
        $username = $request->input('username');
        $checkUsernameDuplicate = User::where('username', $username)->first();
        if($checkUsernameDuplicate) return response()->json([
            'status' => 'failed',
        ]);
        else return response()->json([
            'status' => 'success',
        ]);
    }

    // change user names (username and name)
    public function settings_names(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
        ]);

        $username = $request->input('username');

       $usernameIsSame = User::where('username', $username)->first();
        $fname =  Str::ucfirst(Str::of($request->input('name'))->explode(' ')->first());
        $arr = explode(" ", $request->input('name'));
        $lname = ucwords(implode(" ", array_slice($arr, 1, count($arr))));

        $request->user()->update([
            'fname' => $fname ?? null,
            'lname' => $lname ?? null,
            'name' => $request->input('name'),
            'username' => !$usernameIsSame ? $request->input('username') : $request->user()->username,
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => 
                $usernameIsSame ?
                    "Name Updated Successfully" :
                        "Username and Name Updated Successfully",
        ]);
    }

    // change user phone_number
    public function settings_phone_number(Request $request)
    {
        $request->user()->update([
            'phone_number' => $request->input('phone_number'),
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }
    public function settings_country_and_bio(Request $request)
    {
        $request->user()->update([
            'country' => $request->input('country'),
            'bio' => $request->input('bio'),
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    // change user profile picture (avatar)
    public function action_change_avatar(Request $request)
    {
        $request->validate([
            "avatar" => "required|file|mimes:jpg,png,jpeg|max:5120", // max filesize of 5 MB
        ]);

        $user = $request->user();

        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('uploads/user', 'public');
        }
        
        // Delete the old image if it exists
        if ($user->avatar) {
            Storage::disk('public')->delete(explode("/storage/", $user->avatar)[1]);
        }

        $request->user()->update([
            "avatar" => "http://localhost:8000/storage/" . $path,
        ]);

        return response()->noContent();
    }

    public function fetch_user_with_blogs(User $user)
    {
        return $user->load(['blogs' => function($query){
            $query->orderBy('created_at', 'desc')->limit(5);
        }]);
    }

    public function fetch_writers()
    {
        return User::inRandomOrder()->limit(5)->get();
    }

}
