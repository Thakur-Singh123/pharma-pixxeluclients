<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PurchaseOrder;
use App\Exports\PurchaseOrdersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    //Function for ensure user is authenticated
    private function ensureAuthenticated(): ?JsonResponse {
        //Check if auth login or not
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    }

    //Function for all purchase order
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $vendorId = Auth::id();
        //Get purchase orders
        $query = PurchaseOrder::with(['items', 'vendor'])
            ->where('vendor_id', $vendorId);
        //Filter
        if ($request->filled('is_delivered')) {
            $query->where('is_delivered', $request->is_delivered);
        }
        //Date Filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('order_date', now());
                    break;

                case 'this_week':
                    $query->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;

                case 'this_month':
                    $query->whereMonth('order_date', now()->month)
                        ->whereYear('order_date', now()->year);
                    break;

                case 'this_year':
                    $query->whereYear('order_date', now()->year);
                    break;
            }
        }
        //Get orders
        $orders = $query->latest()->paginate(10);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase orders fetched successfully.',
            'data' => $orders
        ], 200);
    }

    //Function for update devlivery status
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'is_delivered' => 'required|in:pending,completed',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Get purchase orders
        $po = PurchaseOrder::where('vendor_id', Auth::id()) 
            ->where('id', $id)
            ->first();
        //Check purchase order found or not 
        if (!$po) { 
            return response()->json([ 
                'status' => 404, 
                'message' => 'Purchase order not found' ], 
            404); 
        }
        //Get is delivered
        $po->is_delivered = $request->is_delivered;
        //Save
        $po->save();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Delivery status updated successfully.',
            'data' => $po
        ], 200);
    }
}
