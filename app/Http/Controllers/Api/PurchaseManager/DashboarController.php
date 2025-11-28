<?php

namespace App\Http\Controllers\Api\PurchaseManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class DashboarController extends Controller
{
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

    //Function for show dashboard
    public function dashboard() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get totsl orders
        $total_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())
            ->count();
        //Get delivered orders
        $delivered_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())
            ->where('is_delivered', 'completed')
            ->count();
        //Get approved orders
        $approved_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())
            ->where('status', 'approved')
            ->count();
        //Get pending orders
        $pending_orders = PurchaseOrder::where('purchase_manager_id', Auth::id())
            ->where('is_delivered', 'pending')
            ->count();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Dashboard data fetched successfully.',
            'data' => [
                'total_orders' => $total_orders,
                'delivered_orders' => $delivered_orders,
                'approved_orders' => $approved_orders,
                'pending_orders' => $pending_orders
            ]
        ], 200);
    }
}
