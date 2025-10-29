<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\BaseController;
use App\Models\MRAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends BaseController
{
    //Functino for get Today’s Attendances
    public function index() {
        //Get auth login detail
        $userId = Auth::id();
        //Check if user exists or not
        if (!$userId) {
            $error['status'] = 401;
            $error['message'] = "Unauthorized access. Please login first.";
            return response()->json($error, 401);
        }
        //Get current day 
        $today = Carbon::today()->toDateString();
        $attendance = MRAttendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();
        //Check if attendance found or not
        if (!$attendance) {
            $error['status'] = 404;
            $error['message'] = "No attendance record found for today.";
            return response()->json($error, 404);
        }
        //Success response
        $success['status'] = 200;
        $success['message'] = "Today's attendance get successfully.";
        $success['data'] = $attendance;
        return response()->json($success, 200);
    }

    //Function for check-In attendance
    public function checkIn() {
        //Get auth login detail
        $userId = Auth::id();
        //check if auth exists or not
        if (!$userId) {
            $error['status'] = 401;
            $error['message'] = "Unauthorized access.";
            return response()->json($error, 401);
        }
        //Get current day
        $today = Carbon::today();
        $attendance = MRAttendance::firstOrCreate(
            ['user_id' => $userId, 'date' => $today],
            ['check_in' => Carbon::now()->toTimeString(), 'status' => 'half']
        );
        //Check in attendance check in or not
        if (!$attendance->check_in) {
            $attendance->check_in = Carbon::now()->toTimeString();
            $attendance->status = 'half';
            $attendance->save();
        }
        //Success response
        $success['status'] = 200;
        $success['message'] = "Check-in successful.";
        $success['data'] = $attendance;
        return response()->json($success, 200);
    }

    //Function for Check-Out attendance
    public function checkOut() {
        //Get auth login detail
        $userId = Auth::id();
        if (!$userId) {
            $error['status'] = 401;
            $error['message'] = "Unauthorized access.";
            return response()->json($error, 401);
        }
        //Get current day
        $today = Carbon::today();
        $attendance = MRAttendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
        //Check if attendance check in or not
        if (!$attendance) {
            $error['status'] = 404;
            $error['message'] = "No check-in found for today.";
            return response()->json($error, 404);
        }
        //Check if attendance check out or not
        if (!$attendance->check_out) {
            $attendance->check_out = Carbon::now()->toTimeString();
            //Check if attendance check in
            if ($attendance->check_in) {
                $checkIn = Carbon::parse($attendance->check_in);
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
            //Success reponse
            $success['status'] = 200;
            $success['message'] = "Check-out successful.";
            $success['data'] = $attendance;
            return response()->json($success, 200);
        }
        //Error response
        $error['status'] = 409;
        $error['message'] = "Already checked out for today.";
        return response()->json($error, 409);
    }

    //function for get monthly attendances
    public function month() {
        //Get auth login detail
        $userId = Auth::id();
        if (!$userId) {
            $error['status'] = 401;
            $error['message'] = "Unauthorized access.";
            return response()->json($error, 401);
        }
        //Get month and year
        $month = now()->month;
        $year = now()->year;
        //Get attendances
        $attendances = MRAttendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy('date');

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $days = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->toDateString();
            $att = $attendances[$date] ?? null;
            $days[] = [
                'date' => $date,
                'check_in' => $att->check_in ?? null,
                'check_out' => $att->check_out ?? null,
                'status' => $att->status ?? 'absent',
            ];
        }
        //Success response
        $success['status'] = 200;
        $success['message'] = "Monthly attendance get successfully.";
        $success['data'] = $days;
        return response()->json($success, 200);
    }
}
