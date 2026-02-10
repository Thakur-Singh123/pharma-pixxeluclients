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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseOrdersExport;
use App\Mail\PurchaseOrderApprovedMail;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderController extends Controller
{
    //Function for all po
    public function index(Request $request) {
        //Get manager
        $managerId = Auth::id();
        //Get purchase manager
        $purchase_manager_ids = ManagerPurchaseManager::where('manager_id', $managerId)
            ->pluck('purchase_manager_id')
            ->toArray();
        //Get users
        $pms = User::where('status', 'Active')
            ->whereIn('id', $purchase_manager_ids)
            ->get();
        //Query purchase orders
        $ordersQuery = PurchaseOrder::with(['vendor', 'items', 'purchaseManager'])
            ->withCount('items')
            ->where('manager_id', $managerId)
            ->whereIn('purchase_manager_id', $purchase_manager_ids)
            ->orderByDesc('id');
        //Filter by status
        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }
        //Filter by purchase manager
        if ($request->filled('purchase_manager_id')) {
            $ordersQuery->where('purchase_manager_id', $request->purchase_manager_id);
        }
        //Filter by date range
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
                case 'all':
                default:
                    break;
            }
        }
        //All orders
        $orders = $ordersQuery->paginate(5); 
        return view('manager.purchase_orders.index', compact('orders', 'pms'));
    }

    //Function for approve order
    public function approve($id) {
        //Get auth login
        $managerId = Auth::id();
        //Get po
        $po = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        $po->update(['status' => 'approved']);
        //Get purchaseManager detail
        $purchaseManager = User::find($po->purchase_manager_id);
        //echo "<pre>"; print_r($purchaseManager->toArray());exit;
        //Check if manager exists or not
        if ($purchaseManager) {
            //Send notification
            $purchaseManager->notify(new PurchaseOrderApprovedNotification($po, 'purchase_manager'));
            Mail::to($purchaseManager->email)->send(new PurchaseOrderApprovedMail($po, 'purchase_manager'));
        }
        //Get vendor detail
        $vendor = User::find($po->vendor_id);
        //Send notification
        if ($vendor) {
            $vendor->notify(new PurchaseOrderApprovedNotification($po, 'vendor'));
            Mail::to($vendor->email)->send(new PurchaseOrderApprovedMail($po, 'vendor'));
        }

        return back()->with('success', "Purchase Order No #{$po->id} approved successfully.");
    }

    //Functin for reject order
    public function reject($id) {
        //Get auth login
        $managerId = Auth::id();
        //Get po
        $po = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        //Status
        $po->update(['status' => 'rejected']);
        return back()->with('success', "Purchase Order No #{$po->id} rejected successfully.");
    }

    //Function for edit po
    public function edit($id) {
        //Get auth login
        $managerId = Auth::id();
        //Get po
        $order = PurchaseOrder::with(['vendor', 'items'])
            ->where('manager_id', $managerId)
            ->findOrFail($id);
        //Get vendors
        $vendors = User::where('user_type', 'vendor')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('manager.purchase_orders.edit', compact('order', 'vendors'));
    }

    //Function for update po
    public function update(Request $request, $id) {
        //Get auth login
        $managerId = Auth::id();
        //Validate input fields
        $validated = $request->validate([
            'vendor_id'              => 'required|exists:users,id',
            'order_date'             => 'required|date',
            'nature_of_vendor'       => 'nullable|string',
            'notes'                  => 'nullable|string|max:1000',
            'items'                  => 'required|array|min:1',
            'items.*.product_name'   => 'required|string|max:255',
            'items.*.type'           => 'nullable|string|max:100',
            'items.*.quantity'       => 'required|numeric|min:1',
            //'items.*.price'          => 'required|numeric|min:0',
            //'items.*.discount_type'  => 'nullable|in:flat,percent',
            //'items.*.discount_value' => 'nullable|numeric|min:0',
        ]);
        //Get po
        $order = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        //Transaction
        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'vendor_id'  => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'nature_of_vendor' => $validated['nature_of_vendor'],
                'notes'      => $validated['notes'] ?? null,
            ]);
            //delete po
            $order->items()->delete();
            //$subtotal = 0;
            //$discountTotal = 0;
            foreach ($validated['items'] as $item) {
                $qty   = (float) $item['quantity'];
                //$price = (float) $item['price'];
                //$gross = $qty * $price;
                //$dtype = $item['discount_type'] ?? 'flat';
                //$dval  = (float) ($item['discount_value'] ?? 0);
                //$disc = ($dtype === 'percent') ? ($gross * ($dval / 100)) : $dval;
                //if ($disc > $gross) {
                //$disc = $gross;
                //}
                //$lineTotal = $gross - $disc;
                $order->items()->create([
                    'product_name'   => $item['product_name'],
                    'type'           => $item['type'] ?? null,
                    'quantity'       => $qty,
                    //'price'          => $price,
                    //'discount_type'  => $dtype,
                    //'discount_value' => $dval,
                    //'line_total'     => $lineTotal,
                ]);
                //$subtotal += $gross;
                //$discountTotal += $disc;
            }
            //$order->update([
            //'subtotal'       => $subtotal,
            //'discount_total' => $discountTotal,
            //'grand_total'    => $subtotal - $discountTotal,
            //]);
        });
        //Redirect with correct route name (manager prefix)
        return redirect()->route('manager.purchase-manager.index')
            ->with('success', 'Purchase Order updated successfully.');
    }

    //Function for export po
    public function export(Request $request) {
        //Filter
        $filters = $request->only(['is_delivered', 'date_range', 'status', 'purchase_manager_id']);
        return Excel::download(new PurchaseOrdersExport($filters), 'purchase_orders.csv');
    }

    //Function for delete purchase order
    public function destroy($id) {
        //Get auth login detail
        $managerId = Auth::id();
        //Find purchase order
        $order = PurchaseOrder::where('manager_id', $managerId)->findOrFail($id);
        //Delete order
        $order->delete();
        return back()->with('success', 'Purchase Order deleted successfully.');
    }
}
