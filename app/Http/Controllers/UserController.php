<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth.basic', ['only' => [
            'getApiToken',
        ]]);
    }

    public function postUser(Request $request) 
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|max:255',
            'api_token' => 'max:60',
            'password' => 'required|min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => 'User login failed.']);
        }

        if ($request->has('api_token')) {
            try{
                $user = User::where('api_token', $request['api_token'])->firstOrFail();
                return response()->json([
                    'api_token' => $user->api_token,
                    'name' => $user->name,
                    'picture' => $user->picture
                ]);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'User login failed.']);
            }
        }

        $api_token = str_random(60);
        try {
            $user = User::whereRaw("name = '".$request['name']."' OR email = '".$request['email']."'")->firstOrFail();
            if (!Hash::check($request['password'], $user->password)){
                return response()->json(['error' => 'User login failed.']);
            }
        } catch (ModelNotFoundException $e) {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'api_token' => $api_token,
                'password' => bcrypt($request['password']),
            ]);
        }

        return response()->json([
            'api_token' => $user->api_token,
            'name' => $user->name,
            'picture' => $user->picture
        ]);
    }

}
