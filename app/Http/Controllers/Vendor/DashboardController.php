<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;

class DashboardController extends Controller
{
    //Function for show dashboard
    public function dashboard()
    {
        $vendorId = Auth::id();

                                     // Pehle se existing stats (ye tu already rakh raha hoga)
        $total_visits         = 120; // example
        $total_completed_task = 35;
        $total_attendances    = 90;
        $total_sales          = 50000;

        // New Stats for Orders
        $pendingCount = PurchaseOrder::where('vendor_id', $vendorId)
            ->where('status', 'pending')
            ->count();

        $approvedCount = PurchaseOrder::where('vendor_id', $vendorId)
            ->where('status', 'approved')
            ->count();

        $rejectedCount = PurchaseOrder::where('vendor_id', $vendorId)
            ->where('status', 'rejected')
            ->count();

        $approvedTotal = PurchaseOrder::where('vendor_id', $vendorId)
            ->where('status', 'approved')
            ->sum('grand_total');

        return view('vendor.dashboard', compact(
            'total_visits',
            'total_completed_task',
            'total_attendances',
            'total_sales',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'approvedTotal'
        ));
    }
}
