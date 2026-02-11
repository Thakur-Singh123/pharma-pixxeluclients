<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Auth;

class SalesController extends Controller
{
    //Function for show all sales
    public function index(Request $request) {
        //Get sales
        $query = Sale::orderBy('created_at', 'desc')->where('user_id', Auth::id());
        //Filter by date
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }
        $sales = $query->with('items')->paginate(5);
        return view('mr.sales.index', compact('sales'));
    }

    //Function for view sales form
    public function create() {
        return view('mr.sales.create');
    }

    //Function for store sale
    public function store(Request $request) {
        //Db function
        DB::transaction(function () use ($request) {
            //Handle file upload
            $currentFile = null;
            if ($request->hasFile('prescription_file')) {
                $prescription = $request->file('prescription_file');
                $prescription->move(public_path('prescriptions'), $prescription->getClientOriginalName());
                $currentFile = $prescription->getClientOriginalName();
            }
            //Create sale
            $sale = Sale::create([
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'phone' => $request->phone,
                'address' => $request->address,
                'doctor_name' => $request->doctor_name,
                'prescription_file' => $currentFile,
                'total_amount' => $request->total_amount,
                'discount' => '0',
                'net_amount' => '0',
                'payment_mode' => $request->payment_mode,
                'user_id' => auth()->id(),
                'status' => 'Pending',
            ]);
            //Create sale items
            foreach ($request->salt_name as $index => $salt_name) {
                $sale->items()->create([
                    'medicine_name' => 'Thakur',
                    'salt_name' => $salt_name,
                    'brand_name' => $request->brand_name[$index],
                    'company' => $request->company[$index],
                    'type' => '0',
                    'base_price' => $request->base_price[$index],
                    'sale_price' => $request->sale_price[$index],
                    'margin' => $request->margin[$index],
                    'total_amount' => $request->total_amount,
                    'payment_mode' => $request->payment_mode,
                    'discount' => '0',
                    'net_amount' => $request->total_amount,
                    'quantity' => '0',
                    'line_total' => '0',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Sale created successfully.');
    }

    //Function for edit sale
    public function edit($id) {
        //Get sale detail
        $sale = Sale::with('items')->findOrFail($id);
        return view('mr.sales.edit', compact('sale'));
    }

    //Function for update sale
    public function update(Request $request, $id) {
        // $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|email',
        //     'designation' => 'required|string',
        //     'phone' => 'required|string',
        //     'address' => 'required|string',
        //     'doctor_name' => 'required|string',
        //     'prescription_file' => 'nullable',

        //     'medicine_name' => 'required|array',
        //     'medicine_name.*' => 'required|string',
        //     'base_price.*' => 'required|numeric',
        //     'sale_price.*' => 'required|numeric',
        //     'quantity.*' => 'required|integer',
        //     'line_total.*' => 'required|numeric',

        //     'total_amount' => 'required|numeric',
        //     'discount' => 'nullable|numeric',
        //     'net_amount' => 'required|numeric',
        //     'payment_mode' => 'required|string',
        // ]);
        //Db function
        DB::transaction(function () use ($request, $id) {
            $sale = Sale::with('items')->findOrFail($id);
            //Handle file upload (optional)
            $currentFile = $sale->prescription_file;
            if ($request->hasFile('prescription_file')) {
                $prescription = $request->file('prescription_file');
                $prescription->move(public_path('prescriptions'), $prescription->getClientOriginalName());
                $currentFile = $prescription->getClientOriginalName();
            }
            //Update Sale
            $sale->update([
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'phone' => $request->phone,
                'address' => $request->address,
                'doctor_name' => $request->doctor_name,
                'prescription_file' => $currentFile,
                'total_amount' => $request->total_amount,
                'discount' => '0',
                'net_amount' => '0',
                'payment_mode' => $request->payment_mode,
            ]);
            //Update sale items
            $sale->items()->delete();
            foreach ($request->salt_name as $index => $salt_name) {
                $sale->items()->create([
                    'medicine_name' => 'Thakur',
                    'salt_name' => $salt_name,
                    'brand_name' => $request->brand_name[$index],
                    'company' => $request->company[$index],
                    'type' => '0',
                    'base_price' => $request->base_price[$index],
                    'sale_price' => $request->sale_price[$index],
                    'margin' => $request->margin[$index],
                    'total_amount' => $request->total_amount,
                    'discount' => '0',
                    'net_amount' => $request->total_amount,
                    'quantity' => '0',
                    'line_total' => '0',
                ]);
            }
        });
        
        return redirect()->route('mr.sales.index')->with('success', 'Sale updated successfully.');
    }
}
