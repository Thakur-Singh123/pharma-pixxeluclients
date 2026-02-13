<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientCategory;

class ClientController extends Controller
{
    //Function for show all clients
    public function index(Request $request) {
        //Categories
        $client_categories = ClientCategory::where('status', 'active')->with('fields')->get();
        //Query
        $query  = Client::OrderBy('ID','DESC')->where('manager_id', auth()->id());
        //Category filter
        if ($request->category_type) {
            $query->where('category_type', $request->category_type);
        }
        //Date filter
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }
        //Get clients
        $all_clients = $query->paginate(5);

        return view('manager.clients.all-clients', compact('all_clients','client_categories'));
    }

    //Function for approval client
    public function client_approve($id) {
        //Get client detail
        $client_detail = Client::findOrFail($id);
        //Update status
        $client_detail->status = 'Approved';
        $client_detail->approved_by = '1';
        $client_detail->save();

        return redirect()->back()->with('success', 'Client approved successfully.');
    }

    //Function for reject client
    public function client_reject($id) {
        //Get client detail
        $client_detail = Client::findOrFail($id);
        //Update status
        $client_detail->status = 'Reject';
        $client_detail->approved_by = '0';
        $client_detail->save();
        
        return redirect()->back()->with('success', 'Client reject successfully.');
    }

    //Function for edit client
    public function edit($id) {
        //Get client detail
        $client_detail = Client::findOrFail($id);
        $client_detail->details = json_decode($client_detail->details);
        return view('manager.clients.edit-client', compact('client_detail'));
    }

    //Function for client update
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
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
        }
        //Update client
        $is_update_client = Client::where('id', $id)->update([
            'category_type' => $request->category_type,
            'details' => json_encode($details),
        ]);
        //Check if client updated or not
        if($is_update_client) {
            return redirect()->route('manager.clients.index')->with('success', 'Client updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for delete client
    public function destroy($id) {
        //delete client
        $is_delete_client = Client::where('id', $id)->delete();
        //Check if client deleted or not
        if ($is_delete_client) {
            return redirect()->route('manager.clients.index')->with('success', 'Client deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for show create client form
    public function create_client_category() {
        return view('manager.add-client-category.create');
    }
}
