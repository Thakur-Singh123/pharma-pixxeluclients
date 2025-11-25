<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;

class SalesController extends Controller
{
    //Function for all sales
    public function index() {
        //Get sales
        $sales = Sale::with('items')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //Get image
        $sales->getCollection()->transform(function ($sale) {
            if ($sale->prescription_file) {
                $sale->prescription_file = asset('public/prescriptions/' . $sale->prescription_file);
            }
            return $sale;
        });
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sales fetched successfully.',
            'data' => $sales
        ]);
    }

    //Function for store sale
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
            'phone' => 'required',
            'address'  => 'required',
            'doctor_name' => 'required',
            'payment_mode' => 'required',
            'total_amount' => 'required|numeric',

            'salt_name'  => 'required|array',
            'salt_name.*' => 'required|string',

            'brand_name'  => 'required|array',
            'brand_name.*' => 'required|string',

            'company' => 'required|array',
            'company.*' => 'required|string',

            'base_price' => 'required|array',
            'base_price.*' => 'required|numeric',

            'sale_price' => 'required|array',
            'sale_price.*' => 'required|numeric',

            'margin'  => 'required|array',
            'margin.*' => 'required|numeric',
        ]);
        //Response
        if ($validator->fails()) {
            return response()->json([
                'status'   => 400,
                'message'  => $validator->errors()->first(),
                'data'     => null
            ], 400);
        }
        //validated
        $validated = $validator->validated();
    
        DB::transaction(function () use ($request, $validated, &$sale) {

            $fileName = null;

            if ($request->hasFile('prescription_file')) {
                $file = $request->file('prescription_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('prescriptions'), $fileName);
            }
            //Create sale
            $sale = Sale::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'designation' => $validated['designation'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'doctor_name' => $validated['doctor_name'],
                'prescription_file' => $fileName,
                'payment_mode' => $validated['payment_mode'],
                'total_amount' => $validated['total_amount'],
                'discount' => 0,
                'net_amount' => 0,
                'user_id' => auth()->id(),
                'status' => 'Pending',
            ]);
            //validated
            foreach ($validated['salt_name'] as $i => $salt) {
                $sale->items()->create([
                    'medicine_name' => 'Thakur',
                    'salt_name' => $salt,
                    'brand_name' => $validated['brand_name'][$i],
                    'company' => $validated['company'][$i],
                    'type' => '0',
                    'base_price' => $validated['base_price'][$i],
                    'sale_price' => $validated['sale_price'][$i],
                    'margin' => $validated['margin'][$i],
                    'total_amount' => $validated['total_amount'],
                    'payment_mode' => $validated['payment_mode'],
                    'discount' => 0,
                    'net_amount' => $validated['total_amount'],
                    'quantity' => 0,
                    'line_total' => 0,
                ]);
            }
        });
        //Get sale
        $saleData = Sale::with('items')->find($sale->id);
        //Get image url
        if ($saleData->prescription_file) {
            $saleData->prescription_file = asset('public/prescriptions/' . $saleData->prescription_file);
        }
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sale created successfully.',
            'data' => $saleData
        ]);
    }

    //Function for update sale
    public function update(Request $request, $id) {
        //Get sale
        $sale = Sale::where('user_id', Auth::id())->find($id);
        //Check if sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ], 404);
        }
        //Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'doctor_name' => 'required',
            'payment_mode' => 'required',
            'total_amount' => 'required|numeric',

            'salt_name'  => 'required|array',
            'salt_name.*' => 'required|string',

            'brand_name' => 'required|array',
            'brand_name.*' => 'required|string',

            'company'  => 'required|array',
            'company.*' => 'required|string',

            'base_price' => 'required|array',
            'base_price.*' => 'required|numeric',

            'sale_price' => 'required|array',
            'sale_price.*' => 'required|numeric',

            'margin' => 'required|array',
            'margin.*' => 'required|numeric',
        ]);
        //validator
        if ($validator->fails()) {
            return response()->json([
                'status'   => 400,
                'message'  => $validator->errors()->first(),
                'data'     => null
            ], 400);
        }
        
        $validated = $validator->validated();

        DB::transaction(function () use ($request, $validated, $sale) {

            $fileName = $sale->prescription_file;

            if ($request->hasFile('prescription_file')) {
                $file = $request->file('prescription_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('prescriptions'), $fileName);
            }
            //update sale
            $sale->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'designation' => $validated['designation'],
                'phone'  => $validated['phone'],
                'address' => $validated['address'],
                'doctor_name' => $validated['doctor_name'],
                'prescription_file'=> $fileName,
                'payment_mode' => $validated['payment_mode'],
                'total_amount' => $validated['total_amount'],
                'discount'  => 0,
                'net_amount' => 0,
            ]);
            //Delete old item
            $sale->items()->delete();
            //Create item
            foreach ($validated['salt_name'] as $i => $salt) {
                $sale->items()->create([
                    'medicine_name' => 'Thakur',
                    'salt_name' => $salt,
                    'brand_name' => $validated['brand_name'][$i],
                    'company' => $validated['company'][$i],
                    'type' => '0',
                    'base_price' => $validated['base_price'][$i],
                    'sale_price' => $validated['sale_price'][$i],
                    'margin' => $validated['margin'][$i],
                    'total_amount' => $validated['total_amount'],
                    'payment_mode' => $validated['payment_mode'],
                    'discount' => 0,
                    'net_amount' => $validated['total_amount'],
                    'quantity' => 0,
                    'line_total' => 0,
                ]);
            }
        });
        //Find sale
        $saleData = Sale::with('items')->find($sale->id);
        //Get image
        if ($saleData->prescription_file) {
            $saleData->prescription_file = asset('public/prescriptions/' . $saleData->prescription_file);
        }
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sale updated successfully.',
            'data' => $saleData
        ]);
    }

    //Function for delete sale
    public function destroy($id) {
        //Get sale
        $sale = Sale::where('user_id', Auth::id())->find($id);
        //Sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ], 404);
        }
        //Delete sale item
        $sale->items()->delete();
        //Delete sale
        $sale->delete();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sale deleted successfully.',
            'data' => null
        ]);
    }
}
