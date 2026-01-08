<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    //Function for privacy-policy
    public function privacy_policy() {
        return view('privacy-policy');
    }
}
