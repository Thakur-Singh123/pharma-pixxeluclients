<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TADARecords;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\MobilePusher;

class TADAController extends Controller
{
    //Function for Ensure authenticated
    private function ensureAuthenticated() {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => "Unauthorized access. Please login first.",
                'data' => null,
            ], 401);
        }

        return null;
    }

    //Function for all tADA Records (List)
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all mrs
        $manager = auth()->user();
        $mrs = $manager->mrs()->pluck('users.id');
        //query
        $query = TADARecords::whereIn('mr_id', $mrs)->orderBy('id', 'DESC');
        //filter
        if ($request->status && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }
        //all records
        $records = $query->paginate(10);
        //image url
        foreach ($records as $item) {
            $item->attachment = $item->attachment 
                ? asset('public/uploads/ta_da/' . $item->attachment) 
                : null;
        }
        //response
        return response()->json([
            'status' => true,
            'message' => "TADA records fetched successfully",
            'data' => $records
        ]);
    }

    //Function for approve TADA
    public function approve($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get tada detail
        $record = TADARecords::find($id);
        //check tada found or not
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'TADA record not found'
            ], 404);
        }
        //check status approved or not
        if ($record->status == 'approved') {
            return response()->json([
                'status' => false,
                'message' => 'This TADA is already approved. Reject it first to approve again.'
            ], 400);
        }
        //save
        $record->status = 'approved';
        $record->approved_by = auth()->id();
        $record->approved_at = now();
        $record->save();

$managerName = auth()->user()->name;
$amount = $record->total_amount;

// fetch MR properly
$mr = User::find($record->mr_id);

if ($mr) {
    MobilePusher::send(
        $mr->id,
        "TADA Approved",
        "Your TADA claim of INR {$amount} has been approved successfully by Manager {$managerName}.",
        "tada",
        $record->id     // <-- 5th param: item id
    );
}


        //response
        return response()->json([
            'status' => true,
            'message' => 'TADA approved successfully',
            'data' => 'null'
        ]);
    }

    //Function for reject TADA
    public function reject($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get tada detail
        $record = TADARecords::find($id);
        //check if tada found or not
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'TADA record not found'
            ], 404);
        }
        //check if status rejected or not
        if ($record->status == 'rejected') {
            return response()->json([
                'status' => false,
                'message' => 'This TADA is already rejected. Approve it first to reject again.'
            ], 400);
        }
        $managerName = auth()->user()->name;
$amount = $record->total_amount;

// fetch MR
$mr = User::find($record->mr_id);

if ($mr) {
    MobilePusher::send(
        $mr->id,
        "TADA Rejected",
        "Your TADA claim of INR {$amount} has been rejected by Manager {$managerName}.",
        "tada",
        $record->id     // <-- 5th param
    );
}

        //save tada
        $record->status = 'rejected';
        $record->approved_by = auth()->id();
        $record->approved_at = now();
        $record->save();

        
        //response
        return response()->json([
            'status' => true,
            'message' => 'TADA rejected successfully',
            'data' => 'null'
        ]);
    }

    //Function for update tada
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //validate inputs fields
        $validator = Validator::make($request->all(), [
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric',
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //get tada detail
        $record = TADARecords::find($id);
        //check tada found or not
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }
        //Upload image
        $filename = $record->attachment;
        //check if image exists or not
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ta_da'), $filename);
        }
        //total amount
        $total = $request->ta_amount + $request->da_amount;
        //uppdate tada
        $record->update([
            'travel_date' => $request->travel_date,
            'place_visited' => $request->place_visited,
            'distance_km' => $request->distance_km,
            'ta_amount' => $request->ta_amount,
            'da_amount' => $request->da_amount,
            'total_amount' => $total,
            'mode_of_travel' => $request->mode_of_travel,
            'purpose_of_visit' => $request->purpose,
            'outstation_stay' => $request->outstation_stay,
            'remarks' => $request->remarks,
            'attachment' => $filename,
            'approved_by' => null,
            'approved_at' => null,
            'status' => 'pending'
        ]);
        //image URL
        $record->image_url = asset('uploads/ta_da/' . $filename);
        //response
        return response()->json([
            'status' => true,
            'message' => 'TADA updated successfully',
            'data' => $record
        ]);
    }
    
    //Function for delete tada
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get tada detail
        $record = TADARecords::find($id);
        //check if tada found or not
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }
        //delete tada
        $record->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'TADA deleted successfully'
        ]);
    }
}
