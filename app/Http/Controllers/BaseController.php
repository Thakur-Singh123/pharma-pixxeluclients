<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AuthHelper;

class BaseController extends Controller
{
    //Function for constturct token
    public function __construct(Request $request) {
        //Get auth detail
        $auth = AuthHelper::checkAuthToken($request);
        //Check if auth exists or not
        if ($auth !== true) {
            response()->json($auth->original, $auth->getStatusCode())->send();
            exit;
        }
    }
}
