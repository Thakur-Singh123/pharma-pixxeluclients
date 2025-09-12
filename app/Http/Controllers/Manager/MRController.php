<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MangerMR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MRController extends Controller
{
   //Function for all mrs
    public function index() {
        //Get mrs
        $manager = User::find(auth()->id());
        $mrs = $manager->mrs()->OrderBy('ID', 'DESC')->paginate(10);
        return view('manager.mr-management.index', compact('mrs'));
    }

    //Function for create mr
    public function create() {
        return view('manager.mr-management.create');
    }

    //Function for submit mr
    public function store(Request $request) {
        //Validation input fields
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|email|unique:users,email',
            'password' =>'required|min:6',
            'phone' =>'nullable|string|max:15',
            'employee_code' =>'nullable|string|unique:users,employee_code',
            'territory' =>'nullable|string|max:255',
            'city' =>'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'joining_date' =>'nullable|date',
            'status' =>'required|in:Active,Pending',
        ]);
        //Get last employee code
        $lastEmployee = User::OrderBy('ID', 'DESC')->first();
        //Check if employee code exist or not
        if ($lastEmployee && $lastEmployee->employee_code) {
            //Remove leading zeros and increment
            $lastCode = intval($lastEmployee->employee_code);
            $newCode = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newCode = '0001';
        }
        //Create mr
        $is_create_mr = User::create([
            'employee_code' => $newCode,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'territory' => $request->territory,
            'city' => $request->city,
            'state' => $request->state,
            'joining_date' => $request->joining_date,
            'status' => $request->status,
            'user_type' => 'MR',
            'can_sale' => $request->can_sale,
        ]);
        //Check if mr created or not
        if (!$is_create_mr) {
            return redirect()->back()->with('error', 'Failed to add MR');
        } else {
            //Create MangerMR record
            MangerMR::create([
                'manager_id' => Auth::id(),
                'mr_id'      => $is_create_mr->id,
            ]);
        }
        return redirect()->route('manager.mrs.index')->with('success', 'MR created successfully');
    }

    //Function for edit mr
    public function edit($id) {
        //Get mr detail
        $mr_detail = User::find($id);
        return view('manager.mr-management.edit-mr', compact('mr_detail'));
    }

    //Function for update mr
    public function update(Request $request, $id) {
        //Validation input fields
        $request->validate([
            'name' =>'required|string|max:255',
            //'email' =>'required|email|unique:users,email',
            'password' =>'required|min:6',
            'phone' =>'nullable|string|max:15',
            'employee_code' =>'nullable|string|unique:users,employee_code',
            'territory' =>'nullable|string|max:255',
            'city' =>'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'joining_date' =>'nullable|date',
            'status' =>'required|in:Active,Pending',
        ]);
        //Get mr detail
        $mr = User::findOrFail($id);
        $is_update_mr = $mr->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'territory' => $request->territory,
            'city' => $request->city,
            'state' => $request->state,
            'joining_date' => $request->joining_date,
            'status' => $request->status,
            'user_type' => 'MR',
            'can_sale' => $request->can_sale,
        ]);
        //Check if mr updated or not
        if (!$is_update_mr) {
            return redirect()->back()->with('error', 'Failed to updated MR');
        } else {
            //Delete old mr
            MangerMR::where('mr_id', $id)->where('manager_id', Auth::id())->delete();
            //Create ManagerMR record
            MangerMR::create([
                'manager_id' => Auth::id(),
                'mr_id' => $mr->id,
            ]);
        }
        return redirect()->route('manager.mrs.index')->with('success', 'MR updated successfully');
    }

    //Function for delete mr
    public function destroy($id) {
        //Delete mr
        $is_delete_mr = User::where('id', $id)->delete();
        //Check if mr deleted or not
        if ($is_delete_mr) {
            MangerMR::where('mr_id', $id)->where('manager_id', Auth::id())->delete();
            return redirect()->route('manager.mrs.index')->with('success', 'MR deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to deleted MR');
        }
    }
}
