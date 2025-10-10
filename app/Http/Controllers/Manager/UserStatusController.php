<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use DB;
use Auth;

class UserStatusController extends Controller
{
    //Function for active users
    public function all_active_users() {
        //Get auth login detail
        $manager = User::find(auth()->id());
        //Get active users
        $active_users = $manager->mrs()->OrderBy('ID', 'DESC')->where('user_type', 'MR')->where('status', 'Active')->paginate(10);
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
        try {
            $user = User::findOrFail($id);

            // Check if already approved
            if ($user->status === 'Active') {
                return redirect()->back()->with('error', 'User is already approved.');
            }

            //Approve user
            $user->status = 'Active';
            $user->save();

            //Check if relation already exists to avoid duplicate entries
            // $exists = MangerMR::where('manager_id', Auth::id())
            //                 ->where('mr_id', $id)
            //                 ->exists();

            // if (!$exists) {
            //     MangerMR::create([
            //         'manager_id' => Auth::id(),
            //         'mr_id'      => $id,
            //     ]);
            // }
            //Remove old
            MangerMR::where('mr_id', $id)->delete();
            //Create update MR
            MangerMR::updateOrCreate([
                'manager_id' => Auth::id(),
                'mr_id'      => $id,
            ]);
            
            return redirect()->back()->with('success', 'User approved successfully.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    //Function for suspend user
    public function reject_user($id) {
        //Get user detail
        $user_record = User::findOrFail($id);
        $user_record->status = 'Suspend';
        $user_record->save();
        DB::table('sessions')->where('user_id', $user_record->id)->delete();

        return redirect()->back()->with('success', 'User suspend successfully.');
    }

    //Function for pending user
    public function pending_user($id) {
        //Get user detail
        $user_record = User::findOrFail($id);
        $user_record->status = 'Pending';
        $user_record->save();
        DB::table('sessions')->where('user_id', $user_record->id)->delete();
        
        return redirect()->back()->with('success', 'User pending successfully.');
    }
 }
