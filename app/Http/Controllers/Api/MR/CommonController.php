<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        return null;
    }
    //function for mr doctor listing
    public function mr_doctor_listing(Request $request)
    {
        if($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $mr = $request->user();
        $doctors = $mr->doctors()->orderBy('ID', 'DESC')->get();
        return response()->json([
            'status' => 'success',
            'data' => $doctors
        ]);
    }
}
