<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\ManagerPurchaseManager;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
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

    //Function for all purchase orders
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Auth login detail
        $managerId = Auth::id();
        //Get purchase mangers
        $purchase_manager_ids = ManagerPurchaseManager::where('manager_id', $managerId)
            ->pluck('purchase_manager_id')
            ->toArray();
        //Get purchase orders
        $ordersQuery = PurchaseOrder::with(['vendor', 'items', 'purchaseManager'])
            ->where('manager_id', $managerId)
            ->whereIn('purchase_manager_id', $purchase_manager_ids)
            ->orderByDesc('id');
        //Filter by status
        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }
        //Filter by manager id
        if ($request->filled('purchase_manager_id')) {
            $ordersQuery->where('purchase_manager_id', $request->purchase_manager_id);
        }
        //Filter by data range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $ordersQuery->whereDate('order_date', today());
                    break;
                case 'this_week':
                    $ordersQuery->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $ordersQuery->whereMonth('order_date', now()->month)
                        ->whereYear('order_date', now()->year);
                    break;
                case 'this_year':
                    $ordersQuery->whereYear('order_date', now()->year);
                    break;
            }
        }
        //Get orders
        $orders = $ordersQuery->paginate(10);
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase orders fetched successfully.',
            'data' => $orders
        ]);
    }

    //Function for approve order
    public function approve($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth login
        $managerId = Auth::id();
        //Find purchase order
        $po = PurchaseOrder::where('manager_id', $managerId)->find($id);
        //Check if po found or not
        if (!$po) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null,
            ]);
        }
        //Check if order already rejected or not
        if ($po->status == 'approved') {
            return response()->json([
                'status' => 400,
                'message' => 'This order is already approved. Please reject it first to re-approve.',
                'data' => null
            ]);
        }
        //Update status
        $po->update([
            'status' => 'approved',
            'manager_id'  => Auth::id(),
        ]);
        //Response
        return response()->json([
            'status' => 200,
            'message' => "PO #{$po->id} approved successfully.",
            'data' => $po
        ]);
    }

    //Function for reject order
    public function reject($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $managerId = Auth::id();
        //Find order
        $po = PurchaseOrder::where('manager_id', $managerId)->find($id);
        //Check if order found or not
        if (!$po) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null,
            ]);
        }
        //Check if order already rejected or not
        if ($po->status == 'rejected') {
            return response()->json([
                'status' => 400,
                'message' => 'This order is already rejected. Please approve it first before rejecting again.',
                'data' => null
            ]);
        }
        //Update
        $po->update([
            'status' => 'rejected',
            'manager_id'  => Auth::id(),
        ]);
        //Response
        return response()->json([
            'status' => 200,
            'message' => "PO #{$po->id} rejected successfully.",
            'data' => $po
        ]);
    }

    //Function for update purchase order
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $managerId = Auth::id();
        //Validate input fields
        $validated = Validator::make($request->all(), [
            'vendor_id'        => 'required',
            'order_date'       => 'required',
            'nature_of_vendor' => 'required',
            'notes'            => 'required',
            'items'            => 'required|array|min:1',
            'items.*.product_name' => 'required',
            'items.*.type'         => 'required',
            'items.*.quantity'     => 'required|numeric',
        ]);
        //If validation fails
        if ($validated->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validated->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        $validatedData = $validated->validated();
    
        //Find purchase order
        $order = PurchaseOrder::where('manager_id', $managerId)->find($id);
        //Check if order found or not
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null
            ]);
        }
        //Update 
        DB::transaction(function () use ($order, $validatedData) {
            $order->update([
                'vendor_id'          => $validatedData['vendor_id'],
                'order_date'         => $validatedData['order_date'],
                'nature_of_vendor'   => $validatedData['nature_of_vendor'],
                'notes'              => $validatedData['notes'] ?? null,
            ]);
            //Delete old record
            $order->items()->delete();
            //Create items
            foreach ($validatedData['items'] as $item) {
                $order->items()->create([
                    'product_name'   => $item['product_name'],
                    'type'           => $item['type'] ?? null,
                    'quantity'       => $item['quantity'],
                ]);
            }
        });
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase order updated successfully.',
            'data' => PurchaseOrder::with('items')->find($order->id)
        ]);
    }
    
    //Function for all vendors
    public function all_vendors() {
        //Logged-in manager ID
        $managerId = Auth::id();
        //Get purchase manager IDs under this manager
        $purchase_manager_ids = ManagerPurchaseManager::where('manager_id', $managerId)
            ->pluck('purchase_manager_id')
            ->toArray();
        //Get vendors
        $vendors = PurchaseOrder::with('vendor')
            ->whereIn('purchase_manager_id', $purchase_manager_ids)
            ->select('vendor_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return $item->vendor;
            });

        // Response
        return response()->json([
            'status' => 200,
            'message' => 'Vendors fetched successfully.',
            'data' => $vendors
        ]);
    }

    //Function for delete purchase order
    public function destroy($id) {
        //Get auth login detail
        $managerId = Auth::id();
        //Find purchase order
        $order = PurchaseOrder::where('manager_id', $managerId)->find($id);
        //Check not found
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null
            ], 404);
        }
        //Delete
        $order->delete();
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase order deleted successfully.',
            'data' => null
        ]);
    }
}
