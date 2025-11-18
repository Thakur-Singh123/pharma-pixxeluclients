<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    /**
     * Ensure the user is authenticated.
     */
    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data'    => null,
            ], 401);
        }

        return null;
    }

    /**
     * List manager's assigned MRs.
     */
    public function mrListing(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $manager = $request->user();
        $mrs     = $manager->mrs()->orderByDesc('users.id')->get();

        $message = $mrs->isEmpty() ? 'No MRs found.' : 'MR list fetched successfully.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $mrs->values(),
        ], 200);
    }

    /**
     * List doctors accessible to the manager via assigned MRs.
     */
    public function doctorListing(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $manager = $request->user();
        $mrIds   = $manager->mrs()->pluck('users.id');

        if ($mrIds->isEmpty()) {
            return response()->json([
                'status'  => 200,
                'message' => 'No doctors found.',
                'data'    => [],
            ], 200);
        }

        $doctors = Doctor::whereHas('mr', function ($query) use ($mrIds) {
                $query->whereIn('users.id', $mrIds);
            })
            ->where('status', 'active')
            ->orderByDesc('id')
            ->get();

        $message = $doctors->isEmpty() ? 'No doctors found.' : 'Doctor list fetched successfully.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $doctors->values(),
        ], 200);
    }
}

