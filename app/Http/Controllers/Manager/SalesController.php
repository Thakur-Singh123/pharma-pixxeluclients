<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
Use Auth;
use App\Models\MangerMR;
use DB;

class SalesController extends Controller
{
    //Function for all sales
    public function index(Request $request) {
        //Get auth login detail
        $mrs_id = MangerMR::where('manager_id', auth()->user()->id)->pluck('mr_id')->toArray();
        $mrs = User::where('can_sale', 1)->where('status','Active')->whereIn('id', $mrs_id)->get();
        //Filter sale
        $query = Sale::orderBy('created_at', 'desc')->where('user_id', $mrs_id);
        if($request->filled('created_by')){
            $query->where('user_id', $request->created_by);
        }
        //Get sales
        $sales = $query->with('user','items')->paginate(5);
        return view('manager.sales.index', compact('sales','mrs'));
    }

    //Function for edit sale
    public function edit($id) {
        //Get sale detail
        $sale = Sale::with('items')->findOrFail($id);
        return view('manager.sales.edit', compact('sale'));
    }

    //Function for approval sale
    public function sale_approve($id) {
        //Get sale detail
        $sale_detail = Sale::with('items')->findOrFail($id);
        //Update status
        $sale_detail->status = 'Approved';
        $sale_detail->approved_by = '1';
        $sale_detail->save();

        return redirect()->back()->with('success', 'Sale approved successfully.');
    }

    //Function for reject sale
    public function sale_reject($id) {
        //Get sale detail
        $sale_detail = Sale::with('items')->findOrFail($id);
        //Update status
        $sale_detail->status = 'Reject';
        $sale_detail->approved_by = '0';
        $sale_detail->save();

        return redirect()->back()->with('success', 'Sale reject successfully.');
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
            //Update sale
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

        return redirect()->route('manager.sales.index')->with('success', 'Sale updated successfully.');
    }

    //Function for destroy sale
    public function destroy($id) {
        //Get sale detail
        $sale = Sale::findOrFail($id);
        //Check if prescription file exists or not
        if($sale && $sale->prescription_file){
            unlink(public_path('prescriptions/'.$sale->prescription_file));
        }
        //Delete sale item
        if($sale->items){
            foreach($sale->items as $item){
                $item->delete();
            }
        }
        $sale->delete();
        return redirect()->route('manager.sales.index')->with('success', 'Sale deleted successfully.');
    }
}
