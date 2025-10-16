<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //Function for login user
    public function user_login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        //Check if user is login or not
        if(auth()->attempt($data)) {
            $token = auth()->user()->createToken('API Token')->accessToken;
            //Return Responce
            $message['status'] = 200;
            $message['message'] = "Login Successfully";
            $message['data'] = [];
            $message['token'] = $token;
            return response()->json($message, 200);
        } else {
            $message['status'] = 400;
            $message['message'] = "Invalid login credentials";
            $message['data'] = [];
            return response()->json($message, 400);

        }
    }
}
