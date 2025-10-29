<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends BaseController
{
    //Function for login
    public function login(Request $request) {
        //Validate input fields
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        //If validation fails
        if ($validator->fails()) {
            //Response
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            return response()->json($error, 400);
        }

        //Get user detail
        $user = User::where('email', $request->email)->first();

        //Check credential exists or not
        if (!$user || !Hash::check($request->password, $user->password)) {
            //Response
            $error['status'] = 400;
            $error['message'] = "Invalid credentials. Please check your email or password!";
            return response()->json($error, 400);
        }

        //Check if status active or not
        if ($user->status !== 'Active') {
            //Response
            $error['status'] = 400;
            $error['message'] = "Your request is still pending for approval. Please wait until it is approved!";
            return response()->json($error, 400);
        }

        //Expiry time
       $expiryTime = now()->addHours(7); 

        //Create token
        $accessToken  = $user->createToken('API Token')->plainTextToken;
        $refreshToken = $user->createToken('Refresh Token', ['refresh'])->plainTextToken;

        //Save expiry time
        $token = $user->tokens()->latest()->first();
        $token->expires_at = $expiryTime;
        $token->save();

        
        //Response
        $success['status'] = 200;
        $success['message'] = "Login successfully.";
        $success['data'] = [
            'data' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiryTime->toDateTimeString(),
        ];
        return response()->json($success, 200);
    }

    //Function for logout
    public function logout(Request $request) {
        //Check if user authenticated
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            //Response
            $success['status'] = 200;
            $success['message'] = "Logged out successfully.";
            return response()->json($success, 200);

        } else {
            //Response
            $error['status'] = 400;
            $error['message'] = "Unauthenticated!";
            return response()->json($error, 400);
        }
    }

    //Function for refresh token
    public function refreshToken(Request $request) {
        //Get refresh token
        $refreshToken = $request->bearerToken();

        //Check if refresh token exists
        if (!$refreshToken) {
            //Response
            $error['status'] = 400;
            $error['message'] = "Refresh token is missing!";
            return response()->json($error, 400);
        }

        //Get token
        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($refreshToken);

        //Check token validity
        if (!$tokenModel || !$tokenModel->can('refresh')) {
            //Response
            $error['status'] = 400;
            $error['message'] = "Invalid refresh token!";
            return response()->json($error, 400);
        }

        //Get user
        $user = $tokenModel->tokenable;

        //Check if user exists or not
        if (!$user) {
            //Response
            $error['status'] = 400;
            $error['message'] = "User not found for this token!";
            return response()->json($error, 400);
        }

        //Remove old access tokens
        $user->tokens()->where('name', 'Access Token')->delete();

        //Create new access token
        $newAccessToken = $user->createToken('Access Token', ['access'])->plainTextToken;

        //expiry time
        $expiryTime = now()->addHours(7); 

       //Save expiry time
        $token = $user->tokens()->latest()->first();
        $token->expires_at = $expiryTime;
        $token->save();

        //Response
        $success['status'] = 200;
        $success['message'] = "Access token refreshed successfully.";
        $success['data'] = [
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiryTime->toDateTimeString(),
        ];
        return response()->json($success, 200);
    }

    //Function for expire token
    public function expire_token(Request $request) {
        //Get token
        $token = $request->bearerToken();
        //Check if token expired or not
        if (!$token) {
            //Response
            $error['status'] = 400;
            $error['message'] = "Your token has expired. Please refresh the token or login again!";
            return response()->json($error, 400);
        }

        $accessToken = PersonalAccessToken::findToken($token);
        //Check if token expired or not
        if (!$accessToken) {

            $error['status'] = 400;
            $error['message'] = "Your token has expired. Please refresh the token or login again!";
            return response()->json($error, 400);
        }
        //Check if token expired or not
        if ($accessToken->expires_at && now()->greaterThan($accessToken->expires_at)) {
            $accessToken->delete();
            $error['status'] = 400;
            $error['message'] = "Your token has expired. Please refresh the token or login again!";
            return response()->json($error, 400);
        }
            $success['status'] = 200;
            $success['message'] = "Token is valid.";
            $success['data'] = [$accessToken->tokenable];
            return response()->json($success, 200);
    }
}
