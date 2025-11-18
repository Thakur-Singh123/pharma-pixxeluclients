<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\MangerMR;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MRController extends Controller
{
    /**
     * Display a listing of the manager's MRs.
     */
    public function index()
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $userId = Auth::id();

        $manager = User::find($userId);

        if (!$manager) {
            $error['status'] = 404;
            $error['message'] = 'Manager not found.';
            $error['data'] = null;
            return response()->json($error, 404);
        }

        $mrs = $manager->mrs()->orderBy('ID', 'DESC')->get();

        $success['status'] = 200;
        $success['message'] = 'MR list fetched successfully.';
        $success['data'] = $mrs;
        return response()->json($success, 200);
    }

    /**
     * Store a newly created MR in storage.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'employee_code' => 'nullable|string|unique:users,employee_code',
            'territory' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'can_sale' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] = $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }

        $lastEmployee = User::orderBy('ID', 'DESC')->first();
        if ($lastEmployee && $lastEmployee->employee_code) {
            $lastCode = intval($lastEmployee->employee_code);
            $newCode = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newCode = '0001';
        }

        $mr = User::create([
            'employee_code' => $newCode,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'territory' => $request->territory,
            'city' => $request->city,
            'state' => $request->state,
            'joining_date' => $request->joining_date,
            'status' => 'Active',
            'user_type' => 'MR',
            'can_sale' => $request->input('can_sale', 0),
        ]);

        if (!$mr) {
            $error['status'] = 500;
            $error['message'] = 'Failed to add MR.';
            $error['data'] = null;
            return response()->json($error, 500);
        }

        MangerMR::create([
            'manager_id' => $userId,
            'mr_id' => $mr->id,
        ]);

        $success['status'] = 200;
        $success['message'] = 'MR created successfully.';
        $success['data'] = $mr;
        return response()->json($success, 200);
    }

    /**
     * Update the specified MR in storage.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'employee_code' => 'nullable|string|unique:users,employee_code',
            'territory' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'can_sale' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] = $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }

        $mr = User::find($id);
        if (!$mr) {
            $error['status'] = 404;
            $error['message'] = 'MR not found.';
            $error['data'] = null;
            return response()->json($error, 404);
        }

        $isUpdated = $mr->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'territory' => $request->territory,
            'city' => $request->city,
            'state' => $request->state,
            'joining_date' => $request->joining_date,
            'user_type' => 'MR',
            'can_sale' => $request->input('can_sale', $mr->can_sale),
        ]);

        if (!$isUpdated) {
            $error['status'] = 500;
            $error['message'] = 'Failed to update MR.';
            $error['data'] = null;
            return response()->json($error, 500);
        }

        MangerMR::where('mr_id', $id)
            ->where('manager_id', $userId)
            ->delete();

        MangerMR::create([
            'manager_id' => $userId,
            'mr_id' => $mr->id,
        ]);

        $success['status'] = 200;
        $success['message'] = 'MR updated successfully.';
        $success['data'] = $mr->fresh();
        return response()->json($success, 200);
    }

    /**
     * Remove the specified MR from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $userId = Auth::id();

        $mr = User::find($id);

        if (!$mr) {
            $error['status'] = 404;
            $error['message'] = 'MR not found.';
            $error['data'] = null;
            return response()->json($error, 404);
        }

        $isDeleted = $mr->delete();

        if (!$isDeleted) {
            $error['status'] = 500;
            $error['message'] = 'Failed to delete MR.';
            $error['data'] = null;
            return response()->json($error, 500);
        }

        MangerMR::where('mr_id', $id)
            ->where('manager_id', $userId)
            ->delete();

        $success['status'] = 200;
        $success['message'] = 'MR deleted successfully.';
        $success['data'] = null;
        return response()->json($success, 200);
    }

    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data'    => null,
            ], 401);
        }

        return null;
    }
}

