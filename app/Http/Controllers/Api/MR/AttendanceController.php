<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\MRAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Retrieve attendance details (daily or monthly) for the authenticated MR.
     */
    public function index(Request $request, ?string $type = null)
    {
        $userId = Auth::id();

        if (!$userId) {
            $error['status'] = 401;
            $error['message'] = "Unauthorized access. Please login first.";
            return response()->json($error, 401);
        }

        $typeInput = strtolower(str_replace(['-', '_', ' '], '', $type ?? $request->input('type', 'daily')));
        $type = match ($typeInput) {
            'daily', 'today', 'day' => 'daily',
            'monthly', 'month' => 'monthly',
            default => null,
        };

        if (!$type) {
            $error['status'] = 422;
            $error['message'] = "Invalid attendance type provided. Allowed values: daily, monthly.";
            return response()->json($error, 422);
        }

        if ($type === 'daily') {
            $dateInput = $request->query('date');

            try {
                $date = $dateInput ? Carbon::parse($dateInput) : Carbon::today();
            } catch (\Exception $exception) {
                $error['status'] = 422;
                $error['message'] = "Invalid date format. Please use a valid ISO date (YYYY-MM-DD).";
                return response()->json($error, 422);
            }

            $payload = $this->buildDailyAttendancePayload($userId, $date);
            $message = "Daily attendance retrieved successfully.";
        } else {
            $month = $request->query('month', now()->month);
            $year = $request->query('year', now()->year);

            if (!filter_var($month, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 12]])) {
                $error['status'] = 422;
                $error['message'] = "Invalid month provided. Month must be between 1 and 12.";
                return response()->json($error, 422);
            }

            if (!filter_var($year, FILTER_VALIDATE_INT)) {
                $error['status'] = 422;
                $error['message'] = "Invalid year provided.";
                return response()->json($error, 422);
            }

            try {
                $month = (int) $month;
                $year = (int) $year;
                $payload = $this->buildMonthlyAttendancePayload($userId, $month, $year);
            } catch (\Exception $exception) {
                $error['status'] = 422;
                $error['message'] = "Invalid month/year combination provided.";
                return response()->json($error, 422);
            }

            $message = "Monthly attendance retrieved successfully.";
        }

        $success['status'] = 200;
        $success['message'] = $message;
        $success['data'] = $payload;

        return response()->json($success, 200);
    }

    //Function for handle check-in and check-out attendance
    public function mark(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $type = strtolower(str_replace(['-', '_'], '', $request->input('type')));
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $today = Carbon::today()->toDateString();
        $attendance = MRAttendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        /*CHECK-IN*/
        if ($type === 'checkin') {

            $attendance = MRAttendance::firstOrCreate(
                ['user_id' => $userId, 'date' => $today],
                ['check_in' => Carbon::now()->toTimeString(), 'status' => 'present']
            );

            if (!$attendance->check_in) {
                $attendance->check_in = Carbon::now()->toTimeString();
                $attendance->status   = 'present';
                $attendance->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Check-in successful.',
                'data' => $attendance->fresh(),
            ]);
        }

        /*CHECK-OUT*/
        if ($type === 'checkout') {

            if (!$attendance || !$attendance->check_in) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No check-in found for today.'
                ], 404);
            }

            if ($attendance->check_out) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Already checked out for today.'
                ], 409);
            }

            $attendance->check_out = Carbon::now()->toTimeString();

            $checkIn  = Carbon::parse($attendance->check_in);
            $checkOut = Carbon::parse($attendance->check_out);
            $hoursWorked = $checkIn->diffInHours($checkOut);

            if ($hoursWorked >= 10) {
                $attendance->status = 'present';
            } elseif ($hoursWorked >= 5) {
                $attendance->status = 'half';
            } elseif ($hoursWorked >= 2) {
                $attendance->status = 'short_leave';
            } else {
                $attendance->status = 'absent';
            }

            $attendance->save();

            return response()->json([
                'status' => 200,
                'message' => 'Check-out successful.',
                'data' => $attendance,
            ]);
        }

        return response()->json([
            'status' => 422,
            'message' => 'Invalid attendance type.'
        ], 422);
    }

    private function buildMonthlyAttendancePayload(int $userId, int $month, int $year): array
    {
        $attendances = MRAttendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn ($attendance) => Carbon::parse($attendance->date)->toDateString());

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $records = [];
        $summary = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->toDateString();
            $attendance = $attendances[$date] ?? null;
            $status = $attendance?->status ?? 'absent';

            $records[] = [
                'date' => $date,
                'check_in' => $attendance?->check_in,
                'check_out' => $attendance?->check_out,
                'status' => $status,
                'is_recorded' => (bool) $attendance,
            ];

            $summary[$status] = ($summary[$status] ?? 0) + 1;
        }

        ksort($summary);

        return [
            'type' => 'monthly',
            'filters' => [
                'month' => $month,
                'year' => $year,
            ],
            'records' => $records,
            'summary' => array_merge(
                ['total_days' => $daysInMonth],
                $summary
            ),
        ];
    }
}
