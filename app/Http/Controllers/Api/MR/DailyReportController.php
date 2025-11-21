<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\MangerMR;
use App\Models\DailyReport;
use App\Models\DailyReportDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    
    //Function for ensure authenticated
    private function ensureAuthenticated(): ?JsonResponse {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    }
    
    //Function for all reports
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //all reports
        $reports = DailyReport::where('mr_id', Auth::id())
            ->with('report_details')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        if ($reports->total() == 0) {
            return response()->json([
                'status' => 200,
                'message' => 'No daily reports found.',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Daily reports fetched successfully.',
            'data' => $reports
        ]);
    }

    //Function for submit daily report
    public function store(Request $request) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validate input fields
        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date',
            'doctor_id' => 'required|array',
            'area_name' => 'required|array',
            'total_visits' => 'required|array',
        ]);
        //response
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }
        //Get manager
        $manager_id = MangerMR::where('mr_id', Auth::id())->value('manager_id');
        //Create report
        $report = DailyReport::create([
            'mr_id' => Auth::id(),
            'manager_id' => $manager_id,
            'report_date' => $request->report_date,
            'staus' => 'Pending',
        ]);
        //create deaily report detail
        foreach ($request->doctor_id as $i => $doctor_id) {
            DailyReportDetail::create([
                'report_id' => $report->id,
                'doctor_id' => $doctor_id,
                'area_name' => $request->area_name[$i],
                'total_visits' => $request->total_visits[$i],
                'patients_referred' => $request->patients_referred[$i] ?? 0,
                'notes' => $request->notes[$i] ?? null,
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Daily report created successfully.',
            'data' => $report
        ]);
    }

    //Function for update report
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

    //Function for delete report
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get report detail
        $report = DailyReport::find($id);
        //response
        if (!$report) {
            return response()->json([
                'status' => 404,
                'message' => 'Daily report not found.',
                'data' => null
            ]);
        }
        //delete report
        $report->delete();
        DailyReportDetail::where('report_id', $id)->delete();
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Daily report deleted successfully.',
            'data' => null
        ]);
    }
}
