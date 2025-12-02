<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\MangerMR;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Ensure the current request is authenticated.
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
     * Resolve pagination size with sane defaults.
     */
    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        return min($perPage, 100);
    }

    /**
     * Base query for MR tasks with relationships.
     */
    private function mrTaskQuery(): Builder
    {
        return Task::where('mr_id', Auth::id())
            ->with(['mr', 'doctor'])
            ->orderByDesc('id');
    }

    /**
     * Apply created_by filter on the query when provided.
     */
    private function applyCreatedByFilter(Request $request, Builder $query): ?JsonResponse
    {
        if (!$request->filled('created_by')) {
            return null;
        }

        $createdBy = strtolower($request->query('created_by'));

        if (!in_array($createdBy, ['mr', 'manager'], true)) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid created_by filter. Allowed values: mr, manager.',
                'data'    => null,
            ], 422);
        }

        $query->where('created_by', $createdBy);

        return null;
    }

    /**
     * Paginate tasks and standardize response.
     */
    private function respondWithTasks(Request $request, Builder $query, string $successMessage, string $emptyMessage): JsonResponse
    {
        $tasks = $query->simplePaginate($this->resolvePerPage($request));
        $message = $tasks->count() ? $successMessage : $emptyMessage;

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $tasks,
        ], 200);
    }

    /**
     * Locate a task that belongs to the authenticated MR.
     */
    private function findOwnTask(int $id, bool $selfOnly = false): ?Task
    {
        $query = Task::where('id', $id)
            ->where('mr_id', Auth::id());

        if ($selfOnly) {
            $query->where('created_by', 'mr');
        }

        return $query->with(['mr', 'doctor'])->first();
    }

    /**
     * List all tasks for the authenticated MR.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrTaskQuery();

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        return $this->respondWithTasks(
            $request,
            $query,
            'Tasks fetched successfully.',
            'No tasks found.'
        );
    }

    /**
     * Fetch tasks created by the authenticated MR that are pending approval.
     */
    public function pendingForApproval(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrTaskQuery()
            ->where('created_by', 'mr')
            ->where('is_active', 0);

        return $this->respondWithTasks(
            $request,
            $query,
            'Pending approval tasks fetched successfully.',
            'No pending approval tasks found.'
        );
    }

    /**
     * Fetch tasks assigned by the manager.
     */
    public function assign_manger(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrTaskQuery()
            ->where('created_by', 'manager');

        return $this->respondWithTasks(
            $request,
            $query,
            'Manager assigned tasks fetched successfully.',
            'No manager assigned tasks found.'
        );
    }

    /**
     * Fetch tasks created by the MR themselves.
     */
    public function himself(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrTaskQuery()
            ->where('created_by', 'mr');

        return $this->respondWithTasks(
            $request,
            $query,
            'Self-created tasks fetched successfully.',
            'No self-created tasks found.'
        );
    }

    /**
     * Store a new task created by the MR.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'pin_code'    => 'nullable|string|max:20',
            'doctor_id'   => 'required|exists:doctors,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $managerId = MangerMR::where('mr_id', Auth::id())->value('manager_id');

        if (!$managerId) {
            return response()->json([
                'status'  => 422,
                'message' => 'Manager mapping not found for the current MR.',
                'data'    => null,
            ], 422);
        }

        $task = Task::create([
            'mr_id'       => Auth::id(),
            'manager_id'  => $managerId,
            'doctor_id'   => $request->doctor_id,
            'pin_code'    => $request->pin_code,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'created_by'  => 'mr',
            'status'      => 'Pending',
            'is_active'   => 0,
        ]);

        $task->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task created successfully.',
            'data'    => $task,
        ], 200);
    }

    /**
     * Update a task created by the MR.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'pin_code'    => 'nullable|string|max:20',
            'doctor_id'   => 'nullable|exists:doctors,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $task = $this->findOwnTask((int) $id, true);

        if (!$task) {
            return response()->json([
                'status'  => 404,
                'message' => 'Task not found or you are not allowed to update it.',
                'data'    => null,
            ], 404);
        }

        $managerId = MangerMR::where('mr_id', Auth::id())->value('manager_id');

        if (!$managerId) {
            return response()->json([
                'status'  => 422,
                'message' => 'Manager mapping not found for the current MR.',
                'data'    => null,
            ], 422);
        }

        $task->update([
            'mr_id'       => Auth::id(),
            'manager_id'  => $managerId,
            'doctor_id'   => $request->doctor_id,
            'pin_code'    => $request->pin_code,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);

        $task->refresh()->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task updated successfully.',
            'data'    => $task,
        ], 200);
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $task = $this->findOwnTask((int) $id);

        if (!$task) {
            return response()->json([
                'status'  => 404,
                'message' => 'Task not found.',
                'data'    => null,
            ], 404);
        }

        $task->status = $request->status;
        $task->save();

        $task->refresh()->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task status updated successfully.',
            'data'    => $task,
        ], 200);
    }

    //Function for delete task
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get task
        $task = Task::find($id);
        //Check task found or not
        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found'
            ], 404);
        }
        //Delete task
        $task->delete();
        //Response
        return response()->json([
            'status' => 200,
            'message' => 'Task deleted successfully'
        ], 200);
    }
}
