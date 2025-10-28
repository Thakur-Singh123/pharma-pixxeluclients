<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
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

        //Create token
        $accessToken  = $user->createToken('API Token')->plainTextToken;
        $refreshToken = $user->createToken('Refresh Token', ['refresh'])->plainTextToken;

        //Define base URL
        $baseUrl = match ($user->user_type) {
            'Manager' => url('/api/manager/dashboard'),
            'MR' => url('/api/mr/dashboard'),
        };
        
        //Response
        $success['status'] = 200;
        $success['message'] = "Login successfully.";
        $success['data'] = [
            'data' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'base_url' => $baseUrl,
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

        //Response
        $success['status'] = 200;
        $success['message'] = "Access token refreshed successfully.";
        $success['data'] = [
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer'
        ];
        return response()->json($success, 200);
    }
}
