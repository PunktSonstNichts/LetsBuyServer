<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Http\Response;
use App\Http\Requests;

class UserController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth.basic', ['only' => [
            'getApiToken',
        ]]);
    }

    public function createUser(Request $request) 
    {
    	$this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

    	$api_token = str_random(60);

    	User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'api_token' => $api_token,
            'password' => bcrypt($request['password']),
        ]);

        return response()->json(['api_token' => $api_token]);
    }

    public function getApiToken(Request $request){
    	
    }

    public function getToken()
    {

    }

}
