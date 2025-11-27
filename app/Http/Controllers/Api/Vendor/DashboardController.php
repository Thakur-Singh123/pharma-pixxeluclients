<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
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
    public function dashboard(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth login
        $vendorId = Auth::id();
        //Get date range
        $dateRange = $request->get('date_range', 'all'); 
        //Get purchase orders
        $query = PurchaseOrder::where('vendor_id', $vendorId);
        //filter
        switch ($dateRange) {
            case 'today':
                $query->whereDate('order_date', today());
                break;
            case 'this_week':
                $query->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereBetween('order_date', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            case 'this_year':
                $query->whereYear('order_date', now()->year);
                break;
            case 'all':
            default:
                break;
        }
        //Query
        $orders = $query->get();
        //Counts & totals
        $pendingCount = $orders->where('is_delivered', 'pending')->count();
        $completedCount = $orders->where('is_delivered', 'completed')->count();
        $approvedTotal = $orders->where('is_delivered', 'completed')->sum('grand_total');
        //Chart Data
        $chartLabels = [];
        $chartPending = [];
        $chartCompleted = [];

        if (in_array($dateRange, ['today', 'all'])) {

            $chartLabels[] = 'Orders';
            $chartPending[] = $pendingCount;
            $chartCompleted[] = $completedCount;

        } elseif ($dateRange === 'this_week') {

            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $chartLabels[] = $date->format('D');
                $chartPending[] = $orders->where('is_delivered', 'pending')
                    ->where('order_date', $date->format('Y-m-d'))->count();
                $chartCompleted[] = $orders->where('is_delivered', 'completed')
                    ->where('order_date', $date->format('Y-m-d'))->count();
            }

        } elseif ($dateRange === 'this_month') {

            $startOfMonth = now()->startOfMonth();
            $daysInMonth = now()->daysInMonth;

            for ($i = 0; $i < $daysInMonth; $i++) {
                $date = $startOfMonth->copy()->addDays($i);
                $chartLabels[] = $date->format('d M');
                $chartPending[] = $orders->where('is_delivered', 'pending')
                    ->where('order_date', $date->format('Y-m-d'))->count();
                $chartCompleted[] = $orders->where('is_delivered', 'completed')
                    ->where('order_date', $date->format('Y-m-d'))->count();
            }

        } elseif ($dateRange === 'this_year') {
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = Carbon::create(now()->year, $m, 1)->format('M');
                //Pending count per month
                $chartPending[] = PurchaseOrder::where('vendor_id', $vendorId)
                    ->whereYear('order_date', now()->year)
                    ->whereMonth('order_date', $m)
                    ->where('is_delivered', 'pending')
                    ->count();

                //Completed count per month
                $chartCompleted[] = PurchaseOrder::where('vendor_id', $vendorId)
                    ->whereYear('order_date', now()->year)
                    ->whereMonth('order_date', $m)
                    ->where('is_delivered', 'completed')
                    ->count();
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Vendor dashboard fetched successfully.',
            'data' => [
                'pending_count' => $pendingCount,
                'completed_count' => $completedCount,
                'approved_total' => $approvedTotal,
                'chart_labels' => $chartLabels,
                'chart_pending' => $chartPending,
                'chart_completed' => $chartCompleted,
                'date_range' => $dateRange,
            ]
        ]);
    }
}
