<?php

namespace App\Http\Controllers\PurchaseManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //Function for show dashboard
    public function dashboard() {
        //Get orders
        $total_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())->count();
        //delivered
        $delivered_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())->where('is_delivered', 'completed')->count();
        //approved
        $approved_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())->where('status', 'approved')->count();
        //pending
        $pending_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())->where('is_delivered', 'pending')->count();
       
        return view('purchase_manager.dashboard', compact('total_orders','delivered_orders','approved_orders','pending_orders'));
    }
}
