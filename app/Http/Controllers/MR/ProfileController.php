<?php

namespace App\Http\Controllers\MR;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    //Function for show profile
    public function profile() {
        //Get auth login detail
        $is_login_id = Auth::user()->id;
        //Get user details
        $user_profile = User::where('id', $is_login_id)->first();
        return view('mr.profiles.profile', compact('user_profile'));
    }

    //Function for edit profile
    public function edit_profile() {
    //Get auth login id
    $is_login_id = Auth::user()->id;
    $user_profile = User::where('id', $is_login_id)->first();
        return view('mr.profiles.edit-profile', compact('user_profile'));
    } 

    //Function for update profile
    public function update_profile(Request $request, $id) {
        //Validate iinput fields
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|string',
            'mobile' => 'required|string',
            'gender' => 'required|string', 
            'address' => 'required|string',
        ]);
        //Check if image is exit or not
        $filename ="default.png";
        if($request->hasFile('image')) {
            //Get user detail
            $user_detail = User::find($id);    
            //Get image path
            $imagePath = public_path('uploads/users/' . $user_detail->avatar);
            //Delete image file
            if(file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
            //Get request image
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
            //Update profile record with image
            $update_profile = User::where('id', $id)->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' =>$request->first_name,
                'last_name' =>$request->last_name,
                'dob' =>$request->dob,
                'mobile' =>$request->mobile,
                'gender' =>$request->gender,
                'address' =>$request->address,
                'image' =>$filename,
            ]);
            //Check if Profile updated or not
            if ($update_profile) {
                return back()->with('success', 'Profile updated successfully.');
            } else {
                return back()->with('unsuccess', 'someting went wrong.');
            }        
        } else {
            //Update profile record without image
            $update_profile = User::where('id', $id)->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' =>$request->first_name,
                'last_name' =>$request->last_name,
                'dob' =>$request->dob,
                'mobile' =>$request->mobile,
                'gender' =>$request->gender,
                'address' =>$request->address,
            ]);
            //Check if Profile updated or not
            if ($update_profile) {
                return back()->with('success', 'Profile updated successfully.');
            } else {
                return back()->with('unsuccess', 'someting went wrong!');
            } 
        }
    }

    //Function for changed password
    public function change_password() {
        //Get auth login id
        $is_login_id = Auth::user()->id;
        $user_profile = User::where('id', $is_login_id)->first();
        return view('mr.profiles.change-password', compact('user_profile'));
    }

    //Function for submit changed password 
    public function submit_change_password(Request $request) {
        //Validate inputs fields
        $request->validate([
            'password' =>'required|string',
            'confirm_password' =>'required|string',
        ]);
        //Get user detail
        $user = Auth::user();
        //Check password and confirm password match
        if ($request->password !== $request->confirm_password) {
            return redirect()->back()->with('unsuccess', 'New password and confirm password do not match.');
        }
        //Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Your password has been changed successfully.');
    }
}
