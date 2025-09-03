<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;

class VisitController extends Controller
{
    //Function for show all daily visits
    public function index() {
        //Get visits
        $mrs = auth()->user()->mrs->pluck('id');
        //Get visits
        $all_visits = Visit::whereIn('mr_id',$mrs)->with('mr')->OrderBy('ID','DESC')->paginate(5);
        return view('manager.daily-visits.all-visits', compact('all_visits'));
    }
}
