<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\TADARecords;
use App\Models\Visit;
use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //Function for show dashboard 
    public function dashboard() {
        //Get auth login detail
        $auth_login = auth()->user()->mrs->pluck('id');
        //Get clients
        $is_approved = Client::where('manager_id', Auth::id())->where('status', 'Approved')->count();
        $is_pending = Client::where('manager_id', Auth::id())->where('status', 'Pending')->count();
        $is_reject = Client::where('manager_id', Auth::id())->where('status', 'Reject')->count();
        //Get tada record
        $bus = TADARecords::whereIn('mr_id', $auth_login)->where('mode_of_travel', 'Bus')->count();
        $train = TADARecords::whereIn('mr_id', $auth_login)->where('mode_of_travel', 'Train')->count();
        $flight = TADARecords::whereIn('mr_id', $auth_login)->where('mode_of_travel', 'Flight')->count();
        $car = TADARecords::whereIn('mr_id', $auth_login)->where('mode_of_travel', 'Car')->count();
        $bike = TADARecords::whereIn('mr_id', $auth_login)->where('mode_of_travel', 'Bike')->count();
        //Get current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();
        //Get daily reports
        $DailyReport = DailyReport::where('manager_id', Auth::id())->selectRaw('DATE(report_date) as day, COUNT(*) as total')
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
        $visits = Visit::whereIn('mr_id', $auth_login)->selectRaw('MONTH(visit_date) as month, COUNT(*) as total')
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
        
        return view('manager.dashboard', compact('is_approved','is_pending','is_reject','bus','train','flight','car','bike','weeklyData','monthlyData'));
    }
}
