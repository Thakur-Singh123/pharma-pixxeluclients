<?php

namespace App\Http\Controllers\Api\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\CounselorPatient;

class DashboardController extends Controller
{
    //Function Ensure the authenticated MR context exists
    private function ensureAuthenticated(): ?JsonResponse {
        //Get auth detail
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }
        return null;
    }

    //Function for show dashboard data
    public function dashboard() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get auth detail
        $cId = Auth::id();
        //Check patient found or not
        if (!$cId) {
            return response()->json([
                'status' => 404,
                'message' => 'Dashboard not found'
            ], 404);
        }
        //Total bookings
        $total_bookings = CounselorPatient::where('counselor_id', $cId)->count();
        //Pending bookings
        $pending_bookings = CounselorPatient::where('counselor_id', $cId)
            ->where('booking_done', 'No')->count();
        //Revenue from completed bookings
        $revenue = CounselorPatient::where('counselor_id', $cId)
            ->where('booking_done', 'Yes')->sum('booking_amount');
        //Repeated customers
        $repeated_customers = CounselorPatient::where('counselor_id', $cId)
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->select('mobile_no')
            ->groupBy('mobile_no')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Dashboard data fetched successfully',
            'data' => [
                'total_bookings'      => $total_bookings,
                'pending_bookings'    => $pending_bookings,
                'revenue'             => $revenue,
                'repeated_customers'  => $repeated_customers,
            ]
        ], 200);
    }
}
