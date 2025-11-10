<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Show vendor dashboard
    public function dashboard(Request $request)
    {
        $vendorId = Auth::id();
        $dateRange = $request->get('date_range', 'all'); // default today

        $query = PurchaseOrder::where('vendor_id', $vendorId);

        // Apply date range filter
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
                // no date filter
                break;
        }

        $orders = $query->get();

        // Counts & totals
        $pendingCount = $orders->where('is_delivered', 'pending')->count();
        $completedCount = $orders->where('is_delivered', 'completed')->count();
        $approvedTotal = $orders->where('is_delivered', 'completed')->sum('grand_total');

        // Chart Data
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
                $chartPending[] = $orders->where('is_delivered', 'pending')
                                         ->whereMonth('order_date', $m)->count();
                $chartCompleted[] = $orders->where('is_delivered', 'completed')
                                           ->whereMonth('order_date', $m)->count();
            }
        }

        return view('vendor.dashboard', compact(
            'pendingCount', 'completedCount', 'approvedTotal',
            'chartLabels', 'chartPending', 'chartCompleted', 'dateRange'
        ));
    }
}
