<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Client;
use App\Models\MangerMR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse {
        //Check if auth login or not
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
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
        //Get auth login detail
        $mr_id = Auth::id();
        //Get clients
        $clients = Client::where('mr_id', $mr_id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //Response
        return response()->json([
            'status' => true,
            'message' => 'Clients fetched successfully.',
            'data' => $clients
        ], 200);
    }

    //Function for create client
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'category_type' => 'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //details
        $details = $this->prepareDetails($request);
        //Get manager
        $manager_id = MangerMR::where('mr_id', Auth::id())->value('manager_id');
        //create client
        $client = Client::create([
            'mr_id' => Auth::id(),
            'manager_id' => $manager_id,
            'category_type' => $request->category_type,
            'details' => json_encode($details),
            'status' => 'Pending',
        ]);
        //respnse
        return response()->json([
            'status' => true,
            'message' => 'Client created successfully.',
            'data' => $client
        ], 201);
    }

    //Function for update client
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get client detail
        $client = Client::find($id);
        //Check if client not fond
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'category_type' => 'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //details
        $details = $this->prepareDetails($request);
        //update client
        $client->update([
            'category_type' => $request->category_type,
            'details' => json_encode($details),
        ]);
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
        //Get client detail
        $client = Client::find($id);
        //Check if client not found
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found.'
            ], 404);
        }
        //delete client
        $client->delete();
        //response
        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully.'
        ], 200);
    }

    //PRIVATE FUNCTION TO SET DETAILS
    private function prepareDetails($request)
    {
        return match ($request->category_type) {
            'doctor' => [
                'doctor_name' => $request->doctor_name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist' => $request->specialist,
                'contact' => $request->hospital_contact,
                'address' => $request->hospital_address,
                'particulars' => $request->hospital_particulars,
                'remarks' => $request->hospital_remarks,
            ],
            'nurse' => [
                'nurse_name' => $request->nurse_name,
                'contact' => $request->nurse_contact,
                'address' => $request->nurse_address,
                'particulars' => $request->nurse_particulars,
                'remarks' => $request->nurse_remarks,
            ],
            'lab_technician' => [
                'lab_name' => $request->lab_name,
                'contact' => $request->lab_contact,
                'address' => $request->lab_address,
                'particulars' => $request->lab_particulars,
                'remarks' => $request->lab_remarks,
            ],
            'chemist' => [
                'chemist_name' => $request->chemist_name,
                'contact' => $request->chemist_contact,
                'address' => $request->chemist_address,
                'particulars' => $request->chemist_particulars,
                'remarks' => $request->chemist_remarks,
            ],
            'asha_worker' => [
                'asha_name' => $request->asha_name,
                'contact' => $request->asha_contact,
                'address' => $request->asha_address,
                'particulars' => $request->asha_particulars,
                'remarks' => $request->asha_remarks,
            ],
            'sarpanch' => [
                'sarpanch_name' => $request->sarpanch_name,
                'contact' => $request->sarpanch_contact,
                'address' => $request->sarpanch_address,
                'particulars' => $request->sarpanch_particulars,
                'remarks' => $request->sarpanch_remarks,
            ],
            'mc' => [
                'mc_ward' => $request->mc_ward,
                'contact' => $request->mc_contact,
                'address' => $request->mc_address,
                'particulars' => $request->mc_particulars,
                'remarks' => $request->mc_remarks,
            ],
            'franchisee' => [
                'franchisee_name' => $request->franchisee_name,
                'contact' => $request->franchisee_contact,
                'address' => $request->franchisee_address,
                'particulars' => $request->franchisee_particulars,
                'remarks' => $request->franchisee_remarks,
            ],
            'healthcare_worker' => [
                'health_worker_name' => $request->health_worker_name,
                'contact' => $request->health_contact,
                'address' => $request->health_address,
                'particulars' => $request->health_particulars,
                'remarks' => $request->health_remarks,
            ],
            'others' => [
                'name' => $request->others_name,
                'contact' => $request->others_contact,
                'address' => $request->others_address,
                'particulars' => $request->others_particulars,
                'remarks' => $request->others_remarks,
            ],
            default => [],
        };
    }
}
