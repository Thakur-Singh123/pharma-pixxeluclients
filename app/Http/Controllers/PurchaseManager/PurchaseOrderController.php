<?php
namespace App\Http\Controllers\PurchaseManager;

use App\Http\Controllers\Controller;
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

class PurchaseOrderController extends Controller
{
    public function create()
    {
        // All vendors from users table
        $purchase_manager = Auth::id();
        $managerId        = ManagerPurchaseManager::where('purchase_manager_id', $purchase_manager)->value('manager_id');
        $manager_vendor   = ManagerVendor::where('manager_id', $managerId)->pluck('vendor_id')->toArray();
        $vendors          = User::where('user_type', 'vendor')
            ->whereIn('id', $manager_vendor)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('purchase_manager.purchase_orders.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id'              => 'required|exists:users,id',
            'order_date'             => 'required|date',
            'nature_of_vendor'       => 'nullable|required',
            'notes'                  => 'nullable|string|max:1000',
            'items'                  => 'required|array|min:1',
            'items.*.product_name'   => 'required|string|max:255',
            'items.*.type'           => 'nullable|string|max:100',
            'items.*.quantity'       => 'required|numeric|min:1',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.discount_type'  => 'nullable|in:flat,percent',
            'items.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $managerId = ManagerPurchaseManager::where('purchase_manager_id', Auth::id())->value('manager_id');
            $po = PurchaseOrder::create([
                'purchase_manager_id' => Auth::id(),
                'manager_id'          => $managerId,
                'vendor_id'           => $validated['vendor_id'],
                'order_date'          => $validated['order_date'],
                'nature_of_vendor'    => $validated['nature_of_vendor'],
                'notes'               => $validated['notes'] ?? null,
                'subtotal'            => 0,
                'discount_total'      => 0,
                'grand_total'         => 0,
            ]);

            $subtotal      = 0;
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

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name'      => $item['product_name'],
                    'type'              => $item['type'] ?? null,
                    'quantity'          => $qty,
                    'price'             => $price,
                    'discount_type'     => $dtype,
                    'discount_value'    => $dval,
                    'line_total'        => $lineTotal,
                ]);

                $subtotal += $gross;
                $discountTotal += $disc;
            }

            $po->update([
                'subtotal'       => $subtotal,
                'discount_total' => $discountTotal,
                'grand_total'    => $subtotal - $discountTotal,
            ]);
            //Get manager detail
            $manager = User::find($managerId);
            //Check if manager exists or not
            if ($manager) {
                //Send notification
                $manager->notify(new PurchaseOrderNotification($po));
            }
        });

        return redirect()->route('purchase-manager.purchase-orders.index')
            ->with('success', 'Purchase Order created successfully.');
    }

    public function index(Request $request)
    {
        $pmId = Auth::id();

        $q    = $request->q;
        $from = $request->from;
        $to   = $request->to;

        $orders = PurchaseOrder::with(['vendor'])
            ->withCount('items')
            ->where('purchase_manager_id', $pmId)
            ->when($q, function ($qr) use ($q) {
                $qr->where('id', (int) $q)
                    ->orWhereHas('vendor', function ($v) use ($q) {
                        $v->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            })
            ->when($from, fn($qr) => $qr->whereDate('order_date', '>=', $from))
            ->when($to, fn($qr) => $qr->whereDate('order_date', '<=', $to))
            ->orderByDesc('id')
            ->paginate(10);

        return view('purchase_manager.purchase_orders.index', compact('orders'));
    }

    public function edit($id)
    {
        $pmId = Auth::id();

        // Order must belong to this Purchase Manager
        $order = PurchaseOrder::with(['items', 'vendor'])
            ->where('purchase_manager_id', $pmId)
            ->findOrFail($id);

        // Vendors under the same manager as this PM
        $managerId      = ManagerPurchaseManager::where('purchase_manager_id', $pmId)->value('manager_id');
        $managerVendors = ManagerVendor::where('manager_id', $managerId)->pluck('vendor_id')->toArray();

        $vendors = User::where('user_type', 'vendor')
            ->whereIn('id', $managerVendors)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('purchase_manager.purchase_orders.edit', compact('order', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $pmId = Auth::id();

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

        $order = PurchaseOrder::where('purchase_manager_id', $pmId)->findOrFail($id);

        DB::transaction(function () use ($order, $validated) {
            if ($order->status === 'approved') {
                $order->status = 'pending';
            }

            // Update header
            $order->update([
                'vendor_id'  => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'nature_of_vendor' => $validated['nature_of_vendor'],
                'notes'      => $validated['notes'] ?? null,
            ]);

            // Rebuild items
            $order->items()->delete();

            $subtotal      = 0;
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
            //Get manager detail
            $manager = User::find($order->manager_id);
            // echo "<pre>"; print_r($manager->toArray());exit;
            //Check if manager exists or not
            if ($manager) {
                //Send notification
                $manager->notify(new PurchaseOrderUpdatedNotification($order));
            }
        });

        return redirect()->route('purchase-manager.purchase-orders.index')
            ->with('success', 'Purchase Order updated successfully.');
    }

    public function destroy($id)
    {
        $pmId = Auth::id();

        $order = PurchaseOrder::where('purchase_manager_id', $pmId)->findOrFail($id);
        $order->delete(); // make sure FK is set to cascade for items

        return back()->with('success', 'Purchase Order deleted successfully.');
    }

}
