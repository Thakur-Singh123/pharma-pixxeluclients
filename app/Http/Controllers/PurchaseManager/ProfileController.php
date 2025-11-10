<?php

namespace App\Http\Controllers\PurchaseManager;

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
        //Get user detail
        $user_profile = User::where('id', $is_login_id)->first();
        return view('purchase_manager.profiles.profile', compact('user_profile'));
    }

    //Function for edit profile
    public function edit_profile() {
        //Get auth login id
        $is_login_id = Auth::user()->id;
        //Get user detail
        $user_profile = User::where('id', $is_login_id)->first();
        return view('purchase_manager.profiles.edit-profile', compact('user_profile'));
    } 

    //Function for update profile
    public function update_profile(Request $request, $id) {
        //Validate iinput fields
        $request->validate([
            'name' =>'required|string',
            'phone' =>'required|string',
            'city' =>'required|string',
            'joining_date' =>'required|string',
            'dob' =>'required|string',
            'state' =>'required|string', 
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
                'name' =>$request->name,
                'phone' =>$request->phone,
                'city' =>$request->city,
                'joining_date' =>$request->joining_date,
                'dob' =>$request->dob,
                'state' =>$request->state,
                'image' =>$filename,
            ]);
            //Check if Profile updated or not
            if ($update_profile) {
                return back()->with('success', 'Profile updated successfully.');
            } else {
                return back()->with('success', 'someting went wrong.');
            }        
        } else {
            //Update profile record without image
            $update_profile = User::where('id', $id)->update([
                'name' =>$request->name,
                'phone' =>$request->phone,
                'city' =>$request->city,
                'joining_date' =>$request->joining_date,
                'dob' =>$request->dob,
                'state' =>$request->state,
            ]);
            //Check if Profile updated or not
            if ($update_profile) {
                return back()->with('success', 'Profile updated successfully.');
            } else {
                return back()->with('success', 'someting went wrong!');
            } 
        }
    }

    //Function for changed password
    public function change_password() {
        //Get auth login id
        $is_login_id = Auth::user()->id;
        //Get user detail
        $user_profile = User::where('id', $is_login_id)->first();
        return view('purchase_manager.profiles.change-password', compact('user_profile'));
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
