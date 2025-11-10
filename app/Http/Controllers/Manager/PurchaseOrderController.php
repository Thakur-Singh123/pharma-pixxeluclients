<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ManagerPurchaseManager;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\PurchaseOrderApprovedNotification;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $managerId = Auth::id();

        $purchase_manager_ids = ManagerPurchaseManager::where('manager_id', $managerId)
            ->pluck('purchase_manager_id')
            ->toArray();

        $pms = User::where('status', 'Active')
            ->whereIn('id', $purchase_manager_ids)
            ->get();

        $orders = PurchaseOrder::with(['vendor', 'items'])
            ->withCount('items')
            ->where('manager_id', $managerId)
            ->whereIn('purchase_manager_id', $purchase_manager_ids)
            ->orderByDesc('id')
            ->paginate(10);

        return view('manager.purchase_orders.index', compact('orders', 'pms'));
    }

    // Approve Order
    public function approve($id)
    {
        $managerId = Auth::id();

        $po = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        $po->update(['status' => 'approved']);

        //Get purchaseManager detail
        $purchaseManager = User::find($po->purchase_manager_id);
        //echo "<pre>"; print_r($purchaseManager->toArray());exit;
        //Check if manager exists or not
        if ($purchaseManager) {
            //Send notification
            $purchaseManager->notify(new PurchaseOrderApprovedNotification($po, 'purchase_manager'));
        }

        //Get vendor detail
        $vendor = User::find($po->vendor_id);
        if ($vendor) {
            $vendor->notify(new PurchaseOrderApprovedNotification($po, 'vendor'));
        }

        return back()->with('success', "PO #{$po->id} approved.");
    }

    // Reject Order
    public function reject($id)
    {
        $managerId = Auth::id();

        $po = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        $po->update(['status' => 'rejected']);

        return back()->with('success', "PO #{$po->id} rejected.");
    }

    // Edit page (includes vendor list)
    public function edit($id)
    {
        $managerId = Auth::id();

        $order = PurchaseOrder::with(['vendor', 'items'])
            ->where('manager_id', $managerId)
            ->findOrFail($id);

        // Only show that vendor
        $vendors = User::where('user_type', 'vendor')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.purchase_orders.edit', compact('order', 'vendors'));
    }

    //Update
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
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.discount_type'  => 'nullable|in:flat,percent',
            'items.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        $order = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'vendor_id'  => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'nature_of_vendor' => $validated['nature_of_vendor'],
                'notes'      => $validated['notes'] ?? null,
            ]);

            $order->items()->delete();

            $subtotal = 0;
            $discountTotal = 0;

            foreach ($validated['items'] as $item) {
                $qty   = (float) $item['quantity'];
                $price = (float) $item['price'];
                $gross = $qty * $price;

                $dtype = $item['discount_type'] ?? 'flat';
                $dval  = (float) ($item['discount_value'] ?? 0);

                $disc = ($dtype === 'percent') ? ($gross * ($dval / 100)) : $dval;
                if ($disc > $gross) {
                    $disc = $gross;
                }

                $lineTotal = $gross - $disc;

                $order->items()->create([
                    'product_name'   => $item['product_name'],
                    'type'           => $item['type'] ?? null,
                    'quantity'       => $qty,
                    'price'          => $price,
                    'discount_type'  => $dtype,
                    'discount_value' => $dval,
                    'line_total'     => $lineTotal,
                ]);

                $subtotal += $gross;
                $discountTotal += $disc;
            }

            $order->update([
                'subtotal'       => $subtotal,
                'discount_total' => $discountTotal,
                'grand_total'    => $subtotal - $discountTotal,
            ]);
        });

        // ✅ Redirect with correct route name (manager prefix)
        return redirect()->route('manager.purchase-manager.index')
            ->with('success', 'Purchase Order updated successfully.');
    }
}
