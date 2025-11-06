<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\BaseController;
use App\Models\TADARecords;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TADACreateNotification;

class TADAController extends BaseController
{
    //Function for all TADA records
    public function all_tada() {
        $query = TADARecords::where('mr_id', auth()->id());
        $query = $query->orderBy('created_at', 'desc');
        if(request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $query = $query->where('status', request('status'));
        }
        $tada_records = $query->paginate(5);
        //Append full attachment URL
        foreach ($tada_records as $tada) {
            if ($tada->attachment) {
                $tada->image_url = asset('public/uploads/ta_da/' . $tada->attachment);
            } else {
                
            }
        }
        //Check if events exists or not
        if ($tada_records) {
            $success['status'] = 200;
            $success['message'] = "Tada records get successfully.";
            $success['data'] = [$tada_records];
            return response()->json($success, 200);
        } else {
            $error['status'] = 404;
            $error['message'] = "No tada found.";
            $error['data'] = [];
            return response()->json($error, 404);
        }
    }

    //Function for store TADA form
    public function create_tada(Request $request) {
        //Validate input fields
        $validator = \Validator::make($request->all(), [
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric', 
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'attachment' => 'required|file',
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
            $success['status'] = 200;
            $success['message'] = "Tada created successfully.";
            $success['data'] = [$is_create_tada];
            return response()->json($success, 200);
            //notification
            $manager  = auth()->user()->managers->pluck('id');
            $manager  = $manager->first();
            $manager  = User::find($manager);
            $manager->notify(new TADACreateNotification($is_create_tada));
        } else {
            $error['status'] = 400;
            $error['message'] = "Oops! Something went wrong.";
            $error['data'] = null;
            return response()->json($error, 400);
        }
    }

    //Function for update tada
    public function update_tada(Request $request) {
        //Get id
        $id = $request->id;
        //Validate input fields
        $validator = \Validator::make($request->all(), [
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric',
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
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
        //Check if attachment exists or not
        $attachmentPath = null;
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
            //Total amount
            $total_amount = $request->ta_amount + $request->da_amount;
            //Update TADA with image
            $is_update_tada = TADARecords::where('id',$id)->update([
                'mr_id' => auth()->id(),
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
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                //notification
                // $mr = auth()->user();
                // $manager  = $mr->managers()->pluck('users.id');
                // $manager  = $manager->first();
                // $manager  = User::find($manager);
                // $manager->notify(new TADACreateNotification($is_update_tada));
                $updatedData = TADARecords::find($id);
                $success['status'] = 200;
                $success['message'] = "Tada updated successfully.";
                $success['data'] = $updatedData;
                return response()->json($success, 200);
            } else {
                $error['status'] = 400;
                $error['message'] = "Oops! Something went wrong.";
                $error['data'] = null;
                return response()->json($error, 400);
            }
        } else {
            //Total amount
            $total_amount = $request->ta_amount + $request->da_amount;
            //Update TADA with image
            $is_update_tada = TADARecords::where('id',$id)->update([
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
                'approved_by' => null,
                'approved_at' => null,
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                   //notification
                // $mr = auth()->user();
                // $manager  = $mr->managers()->pluck('users.id');
                // $manager  = $manager->first();
                // $manager  = User::find($manager);
                // $manager->notify(new TADACreateNotification($is_update_tada));
                $updatedData = TADARecords::find($id);
                $success['status'] = 200;
                $success['message'] = "Tada updated successfully.";
                $success['data'] = $updatedData;
                return response()->json($success, 200);
            } else {
                $error['status'] = 400;
                $error['message'] = "Oops! Something went wrong.";
                $error['data'] = null;
                return response()->json($error, 400);
            }
        }
    }

   //Function for delete tada
    public function delete_tada(Request $request) {
        //Get id
        $id = $request->id;
        //Check if record exists
        $tada = TADARecords::find($id);
        //Check if tada record fond or not
        if (!$tada) {
            $error['status'] = 404;
            $error['message'] = "This record not found.";
            $error['data'] = null;
            return response()->json($error, 404);
        }
        //Delete tada record
        $is_delete_tada = $tada->delete();
        //Check if tada deleted or not
        if ($is_delete_tada) {
            $success['status'] = 200;
            $success['message'] = "Tada deleted successfully.";
            $success['data'] = null;
            return response()->json($success, 200);
        } else {
            $error['status'] = 400;
            $error['message'] = "Oops! Something went wrong.";
            $error['data'] = null;
            return response()->json($error, 400);
        }
    }
}
