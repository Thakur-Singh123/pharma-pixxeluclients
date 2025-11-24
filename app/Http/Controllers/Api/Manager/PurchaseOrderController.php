<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\ManagerPurchaseManager;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    // LIST PURCHASE ORDERS
    public function index(Request $request)
    {
        $managerId = Auth::id();

        $purchase_manager_ids = ManagerPurchaseManager::where('manager_id', $managerId)
            ->pluck('purchase_manager_id')
            ->toArray();

        $ordersQuery = PurchaseOrder::with(['vendor', 'items', 'purchaseManager'])
            ->where('manager_id', $managerId)
            ->whereIn('purchase_manager_id', $purchase_manager_ids)
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('purchase_manager_id')) {
            $ordersQuery->where('purchase_manager_id', $request->purchase_manager_id);
        }

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

        $orders = $ordersQuery->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => 'Purchase orders fetched successfully.',
            'data' => $orders
        ]);
    }

    // APPROVE ORDER
    public function approve($id)
    {
        $managerId = Auth::id();

        $po = PurchaseOrder::where('manager_id', $managerId)->find($id);

        if (!$po) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null,
            ]);
        }

        $po->update(['status' => 'approved']);

        return response()->json([
            'status' => 200,
            'message' => "PO #{$po->id} approved successfully.",
            'data' => $po
        ]);
    }

    // REJECT ORDER
    public function reject($id)
    {
        $managerId = Auth::id();

        $po = PurchaseOrder::where('manager_id', $managerId)->find($id);

        if (!$po) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null,
            ]);
        }

        $po->update(['status' => 'rejected']);

        return response()->json([
            'status' => 200,
            'message' => "PO #{$po->id} rejected successfully.",
            'data' => $po
        ]);
    }

    // SINGLE ORDER DETAIL
    public function show($id)
    {
        $managerId = Auth::id();

        $order = PurchaseOrder::with(['vendor', 'items'])
            ->where('manager_id', $managerId)
            ->find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Purchase order fetched successfully.',
            'data' => $order
        ]);
    }

    // UPDATE ORDER
    public function update(Request $request, $id)
    {
        $managerId = Auth::id();

        $validated = $request->validate([
            'vendor_id'              => 'required|exists:users,id',
            'order_date'             => 'required|date',
            'nature_of_vendor'       => 'nullable|string',
            'notes'                  => 'nullable|string|max:1000',
            'items'                  => 'required|array|min:1',
            'items.*.product_name'   => 'required|string|max:255',
            'items.*.type'           => 'nullable|string|max:100',
            'items.*.quantity'       => 'required|numeric|min:1',
        ]);

        $order = PurchaseOrder::where('manager_id', $managerId)->find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Purchase order not found.',
                'data' => null
            ]);
        }

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'vendor_id'          => $validated['vendor_id'],
                'order_date'         => $validated['order_date'],
                'nature_of_vendor'   => $validated['nature_of_vendor'],
                'notes'              => $validated['notes'] ?? null,
            ]);

            $order->items()->delete();

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_name'   => $item['product_name'],
                    'type'           => $item['type'] ?? null,
                    'quantity'       => $item['quantity'],
                ]);
            }
        });

        return response()->json([
            'status' => 200,
            'message' => 'Purchase order updated successfully.',
            'data' => PurchaseOrder::with('items')->find($order->id)
        ]);
    }
}
