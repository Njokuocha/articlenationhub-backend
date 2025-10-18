<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
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
}
