<?php

namespace App\Http\Controllers\Api\PurchaseManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\ManagerPurchaseManager;
use App\Models\ManagerVendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Notifications\PurchaseOrderNotification;
use App\Notifications\PurchaseOrderUpdatedNotification;
use App\Mail\PurchaseOrderCreatedMail;
use App\Mail\PurchaseOrderUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Services\FirebaseService;

class PurchaseOrderController extends Controller
{
    Protected $fcmService;

    public function __construct(FirebaseService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

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

    //Function for get vendors
    public function vendor() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get purchase manage
        $pmId = Auth::id();
        //Get manager
        $managerId = ManagerPurchaseManager::where('purchase_manager_id', $pmId)->value('manager_id');
        //Check manager found or not
        if (!$managerId) {
            return response()->json([
                'status' => 404,
                'message' => 'Manager not found for this purchase manager.',
                'data' => null
            ], 404);
        }
        //Get vendor
        $vendorIds = ManagerVendor::where('manager_id', $managerId)->pluck('vendor_id');
        //Get vendor details
        $vendors = User::where('user_type', 'vendor')
            ->whereIn('id', $vendorIds)
            ->orderByDesc('id')
            ->get(['id', 'name', 'email']);
        //Check if vendor found or not
        if ($vendors->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No vendors found under this manager.',
                'data' => []
            ], 404);
        }
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Vendors fetched successfully.',
            'data' => $vendors
        ], 200);
    }

    //Function for all po
    public function index(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $pmId = Auth::id();
        //Get po
        $query = PurchaseOrder::with(['vendor', 'purchaseManager'])
            ->where('purchase_manager_id', $pmId);
        //Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        //Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        //Filter by date range
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
        //Query
        $orders = $query->orderByDesc('id')->paginate(10);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase orders fetched successfully.',
            'data' => $orders
        ], 200);
    }

    //Function for create po
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'nature_of_vendor' => 'nullable|required',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required',
            'items.*.type' => 'required',
            'items.*.quantity' => 'required|numeric',
        ]);
        //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        // Get validated data as ARRAY
        $validated = $validator->validated();
        $po  = null;
        $managerFcm  = [];
        DB::transaction(function () use ($validated, &$po, &$managerFcm) {
            //Get manager
            $managerId = ManagerPurchaseManager::where('purchase_manager_id', Auth::id())->value('manager_id');
            //Create po
            $po = PurchaseOrder::create([
                'purchase_manager_id' => Auth::id(),
                'manager_id' => $managerId,
                'vendor_id' => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'nature_of_vendor' => $validated['nature_of_vendor'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => 0,
                'discount_total' => 0,
                'grand_total' => 0,
            ]);
            //Create items
            foreach ($validated['items'] as $item) {
                $po->items()->create([
                    'product_name' => $item['product_name'],
                    'type' => $item['type'] ?? null,
                    'quantity' => (float) $item['quantity'],
                ]);
            }
            //Send manager notification
            $manager = User::find($managerId);
            if ($manager) {
                $manager->notify(new PurchaseOrderNotification($po));
                //fcm notification
                $managerFcm = $this->fcmService->sendToUser($manager, [
                    'id'         => $po->id,
                    'title'      => 'Purchase Order Created',
                    'message'    => auth()->user()->name . ' has created a new Purchase Order (#'. $po->id . ') for your review and approval.',
                    'type'       => 'purchase_order',
                    'is_read'    => false,
                    'created_at' => now()->toDateTimeString(),
                ]);
            }
            //Send vendor notification
            $vendor = User::find($validated['vendor_id']);
            if ($vendor && $vendor->email) {
                $po->load('items', 'vendor');
                Mail::to($vendor->email)->send(new PurchaseOrderCreatedMail($po));
            }
        });
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase order created successfully.',
            'fcm_response'  => $managerFcm,
            'data' => $po->load('items')
        ], 200);
    }

    //Function for update po
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $pmId = Auth::id();
        //Validate input fields
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'nature_of_vendor' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required',
            'items.*.type' => 'required',
            'items.*.quantity' => 'required|numeric',
        ]);
        //If validation fails
        if ($validation->fails()) {  
            $error['status'] = 400;
            $error['message'] =  $validation->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }
        //Validated 
        $validated = $validation->validated();
        //Get po
        $order = PurchaseOrder::where('purchase_manager_id', $pmId)->find($id);
        //Check if order found or not
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null
            ]);
        }
        //Db
        DB::transaction(function () use ($order, $validated) {
            if ($order->status === 'approved') {
                $order->status = 'pending';
            }
            //Update
            $order->update([
                'vendor_id'  => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'nature_of_vendor' => $validated['nature_of_vendor'],
                'notes' => $validated['notes'] ?? null,
            ]);
            //Delete
            $order->items()->delete();
            //Create items
            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'type' => $item['type'] ?? null,
                    'quantity' => (float) $item['quantity'],
                ]);
            }
            //Manager notification
            $manager = User::find($order->manager_id);
            if ($manager) {
                $manager->notify(new PurchaseOrderUpdatedNotification($order));
            }
            //Vendor notification
            $vendor = User::find($validated['vendor_id']);
            if ($vendor && $vendor->email) {
                Mail::to($vendor->email)->send(new PurchaseOrderUpdatedMail($order));
            }
        });
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase order updated successfully.',
            'data' => $order->load('items')
        ], 200);
    }
    
    //Function for delete po
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $pmId = Auth::id();
        //Get po
        $order = PurchaseOrder::where('purchase_manager_id', $pmId)->find($id);
        //Check not found
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null
            ], 404);
        }
        //Delete po
        $order->delete();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Purchase order deleted successfully.',
        ], 200);
    }
}
