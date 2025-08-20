<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MRAttendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();
        $attendance = MRAttendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        return view('mr.attendance.index', compact('attendance'));
    }

    public function checkIn()
    {
        $today = Carbon::today();

        $attendance = MRAttendance::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => $today],
            ['check_in' => Carbon::now()->toTimeString()]
        );

        return back()->with('success', 'Check-in successful!');
    }

    public function checkOut()
    {
        $today = Carbon::today();

        $attendance = MRAttendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        if ($attendance && !$attendance->check_out) {
            $attendance->update(['check_out' => Carbon::now()->toTimeString()]);
        }

        return back()->with('success', 'Check-out successful!');
    }

    public function month()
{
    $userId = auth()->id();
    $month = now()->month;
    $year  = now()->year;

    // MR ki is month ki attendance
    $attendances = MRAttendance::where('user_id', $userId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get()
        ->keyBy(function($item){
            return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
        });

    // Pure month ke days create karna
    $start = \Carbon\Carbon::createFromDate($year, $month, 1);
    $end   = $start->copy()->endOfMonth();
    $days = [];

    for($date = $start; $date->lte($end); $date->addDay()){
        $formatted = $date->format('Y-m-d');
        $days[] = [
            'date'      => $formatted,
            'check_in'  => $attendances[$formatted]->check_in ?? null,
            'check_out' => $attendances[$formatted]->check_out ?? null,
        ];
    }

    return view('mr.attendance.month', compact('days'));
}

}
