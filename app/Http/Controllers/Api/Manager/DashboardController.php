<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
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
    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse {
        //Check if auth login or not
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    }

    //Function for show dashboard data
    public function dashboard() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get mrs
        $mrIds = auth()->user()->mrs->pluck('id');
        //Get visits
        $total_visits = Visit::whereIn('mr_id', $mrIds)->count();
        //Get completed tasks
        $total_completed_task = Task::where('manager_id', Auth::id())
            ->where('status', 'completed')
            ->count();
        //Attendance
        $total_attendances = MRAttendance::whereIn('user_id', $mrIds)->count();
        //Sales
        $total_sales = Sale::where('manager_id', Auth::id())->count();
        //Clients status
        $is_approved = Client::where('manager_id', Auth::id())->where('status', 'Approved')->count();
        $is_pending  = Client::where('manager_id', Auth::id())->where('status', 'Pending')->count();
        $is_reject   = Client::where('manager_id', Auth::id())->where('status', 'Reject')->count();
        //TADA Mode
        $bus    = TADARecords::whereIn('mr_id', $mrIds)->where('mode_of_travel', 'Bus')->count();
        $train  = TADARecords::whereIn('mr_id', $mrIds)->where('mode_of_travel', 'Train')->count();
        $flight = TADARecords::whereIn('mr_id', $mrIds)->where('mode_of_travel', 'Flight')->count();
        $car    = TADARecords::whereIn('mr_id', $mrIds)->where('mode_of_travel', 'Car')->count();
        $bike   = TADARecords::whereIn('mr_id', $mrIds)->where('mode_of_travel', 'Bike')->count();
        //Weekly Reports
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $weeklyReports = DailyReport::where('manager_id', Auth::id())
            ->selectRaw('DATE(report_date) as day, COUNT(*) as total')
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $weeklyData = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $weeklyData[] = [
                'day'   => $date->toDateString(),
                'total' => $weeklyReports->has($date->toDateString()) 
                            ? $weeklyReports[$date->toDateString()]->total 
                            : 0,
            ];
        }

        //Monthly Visits Chart
        $visits = Visit::whereIn('mr_id', $mrIds)
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
        return response()->json([
            'status' => 200,
            'message' => 'Manager dashboard fetched successfully',
            'data' => [
                'total_visits'          => $total_visits,
                'total_completed_task'   => $total_completed_task,
                'total_attendances'      => $total_attendances,
                'total_sales'            => $total_sales,

                'clients' => [
                    'approved' => $is_approved,
                    'pending'  => $is_pending,
                    'reject'   => $is_reject,
                ],

                'tada' => [
                    'bus'    => $bus,
                    'train'  => $train,
                    'flight' => $flight,
                    'car'    => $car,
                    'bike'   => $bike,
                ],

                'weekly_reports' => $weeklyData,
                'monthly_visits' => $monthlyData
            ]
        ], 200);
    }
}
