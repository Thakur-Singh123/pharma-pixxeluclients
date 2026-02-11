<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseOrdersExport;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    //Function for all orders
    public function index(Request $request) {
        //Get vendor detail
        $vendorId = Auth::id();
        //Query
        $query = PurchaseOrder::with(['items', 'vendor'])
            ->where('vendor_id', $vendorId);
        //Status filter
        if($request->filled('is_delivered')){
            $query->where('is_delivered', $request->is_delivered);
        }
        //Order date filter
        if($request->filled('order_date')){
            $query->where('order_date', $request->order_date);
        }
        //Date range filter
        if($request->filled('date_range')) {
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
        //Get order
        $orders = $query->latest()->paginate(5);

        return view('vendor.purchase_orders.index', compact('orders'));
    }

    //Function for update delivery status
    public function updateDelivery(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'is_delivered' => 'required|in:pending,completed',
        ]);
        //Get Po detail
        $po = PurchaseOrder::where('vendor_id', Auth::id())->findOrFail($id);
        //Get request
        $po->is_delivered = $request->is_delivered;
        //Save
        $po->save();

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    //Function for export 
    public function export(Request $request) {
        //Filter
        $filters = $request->only(['is_delivered', 'date_range']);
        return Excel::download(new PurchaseOrdersExport($filters), 'purchase_orders.csv');
    }

    //Function for signle vendor detail
    public function single_detail($id) {
        //Get auth login detail
        $vendorId = Auth::id(); 
        //Get Po detail
        $order = PurchaseOrder::with(['items', 'vendor'])
            ->where('vendor_id', $vendorId)
            ->findOrFail($id);

        return view('vendor.purchase_orders.single-detail', compact('order'));
    }
}
