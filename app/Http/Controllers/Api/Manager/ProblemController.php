<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Problem;
use App\Models\Visit;

class ProblemController extends Controller
{
    //Authentication check
    private function ensureAuthenticated(): ?JsonResponse {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 400);
        }
        return null;
    }

    //Function for all problems
    public function index() {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get mrs
        $mrs = auth()->user()->mrs->pluck('id');
        //All problems
        $all_problems = Problem::with(['mr_detail', 'visit_details'])
            ->whereIn('mr_id', $mrs)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Problems fetched successfully.',
            'data' => $all_problems
        ], 200);
    }

    //Function for approve problem
    public function approve_problem($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get problem
        $problem = Problem::find($id);
        //check problem not found
        if (!$problem) {
            return response()->json([
                'status' => 400,
                'message' => 'Problem not found'
            ], 400);
        }
        //check already approved or not
        if ($problem->status === 'approved') {
            return response()->json([
                'status' => 400,
                'message' => 'This problem challenge is already approved. First reject then approve again.'
            ], 400);
        }
        //save status
        $problem->status = 'approved';
        $problem->save();
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Problem challenge approved successfully.'
        ], 200);
    }

    //Function for reject problem
    public function reject_problem($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //get problem detail
        $problem = Problem::find($id);
        //check problem fonnd or not
        if (!$problem) {
            return response()->json([
                'status' => 400,
                'message' => 'Problem not found'
            ], 400);
        }
        //check already rejected or not
        if ($problem->status === 'rejected') {
            return response()->json([
                'status' => 400,
                'message' => 'This problem challenge is already rejected. First approve then reject again.'
            ], 400);
        }
        //save status
        $problem->status = 'rejected';
        $problem->save();
        //response
        return response()->json([
            'status' => 200,
            'message' => 'Problem challenge rejected successfully.'
        ], 200);
    }

    //Function for update problem
    public function update(Request $request, $id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Validation input fields
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'camp_type' => 'required',
            'visit_name' => 'required',
            'doctor_name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        //Validate input fields
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
            ], 400);
        }
        //Get problem
        $problem = Problem::find($id);
        //Check problem found or not
        if (!$problem) {
            return response()->json([
                'status' => 400,
                'message' => 'Problem not found',
            ], 400);
        }
        //Update problem
        $problem->update([
            'title' => $request->title,
            'camp_type' => $request->camp_type,
            'visit_name' => $request->visit_name,
            'doctor_name' => $request->doctor_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Problem updated successfully.',
            'data' => $problem
        ], 200);
    }

    //Function for delete problem
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get problem
        $problem = Problem::find($id);
        //Check problem found or not
        if (!$problem) {
            return response()->json([
                'status' => 400,
                'message' => 'Problem not found'
            ], 400);
        }
        //Delete problem
        $problem->delete();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Problem deleted successfully.'
        ], 200);
    }
}
