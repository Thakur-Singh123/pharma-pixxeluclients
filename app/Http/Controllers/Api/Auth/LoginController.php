<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\UserResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    //Function for login
    public function login(Request $request) {
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            return response()->json($error, 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $error['status'] = 400;
            $error['message'] = "Invalid credentials. Please check your email or password!";
            return response()->json($error, 400);
        }

        if ($user->status !== 'Active') {
            $error['status'] = 400;
            $error['message'] = "Your request is still pending for approval. Please wait until it is approved!";
            return response()->json($error, 400);
        }

        // =============================
        // Expiry time commented out
        // =============================
        // $expiryTime = now()->addHours(7); 

        $accessTokenResult  = $user->createToken('Access Token', ['access']);
        $refreshTokenResult = $user->createToken('Refresh Token', ['refresh']);

        $accessTokenModel = $accessTokenResult->accessToken;

        // =============================
        // Save expiry time commented
        // =============================
        // $accessTokenModel->expires_at = $expiryTime;
        
        $accessTokenModel->save();

        $userPayload = UserResponseHelper::format($user);

        $success['status'] = 200;
        $success['message'] = "Login successfully.";
        $success['data'] = [
            'data' => $userPayload,
            'access_token' => $accessTokenResult->plainTextToken,
            'refresh_token' => $refreshTokenResult->plainTextToken,
            'token_type' => 'Bearer',

            // =============================
            // removed expire time from response
            // =============================
            // 'expires_at' => $expiryTime->toDateTimeString(),
        ];
        return response()->json($success, 200);
    }


    public function logout(Request $request)
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return response()->json([
                'status' => 400,
                'message' => 'You have already logged out, please login first.'
            ], 400);
        }

        $token = explode(' ', $header)[1] ?? null;
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'status' => 400,
                'message' => 'You have already logged out, please login first.'
            ], 400);
        }

        $accessToken->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully.'
        ]);
    }


    public function refreshToken(Request $request) {
        
        $refreshToken = $request->bearerToken();

        if (!$refreshToken) {
            $error['status'] = 400;
            $error['message'] = "Refresh token is missing!";
            return response()->json($error, 400);
        }

        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($refreshToken);

        if (!$tokenModel || !$tokenModel->can('refresh')) {
            $error['status'] = 400;
            $error['message'] = "Invalid refresh token!";
            return response()->json($error, 400);
        }

        $user = $tokenModel->tokenable;

        if (!$user) {
            $error['status'] = 400;
            $error['message'] = "User not found for this token!";
            return response()->json($error, 400);
        }

        $user->tokens()->where('name', 'Access Token')->delete();

        $newAccessTokenResult = $user->createToken('Access Token', ['access']);

        $newAccessTokenModel = $newAccessTokenResult->accessToken;

        // =============================
        // refreshed token expiry commented
        // =============================
        // $newAccessTokenModel->expires_at = $expiryTime;

        $newAccessTokenModel->save();

        $success['status'] = 200;
        $success['message'] = "Access token refreshed successfully.";
        $success['data'] = [
            'access_token' => $newAccessTokenResult->plainTextToken,
            'token_type' => 'Bearer',
            // 'expires_at' => $expiryTime->toDateTimeString(),
        ];
        return response()->json($success, 200);
    }


    public function expire_token(Request $request) {
        
        $token = $request->bearerToken();

        if (!$token) {
            $error['status'] = 400;
            $error['message'] = "Your token has expired. Please refresh the token or login again!";
            return response()->json($error, 400);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            $error['status'] = 400;
            $error['message'] = "Your token has expired. Please refresh the token or login again!";
            return response()->json($error, 400);
        }

        // =============================
        // expiry time check commented
        // =============================
        if (false && $accessToken->expires_at && now()->greaterThan($accessToken->expires_at)) {
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
