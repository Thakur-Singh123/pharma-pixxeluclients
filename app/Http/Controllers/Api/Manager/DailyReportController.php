<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DailyReportController extends Controller
{
    // Show all daily reports
    public function index(Request $request)
    {
        $auth_login = Auth::id();

        $query = DailyReport::with('mr_details', 'report_details.doctor')
            ->where('manager_id', $auth_login);

        $filter = $request->get('filter_by') ?? 'all';

        if ($filter === 'today') {
            $query->whereDate('report_date', now());
        } elseif ($filter === 'week') {
            $query->whereBetween('report_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('report_date', now()->month);
        } elseif ($filter === 'year') {
            $query->whereYear('report_date', now()->year);
        }

        $reports = $query->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Daily Reports Fetched Successfully',
            'data' => $reports
        ]);
    }

    // Approve report
    public function report_approve($id)
    {
        $report = DailyReport::find($id);

        if (!$report) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found',
            ], 404);
        }

        $report->status = 'Approved';
        $report->approved_by = '1';
        $report->save();

        return response()->json([
            'status' => true,
            'message' => 'Report approved successfully',
            'data' => $report
        ]);
    }

    // Reject report
    public function report_reject($id)
    {
        $report = DailyReport::find($id);

        if (!$report) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found',
            ], 404);
        }

        $report->status = 'Reject';
        $report->approved_by = '0';
        $report->save();

        return response()->json([
            'status' => true,
            'message' => 'Report rejected successfully',
            'data' => $report
        ]);
    }

    // Edit report (Fetch for edit)
    public function edit($id)
    {
        $report_detail = DailyReport::with('report_details')->find($id);

        if (!$report_detail) {
            return response()->json([
                'status' => false,
                'message' => 'Report not found',
            ], 404);
        }

        $auth_login = Auth::id();
        $assignedDoctors = Doctor::where('user_id', $auth_login)
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Edit data fetched successfully',
            'report_detail' => $report_detail,
            'assigned_doctors' => $assignedDoctors
        ]);
    }

    // Update daily report
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date',
            'doctor_id' => 'required|array',
            'area_name' => 'required|array',
            'total_visits' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $update = DailyReport::where('id', $id)->update([
            'report_date' => $request->report_date,
        ]);

        if ($update) {
            // delete old report details
            DailyReportDetail::where('report_id', $id)->delete();

            foreach ($request->doctor_id as $index => $doctor_id) {
                DailyReportDetail::create([
                    'report_id' => $id,
                    'doctor_id' => $doctor_id,
                    'area_name' => $request->area_name[$index],
                    'total_visits' => $request->total_visits[$index],
                    'patients_referred' => $request->patients_referred[$index] ?? 0,
                    'notes' => $request->notes[$index] ?? null,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Daily report updated successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to update daily report'
        ], 500);
    }

}
