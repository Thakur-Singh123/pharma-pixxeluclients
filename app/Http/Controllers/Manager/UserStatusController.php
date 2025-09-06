<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserStatusController extends Controller
{
    //Function for active users
    public function all_active_users() {
        //Get users
        $active_users = User::OrderBy('ID','DESC')->where('user_type', 'MR')->where('status', 'Active')->paginate(5);
        return view('manager.users-status.active-users', compact('active_users'));
    }

    //Function for pending users
    public function all_pending_users() {
        //Get users
        $pending_users = User::OrderBy('ID','DESC')->where('user_type', 'MR')->where('status', 'Pending')->paginate(5);
        return view('manager.users-status.pending-users', compact('pending_users'));
    }

    //Function for suspend users
    public function all_suspend_users() {
        //Get users
        $all_users = User::OrderBy('ID','DESC')->where('user_type', 'MR')->where('status', 'Suspend')->paginate(5);
        return view('manager.users-status.suspend-users', compact('all_users'));
    }

   //Function for approve user
    public function approve_user($id) {
        $user_record = User::findOrFail($id);
        $user_record->status = 'Active';
        $user_record->save();
        return redirect()->back()->with('success', 'User approved successfully.');
    }

    //Function for suspend user
    public function reject_user($id) {
        $user_record = User::findOrFail($id);
        $user_record->status = 'Suspend';
        $user_record->save();
        return redirect()->back()->with('success', 'User suspend successfully.');
    }

    //Function for pending user
    public function pending_user($id) {
        $user_record = User::findOrFail($id);
        $user_record->status = 'Pending';
        $user_record->save();
        return redirect()->back()->with('success', 'User pending successfully.');
    }
 }
