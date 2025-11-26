<?php

namespace App\Http\Controllers\Api\MR;

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
        //Get auth login detail
        $mr_id = Auth::id();
        //Get visits
        $total_visits = Visit::where('mr_id', $mr_id)->count();
        //Get completed tasks
        $total_completed_task  = Task::where('mr_id', $mr_id)->where('status', 'completed')->count();
        //Get attendances
        $total_attendances = MRAttendance::where('user_id', $mr_id)->count();
        //Get sales
        $total_sales = Sale::where('user_id', $mr_id)->count();
        //Get clients
        $is_approved = Client::where('mr_id', $mr_id)->where('status', 'Approved')->count();
        $is_pending  = Client::where('mr_id', $mr_id)->where('status', 'Pending')->count();
        $is_reject   = Client::where('mr_id', $mr_id)->where('status', 'Reject')->count();
        //TADA records
        $bus = TADARecords::where('mr_id', $mr_id)->where('mode_of_travel', 'Bus')->count();
        $train = TADARecords::where('mr_id', $mr_id)->where('mode_of_travel', 'Train')->count();
        $flight = TADARecords::where('mr_id', $mr_id)->where('mode_of_travel', 'Flight')->count();
        $car = TADARecords::where('mr_id', $mr_id)->where('mode_of_travel', 'Car')->count();
        $bike = TADARecords::where('mr_id', $mr_id)->where('mode_of_travel', 'Bike')->count();
        //Get week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();
        //Daily report
        $dailyReports = DailyReport::where('mr_id', $mr_id)
            ->selectRaw('DATE(report_date) as day, COUNT(*) as total')
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get()
            ->keyBy('day');
        //Weekly report
        $weeklyData = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $weeklyData[] = [
                'day'   => $date->toDateString(),
                'total' => $dailyReports->has($date->toDateString())
                    ? $dailyReports[$date->toDateString()]->total
                    : 0,
            ];
        }
        //Monthly visit
        $visits = Visit::where('mr_id', $mr_id)
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
            'message' => 'MR dashboard fetched successfully.',
            'data' => [
                'total_visits'          => $total_visits,
                'total_completed_task'  => $total_completed_task,
                'total_attendances'     => $total_attendances,
                'total_sales'           => $total_sales,

                'clients' => [
                    'approved' => $is_approved,
                    'pending'  => $is_pending,
                    'reject'   => $is_reject,
                ],

                'tada_records' => [
                    'bus'    => $bus,
                    'train'  => $train,
                    'flight' => $flight,
                    'car'    => $car,
                    'bike'   => $bike,
                ],

                'weekly_reports' => $weeklyData,
                'monthly_visits' => $monthlyData,
            ]
        ], 200);
    }
}
