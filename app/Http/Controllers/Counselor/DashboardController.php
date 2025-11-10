<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CounselorPatient; 
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $cId = Auth::id();
        $total_bookings = CounselorPatient::where('counselor_id', $cId)->count();

        $pending_bookings = CounselorPatient::where('counselor_id', $cId)
            ->where('booking_done', 'No')->count();

        $revenue = CounselorPatient::where('counselor_id', $cId)
            ->where('booking_done', 'Yes')->sum('booking_amount');

        $repeated_customers = CounselorPatient::where('counselor_id', $cId)
            ->whereNotNull('mobile_no')->where('mobile_no', '!=', '')
            ->select('mobile_no')->groupBy('mobile_no')
            ->havingRaw('COUNT(*) > 1')->count();
        return view('counselor.dashboard', compact('total_bookings', 'pending_bookings', 'revenue', 'repeated_customers'));
                
    }
}
