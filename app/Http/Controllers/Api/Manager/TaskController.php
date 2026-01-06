<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\DoctorMrAssignement;
use App\Models\Task;
use App\Models\MonthlyTask;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\services\FirebaseService;
class TaskController extends Controller
{

    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
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
     * Resolve per page value with sensible defaults.
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
     * Base task query scoped to the authenticated manager.
     */
    private function managerTaskQuery(): Builder
    {
        return Task::where('manager_id', Auth::id())
            ->with(['mr', 'doctor'])
            ->orderByDesc('id');
    }

    /**
     * Apply created_by filter (mr / manager) when present.
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
     * Apply MR filter when mr_id query parameter is supplied.
     */
    private function applyMrFilter(Request $request, Builder $query): ?JsonResponse
    {
        if (!$request->filled('mr_id')) {
            return null;
        }

        if (!ctype_digit((string) $request->query('mr_id'))) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid mr_id filter. It should be a numeric value.',
                'data'    => null,
            ], 422);
        }

        $query->where('mr_id', (int) $request->query('mr_id'));

        return null;
    }

    /**
     * Locate a task that belongs to the authenticated manager.
     */
    private function findTaskForManager(int $id): ?Task
    {
        return Task::where('id', $id)
            ->where('manager_id', Auth::id())
            ->with(['mr', 'doctor'])
            ->first();
    }

    /**
     * List tasks for the authenticated manager.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->managerTaskQuery();

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        if ($response = $this->applyMrFilter($request, $query)) {
            return $response;
        }

        $tasks = $query->simplePaginate($this->resolvePerPage($request));
        $message = $tasks->count() ? 'Tasks fetched successfully.' : 'No tasks found.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $tasks,
        ], 200);
    }

    /**
     * Create a new task for the authenticated manager.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'mr_id'      => 'required|exists:users,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'pin_code'   => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $task = Task::create([
            'mr_id'       => $request->mr_id,
            'manager_id'  => Auth::id(),
            'doctor_id'   => $request->doctor_id,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'pin_code'    => $request->pin_code,
            'created_by'  => 'manager',
            'status'      => 'pending',
            'is_active'   => 1,
        ]);

        if ($request->filled('doctor_id')) {
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id'     => $request->mr_id,
            ]);
        }

        $fcmResponses = [];
        $user = User::find($request->mr_id);
        if ($user) {
            $user->notify(new TaskAssignedNotification($task));

            //fcm notification
           $fcmResponses = $this->firebaseService->sendToUser($user, [
                'id'         => $task->id,
                'title'      => $task->title, 
                'message'    => 'You have been assigned a new task: ' . $task->title,
                'type'       => 'task',
                'is_read'    => 'false',
                'created_at'=> now()->toDateTimeString(),
            ]);

        }

        $task->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task created successfully.',
            'data'    => $task,
            'fcm_responses' => $fcmResponses
        ], 200);
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'mr_id'      => 'required|exists:users,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'pin_code'   => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $task = $this->findTaskForManager((int) $id);

        if (!$task) {
            return response()->json([
                'status'  => 404,
                'message' => 'Task not found.',
                'data'    => null,
            ], 404);
        }

        $oldMrId = $task->mr_id;

        $task->update([
            'mr_id'       => $request->mr_id,
            'manager_id'  => Auth::id(),
            'doctor_id'   => $request->doctor_id,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'pin_code'    => $request->pin_code,
        ]);

        if ($request->filled('doctor_id')) {
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id'     => $request->mr_id,
            ]);
        }

        //send notification
        $fcmResponses = [];
        if ($oldMrId !== (int) $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task));

                //fcm notification
                $fcmResponses = $this->firebaseService->sendToUser($user, [
                    'id'         => $task->id,
                    'title'      => $task->title,
                    'message'    => 'You have been assigned a new task: ' . $task->title,
                    'type'       => 'task',
                    'is_read'    => 'false',
                    'created_at'=> now()->toDateTimeString(),
                ]);
            }
        }

        $task->refresh()->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task updated successfully.',
            'data'    => $task,
            'fcm_responses' => $fcmResponses
        ], 200);
    }

    /**
     * Update task status only.
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

        $task = $this->findTaskForManager((int) $id);

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

    /**
     * Approve a task.
     */
    public function approve($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $task = $this->findTaskForManager((int) $id);

        if (!$task) {
            return response()->json([
                'status'  => 404,
                'message' => 'Task not found.',
                'data'    => null,
            ], 404);
        }

        $task->is_active = 1;
        $updated = $task->save();
        if($updated) {
            $tasks = MonthlyTask::where('task_id', $id)->update(['is_approval' => true]);
        }
        $task->refresh()->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task approved successfully.',
            'data'    => $task,
        ], 200);
    }

    /**
     * Reject a task.
     */
    public function reject($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $task = $this->findTaskForManager((int) $id);

        if (!$task) {
            return response()->json([
                'status'  => 404,
                'message' => 'Task not found.',
                'data'    => null,
            ], 404);
        }

        $task->is_active = 0;
        $updated = $task->save();
        if($updated) {
        $tasks = MonthlyTask::where('task_id', $id)->update(['is_approval' => false]);
        }

        $task->refresh()->load(['mr', 'doctor']);

        return response()->json([
            'status'  => 200,
            'message' => 'Task rejected successfully.',
            'data'    => $task,
        ], 200);
    }

    //Function for delete task
    public function destroy($id) {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }
        //Get task detail
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
