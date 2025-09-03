<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\MRAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $userId     = Auth::id();
        $today      = Carbon::today()->toDateString();
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
            ['check_in' => Carbon::now()->toTimeString(), 'status' => 'half']
        );

        // If record existed (already checked-in before), only update check_in if not set, and status if both times not available
        if (! $attendance->check_in) {
            $attendance->check_in = Carbon::now()->toTimeString();
            $attendance->status   = 'half';
            $attendance->save();
        }
        // If already checked in (and checked out, don't change status here)

        return back()->with('success', 'Check-in successful!');
    }

    public function checkOut()
    {
        $today = Carbon::today();

        $attendance = MRAttendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        if ($attendance && ! $attendance->check_out) {
            $attendance->check_out = Carbon::now()->toTimeString();

            if ($attendance->check_in) {
                // Calculate total working hours
                $checkIn  = Carbon::parse($attendance->check_in);
                $checkOut = Carbon::parse($attendance->check_out);

                $hoursWorked = $checkIn->diffInHours($checkOut);

                if ($hoursWorked >= 8) {
                    $attendance->status = 'present';
                } elseif ($hoursWorked >= 4) {
                    $attendance->status = 'half-day';
                } else {
                    $attendance->status = 'absent';
                }
            }

            $attendance->save();
        }

        return back()->with('success', 'Check-out successful!');
    }

    public function month()
    {
        $userId = Auth::id();
        $month  = now()->month;
        $year   = now()->year;

        $attendances = MRAttendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy('date');

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $days        = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date   = Carbon::create($year, $month, $day)->toDateString();
            $att    = $attendances[$date] ?? null;
            $days[] = [
                'date'      => $date,
                'check_in'  => $att->check_in ?? null,
                'check_out' => $att->check_out ?? null,
            ];
        }
        return view('mr.attendance.month', compact('days'));
    }

}
