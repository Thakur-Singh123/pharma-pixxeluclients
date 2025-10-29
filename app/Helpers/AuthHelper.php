<?php

namespace App\Helpers;

use Laravel\Sanctum\PersonalAccessToken;

class AuthHelper
{
    //Function for check token
    public static function checkAuthToken($request) {
        //Get token
        $token = $request->bearerToken();
        //Check if auth exists or not
        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.'
            ], 401);
        }
        //Get AccessToken
        $accessToken = PersonalAccessToken::findToken($token);
        //Check if token is exists or not
        if (!$accessToken) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid token. Please login again.'
            ], 401);
        }
        //Check expiry 
        if ($accessToken->expires_at && now()->greaterThan($accessToken->expires_at)) {
            $accessToken->delete();
            return response()->json([
                'status' => 401,
                'message' => 'Your token has expired. Please login again.'
            ], 401);
        }
        return true;
    }
}
