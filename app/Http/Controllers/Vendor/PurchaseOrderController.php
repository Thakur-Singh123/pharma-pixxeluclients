<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VenodrPurchaseOrdersExport;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        $query = PurchaseOrder::with(['items', 'vendor'])
            ->where('vendor_id', $vendorId);

        //Status Filter
        if($request->filled('is_delivered')){
            $query->where('is_delivered', $request->is_delivered);
        }

        if($request->filled('date_range')){
            switch($request->date_range){
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

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('vendor.purchase_orders.index', compact('orders'));
    }

    public function updateDelivery(Request $request, $id)
    {
        $request->validate([
            'is_delivered' => 'required|in:pending,completed',
        ]);

        $po = PurchaseOrder::where('vendor_id', Auth::id())->findOrFail($id);

        $po->is_delivered = $request->is_delivered;
        $po->save();

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    public function export(Request $request)
    {
        $filters = $request->only(['is_delivered', 'date_range']);
        return Excel::download(new VenodrPurchaseOrdersExport($filters), 'purchase_orders.csv');
    }

}
