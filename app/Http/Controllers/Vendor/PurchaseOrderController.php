<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        $query = PurchaseOrder::with(['items'])
            ->where('vendor_id', $vendorId);

        //Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('order_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('order_date', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('vendor.purchase_orders.index', compact('orders'));
    }

}
