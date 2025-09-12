<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use DB;

class SalesController extends Controller
{
    //function for view sales
    public function index() {
        $sales = Sale::orderBy('created_at', 'desc')->with('user','items')->paginate(10);
        return view('manager.sales.index', compact('sales'));
    }

    //function for edit
    public function edit($id) {
        $sale = Sale::with('items')->findOrFail($id);
        return view('manager.sales.edit', compact('sale'));
    }

    //function for update
     public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'designation' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'doctor_name' => 'required|string',
            'prescription_file' => 'nullable',

            'medicine_name' => 'required|array',
            'medicine_name.*' => 'required|string',
            'base_price.*' => 'required|numeric',
            'sale_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'line_total.*' => 'required|numeric',

            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'net_amount' => 'required|numeric',
            'payment_mode' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            $sale = Sale::with('items')->findOrFail($id);

            // Handle file upload (optional)
            $currentFile = $sale->prescription_file;
            if ($request->hasFile('prescription_file')) {
                $prescription = $request->file('prescription_file');
                $prescription->move(public_path('prescriptions'), $prescription->getClientOriginalName());
                $currentFile = $prescription->getClientOriginalName();
            }

            // Update Sale
            $sale->update([
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'phone' => $request->phone,
                'address' => $request->address,
                'doctor_name' => $request->doctor_name,
                'prescription_file' => $currentFile,
                'total_amount' => $request->total_amount,
                'discount' => $request->discount ?? 0,
                'net_amount' => $request->net_amount,
                'payment_mode' => $request->payment_mode,
            ]);

            // Replace Sale Items
            $sale->items()->delete();
            foreach ($request->medicine_name as $index => $medicineName) {
                $sale->items()->create([
                    'medicine_name' => $medicineName,
                    'base_price' => $request->base_price[$index],
                    'sale_price' => $request->sale_price[$index],
                    'quantity' => $request->quantity[$index],
                    'line_total' => $request->line_total[$index],
                ]);
            }
        });

        return redirect()->route('manager.sales.index')->with('success', 'Sale updated successfully!');
    }
}
