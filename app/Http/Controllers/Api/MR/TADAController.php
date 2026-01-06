<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\TADARecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\MobilePusher;
use App\Notifications\TADACreateNotification;
use App\Models\User;
use App\Services\FirebaseService;

class TADAController extends Controller
{
    Protected $fcmService;

    public function __construct(FirebaseService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Ensure the current request is authenticated.
     */
    private function ensureAuthenticated()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => "Unauthorized access. Please login first.",
                'data' => null,
            ], 401);
        }

        return null;
    }

    //Function for all TADA records
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = TADARecords::where('mr_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $status = ucfirst(strtolower($request->query('status')));
            if (in_array($status, ['Pending', 'Approved', 'Rejected'])) {
                $query->where('status', $status);
            }
        }

        $tada_records = $query->paginate(5);

        $tada_records->getCollection()->transform(function ($tada) {
            $tada->image_url = $tada->attachment
                ? asset('public/uploads/ta_da/' . $tada->attachment)
                : null;
            return $tada;
        });

        $message = $tada_records->count()
            ? "TADA records fetched successfully."
            : "No TADA records found.";

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $tada_records,
        ], 200);
    }

    //Function for store TADA form
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        //Validate input fields
        $validator = Validator::make($request->all(), [
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric', 
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            //'attachment' => 'required|file',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Check if attachment exists or not
        $attachmentPath = null;
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
        }
        //Total amount
        $total_amount = $request->ta_amount + $request->da_amount;
        //Create TADA
        $is_create_tada = TADARecords::create([
            'mr_id' => auth()->id(),
            'travel_date' => $request->travel_date,
            'place_visited' => $request->place_visited,
            'distance_km' => $request->distance_km,
            'ta_amount' => $request->ta_amount,
            'da_amount' => $request->da_amount,
            'total_amount' => $total_amount,
            'mode_of_travel' => $request->mode_of_travel,
            'purpose_of_visit' => $request->purpose,
            'outstation_stay' => $request->outstation_stay,
            'remarks' => $request->remarks,
            'attachment' => $attachmentPath,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
        //Check if tada created or not
        if ($is_create_tada) {
            $is_create_tada->refresh();
            $is_create_tada->image_url = $is_create_tada->attachment
                ? asset('public/uploads/ta_da/' . $is_create_tada->attachment)
                : null;
            $fcmResponse = [];
            //Get Manager
            $managerId = auth()->user()->managers->pluck('id')->first();
            //Notification
            $manager = User::find($managerId);
            if($manager){
                $manager->notify(new TADACreateNotification($is_create_tada));
                //FCM Notification
                $fcmResponses = $this->fcmService->sendToUser($manager, [
                    'id' => $is_create_tada->id,
                    'title' => 'TADA Created',
                    'message' => 'A new TADA has been created. with amount of INR: ' . $is_create_tada->total_amount,
                    'type' => 'tada',
                    'is_read' => 'false',
                    'created_at' => now()->toDateTimeString(),
                ]);
            }
                //Response
                $success['status'] = 200;
                $success['message'] = "TADA created successfully.";
                $success['data'] = $is_create_tada;
                $success['fcm_response'] = $fcmResponses;
                return response()->json($success, 200);
        } else {
            $error['status'] = 400;
            $error['message'] = "Oops! Something went wrong.";
            $error['data'] = null;
            return response()->json($error, 400);
        }
    }

    //Function for update tada
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric',
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            //'attachment' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $tada = TADARecords::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$tada) {
            return response()->json([
                'status' => 404,
                'message' => "This record not found.",
                'data' => null
            ], 404);
        }

        // File upload
        $attachmentPath = $tada->attachment;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
        }

        $total_amount = $request->ta_amount + $request->da_amount;

        $updatePayload = [
            'travel_date' => $request->travel_date,
            'place_visited' => $request->place_visited,
            'distance_km' => $request->distance_km,
            'ta_amount' => $request->ta_amount,
            'da_amount' => $request->da_amount,
            'total_amount' => $total_amount,
            'mode_of_travel' => $request->mode_of_travel,
            'outstation_stay' => $request->outstation_stay,
            'purpose_of_visit' => $request->purpose,
            'remarks' => $request->remarks,
            'attachment' => $attachmentPath,
            'approved_by' => null,
            'approved_at' => null,
        ];

        $tada->update($updatePayload);

        $tada->refresh();
        $tada->image_url = $tada->attachment
            ? asset('public/uploads/ta_da/' . $tada->attachment)
            : null;

        return response()->json([
            'status' => 200,
            'message' => "TADA updated successfully.",
            'data' => $tada
        ], 200);
    }

    //Function for delete tada
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $tada = TADARecords::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$tada) {
            $error['status'] = 404;
            $error['message'] = "This record not found.";
            $error['data'] = null;
            return response()->json($error, 404);
        }

        $is_delete_tada = $tada->delete();

        if ($is_delete_tada) {
            $success['status'] = 200;
            $success['message'] = "TADA deleted successfully.";
            $success['data'] = null;
            return response()->json($success, 200);
        }

        $error['status'] = 400;
        $error['message'] = "Oops! Something went wrong.";
        $error['data'] = null;
        return response()->json($error, 400);
    }
}
