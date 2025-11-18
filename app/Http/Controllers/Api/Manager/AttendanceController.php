<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\MRAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades/Auth;

class AttendanceController extends Controller
{
    public function index(Request $request, ?string $type = null)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $userId = Auth::id();

        $manager = User::with('mrs')->find($userId);

        if (!$manager) {
            return response()->json([
                'status'  => 404,
                'message' => 'Manager not found.',
            ], 404);
        }

        $mrs          = $manager->mrs;
        $resolvedType = $this->normalizeType($type ?? $request->query('type'));

        if (!$resolvedType) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid type. Allowed: daily, monthly.',
            ], 422);
        }

        // DAILY
        if ($resolvedType === 'daily') {
            $dateInput = $request->query('date');

            try {
                $date = $dateInput ? Carbon::parse($dateInput) : Carbon::today();
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Invalid date format. Use YYYY-MM-DD.',
                ], 422);
            }

            $payload = $this->dailyPayload($mrs, $date);

            return response()->json([
                'status'  => 200,
                'message' => 'Daily attendance retrieved.',
                'data'    => $payload,
            ]);
        }

        // MONTHLY
        $month = (int) $request->query('month', now()->month);
        $year  = (int) $request->query('year', now()->year);

        if ($month < 1 || $month > 12) {
            return response()->json([
                'status'  => 422,
                'message' => 'Month must be between 1–12.',
            ], 422);
        }

        if (!is_numeric($year)) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid year.',
            ], 422);
        }

        try {
            Carbon::create($year, $month, 1);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid month/year.',
            ], 422);
        }

        $payload = $this->monthlyPayload($mrs, $month, $year);

        return response()->json([
            'status'  => 200,
            'message' => 'Monthly attendance retrieved.',
            'data'    => $payload,
        ]);
    }

    // ----------------------------------------------------------
    // TYPE NORMALIZER
    // ----------------------------------------------------------

    private function normalizeType(?string $type): ?string
    {
        if (!$type) return 'daily';

        $t = strtolower(str_replace(['-', '_', ' '], '', $type));

        return match ($t) {
            'daily', 'day', 'today' => 'daily',
            'monthly', 'month'      => 'monthly',
            default                 => null,
        };
    }

    // ----------------------------------------------------------
    // DAILY PAYLOAD
    // ----------------------------------------------------------

    private function dailyPayload($mrs, Carbon $date): array
    {
        $mrIds      = $mrs->pluck('id')->all();
        $dateString = $date->toDateString();

        $attendances = empty($mrIds)
            ? collect()
            : MRAttendance::whereIn('user_id', $mrIds)
                ->whereDate('date', $dateString)
                ->get()
                ->keyBy('user_id');

        $records = $mrs->map(function ($mr) use ($attendances, $dateString) {
            $attendance = $attendances->get($mr->id);

            return [
                'mr'        => $this->formatMr($mr),
                'attendance' => [
                    'date'        => $dateString,
                    'status'      => $attendance->status ?? 'absent',
                    'check_in'    => $attendance->check_in ?? null,
                    'check_out'   => $attendance->check_out ?? null,
                    'is_recorded' => (bool) $attendance,
                ],
            ];
        })->values()->all();

        return [
            'type'    => 'daily',
            'filters' => ['date' => $dateString],
            'records' => $records,
        ];
    }

    // ----------------------------------------------------------
    // MONTHLY PAYLOAD
    // ----------------------------------------------------------

    private function monthlyPayload($mrs, int $month, int $year): array
    {
        $mrIds = $mrs->pluck('id')->all();

        $attendanceList = empty($mrIds)
            ? collect()
            : MRAttendance::whereIn('user_id', $mrIds)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();

        // Indexing by MR + DATE
        $index = [];
        foreach ($attendanceList as $att) {
            $date = Carbon::parse($att->date)->toDateString();
            $index[$att->user_id][$date] = $att;
        }

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        $records = $mrs->map(function ($mr) use ($index, $daysInMonth, $month, $year) {
            $days = [];
            $summary = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date       = Carbon::create($year, $month, $day)->toDateString();
                $attendance = $index[$mr->id][$date] ?? null;
                $status     = $attendance->status ?? 'absent';

                $summary[$status] = ($summary[$status] ?? 0) + 1;

                $days[] = [
                    'date'        => $date,
                    'status'      => $status,
                    'check_in'    => $attendance->check_in ?? null,
                    'check_out'   => $attendance->check_out ?? null,
                    'is_recorded' => (bool) $attendance,
                ];
            }

            ksort($summary);

            return [
                'mr' => $this->formatMr($mr),
                'attendance' => [
                    'days'    => $days,
                    'summary' => array_merge(['total_days' => $daysInMonth], $summary),
                ],
            ];
        })->values()->all();

        return [
            'type'    => 'monthly',
            'filters' => ['month' => $month, 'year' => $year],
            'records' => $records,
        ];
    }

    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data'    => null,
            ], 401);
        }

        return null;
    }

    // ----------------------------------------------------------
    // FORMAT MR DATA
    // ----------------------------------------------------------

    private function formatMr($mr): array
    {
        return [
            'id'            => $mr->id,
            'name'          => $mr->name,
            'employee_code' => $mr->employee_code,
            'territory'     => $mr->territory,
            'city'          => $mr->city,
            'state'         => $mr->state,
        ];
    }
}
