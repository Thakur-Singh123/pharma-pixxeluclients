<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MRAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    //function to show attendance dashboard
    public function index()
    {
        $month = now()->month;
        $year  = now()->year;
        $daysInMonth = now()->daysInMonth;

        $mrs = auth()->user()->mrs;
        
        // All attendance for this month
        $attendanceRecords = MRAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $attendanceSummary = [];

        foreach ($mrs as $mr) {
            $row = [
                'employee' => $mr,
                'days' => [],
                'present' => 0,
                'half' => 0,
                'leave' => 0,
                'absent' => 0
            ];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                $attendance = $attendanceRecords->where('user_id', $mr->id)->where('date', $date)->first();
                $status = $attendance ? $attendance->status : 'absent';
                $row['days'][$d] = $status;
                if ($status == 'present') $row['present']++;
                else if ($status == 'half') $row['half']++;
                else if ($status == 'leave') $row['leave']++;
                else $row['absent']++;
            }
            $attendanceSummary[] = $row;
        }

        return view('manager.attendence.index', [
            'attendanceSummary' => $attendanceSummary,
            'daysInMonth' => $daysInMonth,
            'month' => $month,
            'year'  => $year
        ]);
    }

    //function to show attendance daily
    public function daily_attendance()
    {
        $mrIds = auth()->user()->mrs->pluck('id');
       $users = User::whereIn('id', $mrIds)
        ->with(['attendance' => function ($q) {
            $q->whereDate('date', today());
        }])
        ->get()
        ->toArray(); 

    return view('manager.attendence.daily-attendance', [
        'attendances' => $users
    ]);
    }
}
