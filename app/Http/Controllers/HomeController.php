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
        } elseif ($user_detail->user_type == 'MR') {
            return redirect('mr/dashboard');
        } else {
            return view('home');
        }
    }
}
