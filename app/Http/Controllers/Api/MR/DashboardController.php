<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\BaseController;
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

class DashboardController extends BaseController
{
    //Function for show dashboard
    public function dashboard() {
        //Get auth login detail
        $userId = Auth::id();
        //Visits
        $total_visits = Visit::where('mr_id', $userId)->count();
        $total_completed_task = Task::where('mr_id', $userId)->where('status', 'completed')->count();
        $total_attendances = MRAttendance::where('user_id', $userId)->count();
        $total_sales = Sale::where('user_id', $userId)->count();
        //Clients
        $is_approved = Client::where('mr_id', $userId)->where('status', 'Approved')->count();
        $is_pending  = Client::where('mr_id', $userId)->where('status', 'Pending')->count();
        $is_reject   = Client::where('mr_id', $userId)->where('status', 'Reject')->count();
        //TADAs
        $tada_modes = [
            'Bus'    => TADARecords::where('mr_id', $userId)->where('mode_of_travel', 'Bus')->count(),
            'Train'  => TADARecords::where('mr_id', $userId)->where('mode_of_travel', 'Train')->count(),
            'Flight' => TADARecords::where('mr_id', $userId)->where('mode_of_travel', 'Flight')->count(),
            'Car'    => TADARecords::where('mr_id', $userId)->where('mode_of_travel', 'Car')->count(),
            'Bike'   => TADARecords::where('mr_id', $userId)->where('mode_of_travel', 'Bike')->count(),
        ];
        //Weekly reports
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();
        //Get reports
        $dailyReports = DailyReport::where('mr_id', $userId)
            ->selectRaw('DATE(report_date) as day, COUNT(*) as total')
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->keyBy('day');
        $weeklyData = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $weeklyData[] = [
                'day'   => $date->format('Y-m-d'),
                'total' => $dailyReports->has($date->toDateString()) ? $dailyReports[$date->toDateString()]->total : 0,
            ];
        }
        //Monthly Visits
        $visits = Visit::where('mr_id', $userId)
            ->selectRaw('MONTH(visit_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'total' => $visits->has($m) ? $visits[$m] : 0,
            ];
        }
        //Response
        $success['status'] = 200;
        $success['message'] = "Dashboard data get successfully.";
        $success['data'] = [
            'total_visits' => $total_visits,
            'total_completed_task' => $total_completed_task,
            'total_attendances' => $total_attendances,
            'total_sales' => $total_sales,
            'clients' => [
                'approved' => $is_approved,
                'pending'  => $is_pending,
                'rejected' => $is_reject,
            ],
            'tada_modes' => $tada_modes,
            'weekly_data'  => $weeklyData,
            'monthly_data' => $monthlyData,
        ];

        return response()->json($success, 200);
    }
}
