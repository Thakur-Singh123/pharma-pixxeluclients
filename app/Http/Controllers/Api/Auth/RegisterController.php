<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    //Function for register
    public function register(Request $request) {
        //Validate input fields
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'joining_date' => 'required|string',
            'file_attachement' => 'required|file',
        ]);

        //If validation fails
        if ($validator->fails()) {
            //Response
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            return response()->json($error, 400);
        }

        //Get email 
        $email_exists = User::where('email', $request->email)->exists();

        //Check if email already exists or not
        if($email_exists) {
            //Response
            $error['status'] = 400;
            $error['message'] = 'This email is already taken. Please try with a new email.';
            $error['data'] = [
                'email' => $request->email
            ];
            return response()->json($error, 400);

        } else {

            //Check if file upload or not
            $filename = null;
            if ($request->hasFile('file_attachement')) {
                $file = $request->file('file_attachement');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachments'), $filename);
            }

            //Create employee code
            $lastEmployee = User::orderBy('id', 'DESC')->first();
            $newCode = $lastEmployee && $lastEmployee->employee_code
                ? str_pad((int)$lastEmployee->employee_code + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            //Create register
            $user = User::create([
                'employee_code' => $newCode,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'city' => $request->city,
                'state' => $request->state,
                'joining_date' => $request->joining_date,
                'status' => 'Pending',
                'file_attachement' => $filename,
                'user_type' => $request->user_type ?? 'MR',
                'can_sale' => $request->can_sale ?? 0,
                'nature_work' => $request->nature_work,
            ]);

            //Check if register is created is not
            if($user) {
                //Response
                $success['status'] = 200;
                $success['message'] = "Your account has been created successfully. Please wait for manager approval before login.";
                $success['data'] = [$user];
                return response()->json($success, 200);
            } else {
                //Response
                $error['status'] = 400;
                $error['message'] = 'Oops Something Wrong..';
                $error['data'] = [];
                return response()->json($error, 400);
            } 
        }
    }
}
