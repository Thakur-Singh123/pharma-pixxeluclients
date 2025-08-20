<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //function for manager dashboard view
    public function dashboard(){
        return view('mr.dashboard');
    }
}
