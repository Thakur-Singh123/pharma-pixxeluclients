<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientCategory;

class ClientCategoryController extends Controller
{
    
    //Function for show create client form
    public function create_client_category() {
        return view('manager.client-category.create');
    }

    //Function for store client category
    public function store_client_category(Request $request) {
        //Validate input fields
       $request->validate([
            'name' => 'required|string|max:255,unique:client_categories,name',
            'status' => 'required|in:active,inactive',

            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.name' => [
                'required',
                'regex:/^[a-z_]+$/'
            ],
            'fields.*.type' => 'required|in:input,textarea',
            'fields.*.validation_type' => 'required|in:name,contact,address,none',
            'fields.*.input_type'=> 'required|in:text,number,url',
        ], [
            'fields.*.name.regex' =>
                'Field name must contain only lowercase letters and underscore (example: doctor_name)'
        ]);

        //Create client category
        $category = ClientCategory::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Save Fields
        foreach ($request->fields as $field) {
            $category->fields()->create([
                'label' => $field['label'],
                'name'  => $field['name'],
                'type'  => $field['type'],
                'input_type' => $field['input_type'] ?? null,
            ]);
        }
        return redirect()->back()->with('success', 'Client category added successfully.');
    }

    //function for show client category list
    public function client_category_list() {
        $categories = ClientCategory::OrderBy('ID', 'DESC')->paginate(10);
        return view('manager.client-category.index', compact('categories'));
    }

    //function for delete client category
    public function delete_client_category($id) {
        $category = ClientCategory::findOrFail($id);
        $category->delete();
        if($category->fields()->count() > 0) {
            $category->fields()->delete();
        }
        return redirect()->back()->with('success', 'Client category deleted successfully.');        
    }
}
