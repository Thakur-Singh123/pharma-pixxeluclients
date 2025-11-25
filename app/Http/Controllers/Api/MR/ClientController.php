<?php
namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\MangerMR;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse
    {
        //Check if auth login or not
        if (! Auth::check()) {
            return response()->json([
                'status'  => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data'    => null,
            ], 401);
        }

        return null;
    }

    //Function for all clients
    public function index()
    {
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
            'status'  => true,
            'message' => 'Clients fetched successfully.',
            'data'    => $clients,
        ], 200);
    }

    //Function for create client
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'category_type' => 'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status']  = 400;
            $error['message'] = $validator->errors()->first();
            $error['data']    = null;
            return response()->json($error, 400);
        }
        //details
        $details = $this->prepareDetails($request);
        //Get manager
        $manager_id = MangerMR::where('mr_id', Auth::id())->value('manager_id');
        //create client
        $client = Client::create([
            'mr_id'         => Auth::id(),
            'manager_id'    => $manager_id,
            'category_type' => $request->category_type,
            'details'       => json_encode($details),
            'status'        => 'Pending',
        ]);
        //respnse
        return response()->json([
            'status'  => true,
            'message' => 'Client created successfully.',
            'data'    => $client,
        ], 200);
    }

    //Function for update client
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get client detail
        $client = Client::find($id);
        //Check if client not fond
        if (! $client) {
            return response()->json([
                'status'  => false,
                'message' => 'Client not found.',
            ], 404);
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'category_type' => 'required|string',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status']  = 400;
            $error['message'] = $validator->errors()->first();
            $error['data']    = null;
            return response()->json($error, 400);
        }
        //details
        $details = $this->prepareDetails($request);
        //update client
        $client->update([
            'category_type' => $request->category_type,
            'details'       => json_encode($details),
        ]);
        //response
        return response()->json([
            'status'  => true,
            'message' => 'Client updated successfully.',
            'data'    => $client,
        ], 200);
    }

    //Function for delete client
    public function destroy($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get client detail
        $client = Client::find($id);
        //Check if client not found
        if (! $client) {
            return response()->json([
                'status'  => false,
                'message' => 'Client not found.',
            ], 404);
        }
        //delete client
        $client->delete();
        //response
        return response()->json([
            'status'  => true,
            'message' => 'Client deleted successfully.',
        ], 200);
    }

    //PRIVATE FUNCTION TO SET DETAILS
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
