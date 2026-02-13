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
        //Categories
        $client_categories = ClientCategory::where('status', 'active')->with('fields')->get();
        //Query
        $query = Client::OrderBy('ID', 'DESC')->where('mr_id', auth()->id());
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

        return view('mr.clients.all-clients', compact('all_clients','client_categories'));
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
