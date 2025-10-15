<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $start = Carbon::now()->startOfWeek();
        $end   = Carbon::now()->endOfWeek();

        $DailyReport = DailyReport::selectRaw('DATE(report_date) as day, COUNT(*) as total')
            ->whereBetween('report_date', [$start, $end])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $days = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $days[] = [
                'day'   => $date->toDateString(),
                'total' => $DailyReport->has($date->toDateString()) ? $DailyReport[$date->toDateString()]->total : 0
            ];
        }

        return view('mr.dashboard', ['DailyReport' => $days]);
    }
}
