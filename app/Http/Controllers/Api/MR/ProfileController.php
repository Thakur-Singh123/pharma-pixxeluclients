<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //Function for show account
    public function account() {
        //Get auth login detail
        $is_login_id = Auth::user()->id;
        //Get user details
        $user_profile = User::where('id', $is_login_id)->first();
        //Check if user exists or not
        if ($user_profile ) {
            $success['status'] = 200;
            $success['message'] = "Account get successfully.";
            $success['data'] = [$user_profile];
            return response()->json($success, 200);
        } else {
            $error['status'] = 400;
            $error['message'] = "Oops! Something went wrong.";
            $error['data'] = null;
            return response()->json($error, 400);
        }
    }

    //Function for update account
    public function update_account(Request $request, $id) {
        //Validate input fields
        $validator = \Validator::make($request->all(), [
            'name' =>'required|string',
            'address' =>'required|string',
            'phone' =>'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Check if image exists or not
        $filename = "default.png";
        $user_detail = User::find($id);
        //Check if user exists or not
        if (!$user_detail) {
            $error['status'] = 404;
            $error['message'] = "User not found.";
            $error['data'] = null;
            return response()->json($error, 404);
        }
        //Check if image exists or not
        if ($request->hasFile('image')) {
            $imagePath = public_path('uploads/users/' . $user_detail->image);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
        } else {
            $filename = $user_detail->image ?? "default.png";
        }
        //Update profile record
        $update_profile = $user_detail->update([
            'name'  => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'image' => $filename,
        ]);
        //Check if profile exists or not
        if ($update_profile) {
            //Get profile detail
            $updated_user = User::find($id);
            //Success response
            $success['status'] = 200;
            $success['message'] = "Account updated successfully.";
            $success['data'] = $updated_user;
            return response()->json($success, 200);
        } else {
            $error['status'] = 400;
            $error['message'] = "Oops! Something went wrong.";
            $error['data'] = null;
            return response()->json($error, 400);
        }
    }
}
