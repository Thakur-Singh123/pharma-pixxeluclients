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
            'name' => 'required|string|max:255|unique:client_categories,name',
            'status' => 'required|in:active,inactive',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.name' => [
                'required',
                'regex:/^[a-z_]+$/'
            ],
            'fields.*.type' => 'required|in:input,textarea,dropdown',
            'fields.*.validation_type' => 'nullable|in:name,contact,address,none',
            'fields.*.input_type' => 'nullable|in:text,number,url',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*' => 'nullable|string|max:255',
        ], [
            'name.unique' => 'This category name already exists. Please choose a different name.',
            'fields.*.name.regex' =>
                'Field name must contain only lowercase letters and underscore (example: doctor_name)'
        ]);

        // Dropdown type must have at least one option
        foreach ($request->fields as $index => $field) {
            if (($field['type'] ?? '') === 'dropdown') {
                $opts = $field['options'] ?? [];
                $opts = is_array($opts) ? array_filter(array_map('trim', $opts)) : [];
                if (empty($opts)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['fields.' . $index . '.options' => 'Dropdown must have at least one option.']);
                }
            }
        }

        //Create client category
        $category = ClientCategory::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Save Fields
        foreach ($request->fields as $field) {
            $options = null;
            if (($field['type'] ?? '') === 'dropdown' && !empty($field['options'])) {
                $options = array_values(array_filter(array_map('trim', $field['options'])));
            }
            $category->fields()->create([
                'label' => $field['label'],
                'name'  => $field['name'],
                'type'  => $field['type'],
                'input_type' => ($field['type'] ?? '') === 'textarea' ? 'text' : ($field['input_type'] ?? 'text'),
                'validation_type' => $field['validation_type'] ?? 'none',
                'options' => $options,
            ]);
        }

        return redirect()->route('manager.client.category.list')->with('success', 'Client category added successfully.');
    }

    //Function for show client category list
    public function client_category_list(Request $request) {
        //Query
        $query = ClientCategory::OrderBy('ID', 'DESC');
        //Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        //Categories
        $categories = $query->paginate(10);

        return view('manager.client-category.index', compact('categories'));
    }

    //Function for delete client category
    public function delete_client_category($id) {
        $category = ClientCategory::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Client category deleted successfully.');
    }
}
