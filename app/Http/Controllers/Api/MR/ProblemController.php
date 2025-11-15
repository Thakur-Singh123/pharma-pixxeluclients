<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProblemController extends Controller
{
    /**
     * Ensure API request is authenticated.
     */
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

    /**
     * List MR problems.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $problems = Problem::with('visit_details')
            ->where('mr_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(10);

        $message = $problems->count()
            ? 'Problems fetched successfully.'
            : 'No problems found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $problems,
        ], 200);
    }

    /**
     * Store a new problem.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        $problem = Problem::create($this->buildPayload($request));

        return response()->json([
            'status' => 201,
            'message' => 'Problem challenge created successfully.',
            'data' => $problem,
        ], 201);
    }



    /**
     * Update an existing problem.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        $problem = Problem::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$problem) {
            return response()->json([
                'status' => 404,
                'message' => 'Problem not found.',
                'data' => null,
            ], 404);
        }

        $problem->update($this->buildPayload($request));
        $problem->refresh();

        return response()->json([
            'status' => 200,
            'message' => 'Problem challenge updated successfully.',
            'data' => $problem,
        ], 200);
    }

    /**
     * Delete a problem.
     */
    public function destroy($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $problem = Problem::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$problem) {
            return response()->json([
                'status' => 404,
                'message' => 'Problem not found.',
                'data' => null,
            ], 404);
        }

        $problem->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Problem challenge deleted successfully.',
            'data' => null,
        ], 200);
    }

    /**
     * Validation rules for problem requests.
     */
    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'camp_type' => 'required|string|max:255',
            'visit_name' => 'required|string|max:255',
            'doctor_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'visit_id' => 'nullable|exists:visits,id',
        ];
    }

    /**
     * Build payload for create/update requests.
     */
    private function buildPayload(Request $request): array
    {
        return [
            'mr_id' => auth()->id(),
            'visit_id' => $request->input('visit_id'),
            'title' => $request->input('title'),
            'camp_type' => $request->input('camp_type'),
            'visit_name' => $request->input('visit_name'),
            'doctor_name' => $request->input('doctor_name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'description' => $request->input('description'),
        ];
    }
}
