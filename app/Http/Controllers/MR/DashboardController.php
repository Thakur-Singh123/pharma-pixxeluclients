<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Task; 
use App\Models\MRAttendance;
use App\Models\Sale;
use App\Models\Client;
use App\Models\TADARecords;
use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //Function for show dashboard
    public function dashboard() {
        //Get visitor
        $total_visits = Visit::where('mr_id', Auth::id())->count();
        //Get completed tasks
        $total_completed_task = Task::where('mr_id', Auth::id())->where('status', 'completed')->count();
        //Get attendances
        $total_attendances = MRAttendance::where('user_id', Auth::id())->count();
        //Get sales
        $total_sales = Sale::where('user_id', Auth::id())->count();

        //Get clients
        $is_approved = Client::where('mr_id', Auth::id())->where('status', 'Approved')->count();
        $is_pending = Client::where('mr_id', Auth::id())->where('status', 'Pending')->count();
        $is_reject = Client::where('mr_id', Auth::id())->where('status', 'Reject')->count();

        //Get tada record
        $bus = TADARecords::where('mr_id', Auth::id())->where('mode_of_travel', 'Bus')->count();
        $train = TADARecords::where('mr_id', Auth::id())->where('mode_of_travel', 'Train')->count();
        $flight = TADARecords::where('mr_id', Auth::id())->where('mode_of_travel', 'Flight')->count();
        $car = TADARecords::where('mr_id', Auth::id())->where('mode_of_travel', 'Car')->count();
        $bike = TADARecords::where('mr_id', Auth::id())->where('mode_of_travel', 'Bike')->count();

        //Get current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();
        //Get daily reports
        $DailyReport = DailyReport::where('mr_id', Auth::id())->selectRaw('DATE(report_date) as day, COUNT(*) as total')
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        //Get weeks
        $weeklyData = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $weeklyData[] = [
                'day'   => $date->toDateString(),
                'total' => $DailyReport->has($date->toDateString()) ? $DailyReport[$date->toDateString()]->total : 0,
            ];
        }

        //Get monthly visits
        $visits = Visit::where('mr_id', Auth::id())->selectRaw('MONTH(visit_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        //Get month
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'total' => $visits->has($m) ? $visits[$m] : 0,
            ];
        }
        
        return view('mr.dashboard', compact('total_visits','total_completed_task','total_attendances','total_sales','is_approved','is_pending','is_reject','bus','train','flight','car','bike','weeklyData','monthlyData'));
    }
}
