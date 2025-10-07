<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    //Function for show all clients
    public function index() {
        $all_clients = Client::OrderBy('ID', 'DESC')->paginate(5);
        return view('mr.clients.all-clients', compact('all_clients'));
    }

    //Function for show clients
    public function create() {
        return view('mr.clients.add-client');
    }

    //Function for submit client
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'client_name' => 'required|string',
            'client_contact' => 'required|string',
            'category_type' => 'required|string',
        ]);

       //Get details
       $details = [];
        //Check if category type doctor
        if ($request->category_type == 'doctor') {
            $details = [
                'doctor_name' => $request->doctor_name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist' => $request->specialist,
                'age' => $request->age,
                'experience' => $request->experience,
            ];
        //Check if category type nurse
        } elseif ($request->category_type == 'nurse') {
            $details = [
                'nurse_name' => $request->nurse_name,
                'hospital_name' => $request->n_hospital_name,
                'department' => $request->department,
                'age' => $request->n_age,
                'experience' => $request->n_experience,
            ];
        //Check if category type lab technician
        } elseif ($request->category_type == 'lab_technician') {
            $details = [
                'lab_name' => $request->lab_name,
                'lab_contact' => $request->lab_contact,
            ];
        //Check if category type chemist
        } elseif ($request->category_type == 'chemist') {
            $details = [
                'chemist_name' => $request->chemist_name,
                'address' => $request->address,
            ];
        //Check if category type asha worker
        } elseif ($request->category_type == 'asha_worker') {
            $details = [
                'asha_name' => $request->asha_name,
                'asha_area' => $request->asha_area,
            ];
        //Check if category type sarpanch
        } elseif ($request->category_type == 'sarpanch') {
            $details = [
                'sarpanch_name' => $request->sarpanch_name,
                'contact' => $request->contact,
            ];
        //Check if category type mc
        } elseif ($request->category_type == 'mc') {
            $details = [
                'mc_ward' => $request->mc_ward,
                'mc_person' => $request->mc_person,
            ];
        //Check if category type franchisee
        } elseif ($request->category_type == 'franchisee') {
            $details = [
                'franchisee_name' => $request->franchisee_name,
            ];
        //Check if category type healthcare worker
        } elseif ($request->category_type == 'healthcare_worker') {
            $details = [
                'health_worker_name' => $request->health_worker_name,
            ];
        //Check if category type others
        } elseif ($request->category_type == 'others') {
            $details = [
                'others_details' => $request->others,
            ];
        }
        //Create client
        $is_create_client = Client::create([
            'name' => $request->client_name,
            'contact' => $request->client_contact,
            'category_type' => $request->category_type,
            'details' => json_encode($details),
            'status' => 'Pending',
        ]);
        //Check if client craeted or not
        if($is_create_client) {
            return back()->with('success', 'Client created successfuly.');
        } else {
            return back()->with('success', 'Opps something went wrong!');
        }
    }

    //Function for edit client
    public function edit($id) {
        //Get client detail
        $client_detail = Client::findOrFail($id);
        $client_detail->details = json_decode($client_detail->details);
        return view('mr.clients.edit-client', compact('client_detail'));
    }

    //Function for client update
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'client_name' => 'required|string',
            'client_contact' => 'required|string',
            'category_type' => 'required|string',
        ]);
        //Get details
        $details = [];
        //Check if category type doctor
        if ($request->category_type == 'doctor') {
            $details = [
                'doctor_name' => $request->doctor_name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist' => $request->specialist,
                'age' => $request->age,
                'experience' => $request->experience,
            ];
        //Check if category type nurse
        } elseif ($request->category_type == 'nurse') {
            $details = [
                'nurse_name' => $request->nurse_name,
                'hospital_name' => $request->n_hospital_name,
                'department' => $request->department,
                'age' => $request->n_age,
                'experience' => $request->n_experience,
            ];
        //Check if category type lab technician
        } elseif ($request->category_type == 'lab_technician') {
            $details = [
                'lab_name' => $request->lab_name,
                'lab_contact' => $request->lab_contact,
            ];
        //Check if category type chemist
        } elseif ($request->category_type == 'chemist') {
            $details = [
                'chemist_name' => $request->chemist_name,
                'address' => $request->address,
            ];
        //Check if category type asha worker
        } elseif ($request->category_type == 'asha_worker') {
            $details = [
                'asha_name' => $request->asha_name,
                'asha_area' => $request->asha_area,
            ];
        //Check if category type sarpanch
        } elseif ($request->category_type == 'sarpanch') {
            $details = [
                'sarpanch_name' => $request->sarpanch_name,
                'contact' => $request->contact,
            ];
        //Check if category type mc
        } elseif ($request->category_type == 'mc') {
            $details = [
                'mc_ward' => $request->mc_ward,
                'mc_person' => $request->mc_person,
            ];
        //Check if category type franchisee
        } elseif ($request->category_type == 'franchisee') {
            $details = [
                'franchisee_name' => $request->franchisee_name,
            ];
        //Check if category type healthcare worker
        } elseif ($request->category_type == 'healthcare_worker') {
            $details = [
                'health_worker_name' => $request->health_worker_name,
            ];
        //Check if category type others
        } elseif ($request->category_type == 'others') {
            $details = [
                'others_details' => $request->others,
            ];
        }
        //Update client
        $is_update_client = Client::where('id', $id)->update([
            'name' => $request->client_name,
            'contact' => $request->client_contact,
            'category_type' => $request->category_type,
            'details' => json_encode($details),
        ]);
        //Check if client updated or not
        if($is_update_client) {
            return redirect()->route('mr.clients.index')->with('success', 'Client updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for delete client
    public function destroy($id) {
        //Delete client
        $is_delete_client = Client::where('id', $id)->delete();
        //Check if client deleted or not
        if($is_delete_client) {
            return redirect()->route('mr.clients.index')->with('success', 'Client deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
}
