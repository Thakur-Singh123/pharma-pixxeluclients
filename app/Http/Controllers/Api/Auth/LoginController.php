<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\UserResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; 
use Illuminate\Support\Facades\Validator;

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

        //Expiry time
        $expiryTime = now()->addHours(7); 

        //Create tokens
        $accessTokenResult  = $user->createToken('Access Token', ['access']);
        $refreshTokenResult = $user->createToken('Refresh Token', ['refresh']);

        //Save expiry time for access token
        $accessTokenModel = $accessTokenResult->accessToken;
        $accessTokenModel->expires_at = $expiryTime;
        $accessTokenModel->save();

        
        $userPayload = UserResponseHelper::format($user);

        //Response
        $success['status'] = 200;
        $success['message'] = "Login successfully.";
        $success['data'] = [
            'data' => $userPayload,
            'access_token' => $accessTokenResult->plainTextToken,
            'refresh_token' => $refreshTokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiryTime->toDateTimeString(),
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


    //Function for refresh token
    public function refreshToken(Request $request) {
        //Get refresh token
        $refreshToken = $request->input('refresh_token');

        if (!$refreshToken) {
            $refreshToken = $request->bearerToken();
        }

        if (is_string($refreshToken)) {
            $refreshToken = trim($refreshToken);
        }

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
        $newAccessTokenResult = $user->createToken('Access Token', ['access']);

        //expiry time
        $expiryTime = now()->addHours(7); 

        //Save expiry time
        $newAccessTokenModel = $newAccessTokenResult->accessToken;
        $newAccessTokenModel->expires_at = $expiryTime;
        $newAccessTokenModel->save();

        //Response
        $success['status'] = 200;
        $success['message'] = "Access token refreshed successfully.";
        $success['data'] = [
            'access_token' => $newAccessTokenResult->plainTextToken,
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
    
    //Function for forgot password
    public function forgot(Request $req) {
        //Validate input fields
        $validator = Validator::make($req->all(), [
            'email' => 'required|email'
        ]);
        //Respnse
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }
        //Get email
        $email = $req->email;
        $user = User::where('email', $email)->first();
        //User exists or not
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'Email not found. Please enter correct email.'
            ], 400);
        }
        //Generate OTP
        $otp = rand(100000, 999999);
        //Save OTP
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $otp, 'created_at' => now()]
        );
        //Send email
        Mail::to($email)->send(new ResetPasswordMail($otp));
        //Response
        return response()->json([
            'status' => 200,
            'message' => "We’ve sent a verification code to your email. Enter the code to reset your password."
        ]);
    }

    //Function for reset password
    public function reset(Request $req) {
        //Validate input fields
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        //Response
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }
        //Get email
        $email = $req->email;
        //Check if user fond or not
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'Email not found. Please enter correct email.'
            ], 400);
        }
        //Verify OTP
        $check = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $req->otp)
            ->first();
        //Check if otp fond or not
        if (!$check) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid OTP. Please enter the correct code.'
            ], 400);
        }
        //Check otp expire or not
        $otpTime = \Carbon\Carbon::parse($check->created_at);
        if ($otpTime->diffInMinutes(now()) >= 5) {
            return response()->json([
                'status' => 400,
                'message' => 'OTP has expired. Please request a new one.'
            ], 400);
        }
        //Reset password
        $user->password = Hash::make($req->password);
        $user->save();
        //Delete OTP
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Password reset successfully.'
        ], 200);
    }
}
