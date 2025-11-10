<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Events;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        //Get auth detail
        $user_detail = Auth::user();
        //Check if user type admin or not
        if ($user_detail->user_type == 'Admin') {
            return redirect('admin/dashboard');
        //Check if user type manager or not
        } elseif ($user_detail->user_type == 'Manager') {
            return redirect('manager/dashboard');
        //Check if user type mr or not
        } elseif ($user_detail->user_type == 'MR' && $user_detail->can_sale == 0) {
            return redirect('mr/dashboard');
        } elseif ($user_detail->user_type == 'MR' && $user_detail->can_sale == 1) {
            return redirect('mr/sales');
        } elseif ($user_detail->user_type == 'vendor') {
            return redirect('vendor/dashboard');
        } elseif ($user_detail->user_type == 'purchase_manager') {
            return redirect('purchase-manager/dashboard');
        } elseif ($user_detail->user_type == 'counsellor') {
            return redirect('counselor/dashboard');
        } else {
            return view('home');
        }
    }
}
