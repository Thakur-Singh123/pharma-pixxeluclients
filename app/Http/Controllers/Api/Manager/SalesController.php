<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\MangerMR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class SalesController extends Controller
{
    //Function for authentication
    private function ensureAuthenticated(): ?JsonResponse {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    } 

    //Function for all sales
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get mrs
        $mrs_id = MangerMR::where('manager_id', Auth::id())->pluck('mr_id')->toArray();
        //query
        $query = Sale::orderBy('created_at', 'desc')
            ->where(function($q) use ($mrs_id) {
                $q->whereIn('user_id', $mrs_id)
                ->whereNull('manager_id');
            })
            ->orWhere('manager_id', Auth::id());
        //Get sales
        $sales = $query->with('user','items')->paginate(10);
        //Get image
        $sales->getCollection()->transform(function ($sale) {
            if ($sale->prescription_file) {
                $sale->prescription_file = url('public/prescriptions/'.$sale->prescription_file);
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

    //Function for approve sale
    public function approve($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Find sale
        $sale = Sale::find($id);
        //Check sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ]);
        }
        //Check sale already approved or not
        if ($sale->status == 'Approved') {
            return response()->json([
                'status' => 400,
                'message' => 'This sale is already approved. Please reject it first to re-approve.',
                'data' => null
            ]);
        }
        //Update status
        $sale->update([
            'status'      => 'Approved',
            'approved_by' => 1,
            'manager_id'  => Auth::id(),
        ]);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sale approved successfully.',
            'data' => $sale
        ]);
    }

    //Function for reject sale
    public function reject($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Find sale
        $sale = Sale::find($id);
        //check if sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ]);
        }
        //Check if sale already rejected or not
        if ($sale->status == 'Reject') {
            return response()->json([
                'status' => 400,
                'message' => 'This sale is already rejected. Please approve it first before rejecting again.',
                'data' => null
            ]);
        }
        //Update status
        $sale->update([
            'status'      => 'Reject',
            'approved_by' => 0,
            'manager_id'  => null,
        ]);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Sale rejected successfully.',
            'data' => $sale
        ]);
    }

    //Functionf for update sale
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'designation' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'doctor_name' => 'required',
            'salt_name' => 'required|array',
            'brand_name' => 'required|array',
            'company' => 'required|array',
            'base_price' => 'required|array',
            'sale_price' => 'required|array',
            'margin' => 'required|array',
            'payment_mode' => 'required|string',
            'total_amount' => 'required',
        ]);
        //Response
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null
            ]);
        }
        //Sale find
        $sale = Sale::with('items')->find($id);
        //Check sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ]);
        }
        
        DB::transaction(function () use ($request, $sale) {
            //File upload
            $file = $sale->prescription_file;
            if ($request->hasFile('prescription_file')) {
                $img = $request->file('prescription_file');
                $img->move(public_path('prescriptions'), $img->getClientOriginalName());
                $file = $img->getClientOriginalName();
            }
            //update sale
            $sale->update([
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'phone' => $request->phone,
                'address' => $request->address,
                'doctor_name' => $request->doctor_name,
                'prescription_file' => $file,
                'total_amount' => $request->total_amount,
                'discount' => 0,
                'net_amount' => $request->total_amount,
                'payment_mode' => $request->payment_mode,
            ]);
            //delete old items
            $sale->items()->delete();
            //create new items
            foreach ($request->salt_name as $i => $salt) {
                $sale->items()->create([
                    'medicine_name' => $request->medicine_name[$i] ?? 'N/A',
                    'salt_name' => $salt,
                    'brand_name' => $request->brand_name[$i],
                    'company' => $request->company[$i],
                    'type' => 0,
                    'base_price' => $request->base_price[$i],
                    'sale_price' => $request->sale_price[$i],
                    'margin' => $request->margin[$i],
                    'total_amount' => $request->total_amount,
                    'discount' => 0,
                    'net_amount' => $request->total_amount,
                    'quantity' => 0,
                    'line_total' => 0,
                ]);
            }
        });
        //Update sale
        $updated = Sale::with('items')->find($sale->id);
        if ($updated->prescription_file) {
            $updated->prescription_file = url('public/prescriptions/'.$updated->prescription_file);
        }
        //Respnse
        return response()->json([
            'status' => 200,
            'message' => 'Sale updated successfully.',
            'data' => $updated
        ]);
    }

    //Function for delete sale
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Sale find
        $sale = Sale::with('items')->find($id);
        //Check if sale found or not
        if (!$sale) {
            return response()->json([
                'status' => 404,
                'message' => 'Sale not found.',
                'data' => null
            ]);
        }
        //Delete file
        if ($sale->prescription_file && file_exists(public_path('prescriptions/'.$sale->prescription_file))) {
            @unlink(public_path('prescriptions/'.$sale->prescription_file));
        }
        //Delete items
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

