<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //function for manager dashboard view
    public function dashboard(){
        return view('manager.dashboard');
    }
}
