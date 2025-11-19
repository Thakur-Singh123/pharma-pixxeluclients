<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
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

    //Function for all clients
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all clients
        $clients = Client::orderBy('id', 'DESC')
            ->where('manager_id', auth()->id())
            ->paginate(10);
        //response
        return response()->json([
            'status' => true,
            'message' => 'Clients fetched successfully.',
            'data' => $clients
        ], 200);
    }

    //Function for approve client
    public function approve($id) {
        $client = Client::find($id);
        //if check client found or not
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
        }
        //check if status approved or not
        if ($client->status == 'Approved') {
            return response()->json([
                'status' => false,
                'message' => 'This client is already approved. Reject it first to approve again.',
                'data' => 'null'
            ], 400);
        }
        //save
        $client->status = 'Approved';
        $client->approved_by = 1;
        $client->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Client approved successfully.',
        ], 200);
    }

    //Function for reject client
    public function reject($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get client detail
        $client = Client::find($id);
        //check if client found or not
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
        }
        //check if status reject or not
        if ($client->status == 'Reject') {
            return response()->json([
                'status' => false,
                'message' => 'This client is already rejected. Approve it first to reject again.',
                'data' => 'null'
            ], 400);
        }
        //save
        $client->status = 'Reject';
        $client->approved_by = 0;
        $client->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Client rejected successfully.',
        ], 200);
    }

    //Function for update client
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //validate inputs fields
        $validator = Validator::make($request->all(), [
            'category_type' => 'required|string'
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //ATEGORY DETAILS CREATION
        $details = $this->prepareCategoryDetails($request);
        //get client detail
        $client = Client::find($id);
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
        }
        //category type
        $client->category_type = $request->category_type;
        $client->details = json_encode($details);
        $client->save();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Client updated successfully.',
            'data' => $client
        ], 200);
    }

    //Function for delete client
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get client detail
        $client = Client::find($id);
        //if check client found or not
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }
        //delete client
        $client->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully'
        ]);
    }

    //Function for category details
    private function prepareCategoryDetails($request) {
        //get category
        switch ($request->category_type) {
            case 'doctor':
                return [
                    'doctor_name' => $request->doctor_name,
                    'hospital_name' => $request->hospital_name,
                    'hospital_type' => $request->hospital_type,
                    'specialist' => $request->specialist,
                    'contact' => $request->hospital_contact,
                    'address' => $request->hospital_address,
                    'particulars' => $request->hospital_particulars,
                    'remarks' => $request->hospital_remarks,
                ];
            case 'nurse':
                return [
                    'nurse_name' => $request->nurse_name,
                    'contact' => $request->nurse_contact,
                    'address' => $request->nurse_address,
                    'particulars' => $request->nurse_particulars,
                    'remarks' => $request->nurse_remarks,
                ];
            case 'lab_technician':
                return [
                    'lab_name' => $request->lab_name,
                    'contact' => $request->lab_contact,
                    'address' => $request->lab_address,
                    'particulars' => $request->lab_particulars,
                    'remarks' => $request->lab_remarks,
                ];
            case 'chemist':
                return [
                    'chemist_name' => $request->chemist_name,
                    'contact' => $request->chemist_contact,
                    'address' => $request->chemist_address,
                    'particulars' => $request->chemist_particulars,
                    'remarks' => $request->chemist_remarks,
                ];
            case 'asha_worker':
                return [
                    'asha_name' => $request->asha_name,
                    'contact' => $request->asha_contact,
                    'address' => $request->asha_address,
                    'particulars' => $request->asha_particulars,
                    'remarks' => $request->asha_remarks,
                ];
            case 'sarpanch':
                return [
                    'sarpanch_name' => $request->sarpanch_name,
                    'contact' => $request->sarpanch_contact,
                    'address' => $request->sarpanch_address,
                    'particulars' => $request->sarpanch_particulars,
                    'remarks' => $request->sarpanch_remarks,
                ];
            case 'mc':
                return [
                    'mc_ward' => $request->mc_ward,
                    'contact' => $request->mc_contact,
                    'address' => $request->mc_address,
                    'particulars' => $request->mc_particulars,
                    'remarks' => $request->mc_remarks,
                ];
            case 'franchisee':
                return [
                    'franchisee_name' => $request->franchisee_name,
                    'contact' => $request->franchisee_contact,
                    'address' => $request->franchisee_address,
                    'particulars' => $request->franchisee_particulars,
                    'remarks' => $request->franchisee_remarks,
                ];
            case 'healthcare_worker':
                return [
                    'health_worker_name' => $request->health_worker_name,
                    'contact' => $request->health_contact,
                    'address' => $request->health_address,
                    'particulars' => $request->health_particulars,
                    'remarks' => $request->health_remarks,
                ];
            case 'others':
                return [
                    'name' => $request->others_name,
                    'contact' => $request->others_contact,
                    'address' => $request->others_address,
                    'particulars' => $request->others_particulars,
                    'remarks' => $request->others_remarks,
                ];
            default:
                return [];
        }
    }
}
