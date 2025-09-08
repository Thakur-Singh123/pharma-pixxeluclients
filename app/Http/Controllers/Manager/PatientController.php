<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferredPatient;

class PatientController extends Controller
{
    //Function for show all patients
    public function index() {
        //Get patients
        $mrs = auth()->user()->mrs->pluck('id');
        $all_patients = ReferredPatient::whereIn('mr_id',$mrs)->with('mr')->OrderBy('ID','DESC')->paginate(5);
        return view('manager.patients.all-patients', compact('all_patients'));
    }
}