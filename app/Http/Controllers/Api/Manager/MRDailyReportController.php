<?php

namespace App\Http\Controllers\Api\Manager;

use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MRDailyReportController extends Controller
{
    private const ALLOWED_FILTERS = ['all', 'today', 'week', 'month', 'year'];

    /**
     * Ensure the user is authenticated via Sanctum.
     */
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

    /**
     * Resolve per page value with sane defaults.
     */
    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        return min($perPage, 100);
    }

    /**
     * Resolve filter value and validate against allowed list.
     *
     * @return array{0: string|null, 1: JsonResponse|null}
     */
    private function resolveFilter(?string $filter): array
    {
        $value = $filter !== null && $filter !== ''
            ? strtolower($filter)
            : 'all';

        if (!in_array($value, self::ALLOWED_FILTERS, true)) {
            return [
                null,
                response()->json([
                    'status'  => 422,
                    'message' => 'Invalid filter supplied. Allowed values: ' . implode(', ', self::ALLOWED_FILTERS) . '.',
                    'data'    => null,
                ], 422),
            ];
        }

        return [$value, null];
    }

    /**
     * Apply filter on report_date column.
     */
    private function applyDateFilter(Request $request, Builder $query): ?JsonResponse
    {
        [$filter, $error] = $this->resolveFilter($request->query('filter_by'));

        if ($error) {
            return $error;
        }

        switch ($filter) {
            case 'today':
                $query->whereDate('report_date', now());
                break;
            case 'week':
                $query->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('report_date', now()->month)
                    ->whereYear('report_date', now()->year);
                break;
            case 'year':
                $query->whereYear('report_date', now()->year);
                break;
            case 'all':
            default:
                // No additional constraints
                break;
        }

        return null;
    }

    /**
     * Base query for manager specific reports.
     */
    private function reportQuery(): Builder
    {
        return DailyReport::where('manager_id', Auth::id())
            ->with(['mr_details', 'report_details.doctor'])
            ->orderByDesc('id');
    }

    /**
     * Find a single report for the authenticated manager.
     */
    private function findReportForManager(int $id): ?DailyReport
    {
        return DailyReport::where('id', $id)
            ->where('manager_id', Auth::id())
            ->with(['mr_details', 'report_details.doctor'])
            ->first();
    }

    /**
     * List daily reports with optional filter.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->reportQuery();

        if ($response = $this->applyDateFilter($request, $query)) {
            return $response;
        }

        $reports = $query->simplePaginate($this->resolvePerPage($request));
        $message = $reports->count()
            ? 'Daily reports fetched successfully.'
            : 'No daily reports found.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $reports,
        ], 200);
    }

    /**
     * Update report meta and details.
     */
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate inputs
        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        //Get report
        $report = DailyReport::find($id);

        if (!$report) {
            return response()->json([
                'status' => 404,
                'message' => 'Daily report not found.',
                'data' => null
            ], 404);
        }

        //Update report
        $report->update([
            'report_date' => $request->report_date,
        ]);

        //Remove old details
        DailyReportDetail::where('report_id', $id)->delete();

        //Insert new details
        foreach ($request->doctor_id as $i => $doctor_id) {
            DailyReportDetail::create([
                'report_id' => $id,
                'doctor_id' => $doctor_id,
                'area_name' => $request->area_name[$i],
                'total_visits' => $request->total_visits[$i],
                'patients_referred' => $request->patients_referred[$i] ?? 0,
                'notes' => $request->notes[$i] ?? null,
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Daily report updated successfully.',
            'data' => $report
        ], 200);
    }

    /**
     * Approve a report.
     */
    public function approve($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $report = $this->findReportForManager((int) $id);

        if (!$report) {
            return response()->json([
                'status'  => 404,
                'message' => 'Report not found.',
                'data'    => null,
            ], 404);
        }

        $report->status = 'Approved';
        $report->approved_by = '1';
        $report->save();

        $report->refresh()->load(['mr_details', 'report_details.doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Report approved successfully.',
            'data'    => $report,
        ], 200);
    }

    /**
     * Reject a report.
     */
    public function reject($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $report = $this->findReportForManager((int) $id);

        if (!$report) {
            return response()->json([
                'status'  => 404,
                'message' => 'Report not found.',
                'data'    => null,
            ], 404);
        }

        $report->status = 'Reject';
        $report->approved_by = '0';
        $report->save();

        $report->refresh()->load(['mr_details', 'report_details.doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Report rejected successfully.',
            'data'    => $report,
        ], 200);
    }

    /**
     * Export reports as Excel for the authenticated manager.
     */
    public function export(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        [$filter, $error] = $this->resolveFilter($request->query('filter_by'));

        if ($error) {
            return $error;
        }

        return Excel::download(new ReportsExport($filter, Auth::id()), 'daily_reports.xlsx');
    }
}
