<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\MangerMR;
use App\Models\ClientCategory;


class ClientController extends Controller
{
    //Function for show all clients
    public function index(Request $request) {
        //Query
        $query = Client::OrderBy('ID', 'DESC')->where('mr_id', auth()->id());
        //Category filter
        if ($request->filled('category_type')) {
            $query->where('category_type', $request->category_type);
        }
        //Date filter
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }
        //Get clients
        $all_clients = $query->paginate(5);

        return view('mr.clients.all-clients', compact('all_clients'));
    }

    //Function for show clients
    public function create() {
        $client_categories = ClientCategory::where('status', 'active')->with('fields')->get();
        return view('mr.clients.add-client', compact('client_categories'));
    }

    //Function for submit client
    public function store(Request $request)
    {
        $request->validate([
            'category_type' => 'required|string',
        ]);
<<<<<<< HEAD

        // Get selected category
        $category = ClientCategory::where('name', $request->category_type)
                    ->with('fields')
                    ->first();
        $details = [];

        if ($category) {
            foreach ($category->fields as $field) {
                $fieldName = $field->name;
                // Only save filled values
                if ($request->filled($fieldName)) {
                    $details[$fieldName] = $request->input($fieldName);
                }
            }
=======
       //Get details
       $details = [];
        //Check if category type doctor
        if ($request->category_type == 'doctor') {
            $details = [
                'doctor_name' => $request->doctor_name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist' => $request->specialist, 
                'contact' => $request->hospital_contact,
                'address' => $request->hospital_address,
                'particulars' => $request->hospital_particulars,
                'remarks' => $request->hospital_remarks,
            ];
        //Check if category type nurse
        } elseif ($request->category_type == 'nurse') {
            $details = [
                'nurse_name' => $request->nurse_name,
                'contact' => $request->nurse_contact,
                'address' => $request->nurse_address,
                'particulars' => $request->nurse_particulars,
                'remarks' => $request->nurse_remarks,
            ];
        //Check if category type lab technician
        } elseif ($request->category_type == 'lab_technician') {
            $details = [
                'lab_name' => $request->lab_name,
                'contact' => $request->lab_contact,
                'address' => $request->lab_address,
                'particulars' => $request->lab_particulars,
                'remarks' => $request->lab_remarks,
            ];
        //Check if category type chemist
        } elseif ($request->category_type == 'chemist') {
            $details = [
                'chemist_name' => $request->chemist_name,
                'contact' => $request->chemist_contact,
                'address' => $request->chemist_address,
                'particulars' => $request->chemist_particulars,
                'remarks' => $request->chemist_remarks,
            ];
        //Check if category type asha worker
        } elseif ($request->category_type == 'asha_worker') {
            $details = [
                'asha_name' => $request->asha_name,
                'contact' => $request->asha_contact,
                'address' => $request->asha_address,
                'particulars' => $request->asha_particulars,
                'remarks' => $request->asha_remarks,
            ];
        //Check if category type sarpanch
        } elseif ($request->category_type == 'sarpanch') {
            $details = [
                'sarpanch_name' => $request->sarpanch_name,
                'contact' => $request->sarpanch_contact,
                'address' => $request->sarpanch_address,
                'particulars' => $request->sarpanch_particulars,
                'remarks' => $request->sarpanch_remarks,
            ];
        //Check if category type mc
        } elseif ($request->category_type == 'mc') {
            $details = [
                'mc_ward' => $request->mc_ward,
                'contact' => $request->mc_contact,
                'address' => $request->mc_address,
                'particulars' => $request->mc_particulars,
                'remarks' => $request->mc_remarks,
            ];
        //Check if category type franchisee
        } elseif ($request->category_type == 'franchisee') {
            $details = [
                'franchisee_name' => $request->franchisee_name,
                'contact' => $request->franchisee_contact,
                'address' => $request->franchisee_address,
                'particulars' => $request->franchisee_particulars,
                'remarks' => $request->franchisee_remarks,
            ];
        //Check if category type healthcare worker
        } elseif ($request->category_type == 'healthcare_worker') {
            $details = [
                'health_worker_name' => $request->health_worker_name,
                'contact' => $request->health_contact,
                'address' => $request->health_address,
                'particulars' => $request->health_particulars,
                'remarks' => $request->health_remarks,
            ];
        //Check if category type others
        } elseif ($request->category_type == 'others') {
            $details = [
                'name' => $request->others_name,
                'contact' => $request->others_contact,
                'address' => $request->others_address,
                'particulars' => $request->others_particulars,
                'remarks' => $request->others_remarks,
            ];
        //Check if category type school
        } elseif ($request->category_type == 'school') {
            $details = [
                'school_name' => $request->school_name,
                'contact' => $request->school_contact,
                'address' => $request->school_address,
                'particulars' => $request->school_particulars,
                'remarks' => $request->school_remarks,
            ];
        //Check if category type press reporter
        } elseif ($request->category_type == 'press_reporter') {
            $details = [
                'reporter_name' => $request->reporter_name,
                'contact' => $request->reporter_contact,
                'address' => $request->reporter_address,
                'particulars' => $request->reporter_particulars,
                'remarks' => $request->reporter_remarks,
            ];
        //Check if category type market president
        } elseif ($request->category_type == 'market_president') {
            $details = [
                'president_name' => $request->president_name,
                'contact' => $request->president_contact,
                'address' => $request->president_address,
                'particulars' => $request->president_particulars,
                'remarks' => $request->president_remarks,
            ];
>>>>>>> cdf493cfb721166bb1b48d273116d06f942ebc14
        }
        $manager_id = MangerMR::where('mr_id', auth()->id())
                        ->value('manager_id');
        $client = Client::create([
            'mr_id' => auth()->id(),
            'manager_id' => $manager_id,
            'category_type' => $request->category_type,
            'details' => json_encode($details),
            'status' => 'Pending',
        ]);

        return redirect()->route('mr.clients.index')
            ->with('success', 'Client created successfully.');
    }


    //Function for edit client
    public function edit($id)
    {
        // Client detail
        $client = Client::findOrFail($id);

        // Decode JSON (agar cast nahi lagaya hai to)
        $client->details = json_decode($client->details, true);

        // All active categories with fields
        $client_categories = ClientCategory::where('status', 'active')
                                ->with('fields')
                                ->get();

        return view('mr.clients.edit-client', compact(
            'client',
            'client_categories'
        ));
    }


    //Function for client update
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        // Validate category
        $request->validate([
            'category_type' => 'required|string'
        ]);

<<<<<<< HEAD
        // Get selected category with fields
        $category = ClientCategory::where('name', $request->category_type)
                        ->with('fields')
                        ->first();

        $details = [];

        if ($category) {

            foreach ($category->fields as $field) {

                $fieldName = $field->name;

                // Only save filled values
                if ($request->filled($fieldName)) {
                    $details[$fieldName] = $request->input($fieldName);
                }
            }
=======
        //Get details
       $details = [];
        //Check if category type doctor
        if ($request->category_type == 'doctor') {
            $details = [
                'doctor_name' => $request->doctor_name,
                'hospital_name' => $request->hospital_name,
                'hospital_type' => $request->hospital_type,
                'specialist' => $request->specialist, 
                'contact' => $request->hospital_contact,
                'address' => $request->hospital_address,
                'particulars' => $request->hospital_particulars,
                'remarks' => $request->hospital_remarks,
            ];
        //Check if category type nurse
        } elseif ($request->category_type == 'nurse') {
            $details = [
                'nurse_name' => $request->nurse_name,
                'contact' => $request->nurse_contact,
                'address' => $request->nurse_address,
                'particulars' => $request->nurse_particulars,
                'remarks' => $request->nurse_remarks,
            ];
        //Check if category type lab technician
        } elseif ($request->category_type == 'lab_technician') {
            $details = [
                'lab_name' => $request->lab_name,
                'contact' => $request->lab_contact,
                'address' => $request->lab_address,
                'particulars' => $request->lab_particulars,
                'remarks' => $request->lab_remarks,
            ];
        //Check if category type chemist
        } elseif ($request->category_type == 'chemist') {
            $details = [
                'chemist_name' => $request->chemist_name,
                'contact' => $request->chemist_contact,
                'address' => $request->chemist_address,
                'particulars' => $request->chemist_particulars,
                'remarks' => $request->chemist_remarks,
            ];
        //Check if category type asha worker
        } elseif ($request->category_type == 'asha_worker') {
            $details = [
                'asha_name' => $request->asha_name,
                'contact' => $request->asha_contact,
                'address' => $request->asha_address,
                'particulars' => $request->asha_particulars,
                'remarks' => $request->asha_remarks,
            ];
        //Check if category type sarpanch
        } elseif ($request->category_type == 'sarpanch') {
            $details = [
                'sarpanch_name' => $request->sarpanch_name,
                'contact' => $request->sarpanch_contact,
                'address' => $request->sarpanch_address,
                'particulars' => $request->sarpanch_particulars,
                'remarks' => $request->sarpanch_remarks,
            ];
        //Check if category type mc
        } elseif ($request->category_type == 'mc') {
            $details = [
                'mc_ward' => $request->mc_ward,
                'contact' => $request->mc_contact,
                'address' => $request->mc_address,
                'particulars' => $request->mc_particulars,
                'remarks' => $request->mc_remarks,
            ];
        //Check if category type franchisee
        } elseif ($request->category_type == 'franchisee') {
            $details = [
                'franchisee_name' => $request->franchisee_name,
                'contact' => $request->franchisee_contact,
                'address' => $request->franchisee_address,
                'particulars' => $request->franchisee_particulars,
                'remarks' => $request->franchisee_remarks,
            ];
        //Check if category type healthcare worker
        } elseif ($request->category_type == 'healthcare_worker') {
            $details = [
                'health_worker_name' => $request->health_worker_name,
                'contact' => $request->health_contact,
                'address' => $request->health_address,
                'particulars' => $request->health_particulars,
                'remarks' => $request->health_remarks,
            ];
        //Check if category type others
        } elseif ($request->category_type == 'others') {
            $details = [
                'name' => $request->others_name,
                'contact' => $request->others_contact,
                'address' => $request->others_address,
                'particulars' => $request->others_particulars,
                'remarks' => $request->others_remarks,
            ];
        //Check if category type school
        } elseif ($request->category_type == 'school') {
            $details = [
                'school_name' => $request->school_name,
                'contact' => $request->school_contact,
                'address' => $request->school_address,
                'particulars' => $request->school_particulars,
                'remarks' => $request->school_remarks,
            ];
        //Check if category type press reporter
        } elseif ($request->category_type == 'press_reporter') {
            $details = [
                'reporter_name' => $request->reporter_name,
                'contact' => $request->reporter_contact,
                'address' => $request->reporter_address,
                'particulars' => $request->reporter_particulars,
                'remarks' => $request->reporter_remarks,
            ];
        //Check if category type market president
        } elseif ($request->category_type == 'market_president') {
            $details = [
                'president_name' => $request->president_name,
                'contact' => $request->president_contact,
                'address' => $request->president_address,
                'particulars' => $request->president_particulars,
                'remarks' => $request->president_remarks,
            ];
>>>>>>> cdf493cfb721166bb1b48d273116d06f942ebc14
        }

        //Update client
        $client->update([
            'category_type' => $request->category_type,
            'details'       => json_encode($details), // remove json_encode if using cast
        ]);

        return redirect()
                ->route('mr.clients.index')
                ->with('success', 'Client updated successfully.');
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
