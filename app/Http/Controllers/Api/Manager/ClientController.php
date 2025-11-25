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
        $details = $this->prepareDetails($request);
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
    private function prepareDetails($request)
    {
        // COMMON FIELDS (same for all categories)
        $common = [
            'contact'     => $request->contact,
            'address'     => $request->address,
            'particulars' => $request->particulars,
            'remarks'     => $request->remarks,
        ];

        return match ($request->category_type) {

            'doctor'            => [
                'doctor_name'   => $request->name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist'    => $request->specialist,
                'contact'       => $request->contact,
                'address'       => $request->address,
                'particulars'   => $request->particulars,
                'remarks'       => $request->remarks,
            ],

            'nurse'             => array_merge($common, [
                'nurse_name' => $request->name,
            ]),

            'lab_technician'    => array_merge($common, [
                'lab_name' => $request->name,
            ]),

            'chemist'           => array_merge($common, [
                'chemist_name' => $request->name,
            ]),

            'asha_worker'       => array_merge($common, [
                'asha_name' => $request->name,
            ]),

            'sarpanch'          => array_merge($common, [
                'sarpanch_name' => $request->name,
            ]),

            'mc'                => array_merge($common, [
                'mc_ward' => $request->name,
            ]),

            'franchisee'        => array_merge($common, [
                'franchisee_name' => $request->name,
            ]),

            'healthcare_worker' => array_merge($common, [
                'health_worker_name' => $request->name,
            ]),

            'others'            => array_merge($common, [
                'name' => $request->name,
            ]),

            default             => [],

        };
    }
}
