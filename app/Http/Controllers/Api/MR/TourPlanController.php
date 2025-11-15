<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;
use App\Notifications\TourPlanNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TourPlanController extends Controller
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

    // Get all tasks for tomorrow
    public function index()
    {
          if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $tasks = Task::with('doctor')
            ->where('mr_id', Auth::id())
            ->whereDate('start_date', Carbon::now()->addDays(1))
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return response()->json([
            'status' => 200,
            'message' => $tasks->count() ? 'Tour plans fetched successfully.' : 'No tasks found.',
            'data' => $tasks->count() ? $tasks : null
        ]);
    }

    // Create or update tour plan
    public function update(Request $request)
    {
          if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'title'      => 'required|string|max:255',
        ]);

         //If validation fails
        if ($validator->fails()) {
            $error['status'] = 400;
            $error['message'] =  $validator->errors()->first();
            $error['data'] = null;
            return response()->json($error, 400);
        }

        $mrId = Auth::id();
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');

        $existingPlan = TaskTourPlan::where('task_id', $request->task_id)
            ->where('mr_id', $mrId)
            ->first();

        if ($existingPlan) {
            if ($existingPlan->approval_status === 'Approved') {
                return response()->json([
                    'status' => 409,
                    'message' => 'This tour plan is already approved. Delete to modify.',
                    'data' => null
                ], 409);
            }
            if ($existingPlan->approval_status === 'Pending') {
                return response()->json([
                    'status' => 409,
                    'message' => 'This tour plan is pending manager approval.',
                    'data' => null
                ], 409);
            }
        }

        $tourPlan = TaskTourPlan::updateOrCreate(
            ['task_id' => $request->task_id, 'mr_id' => $mrId],
            [
                'manager_id'     => $managerId,
                'doctor_id'      => $request->doctor_id,
                'title'          => $request->title,
                'description'    => $request->description,
                'location'       => $request->location,
                'pin_code'       => $request->pin_code,
                'start_date'     => $request->start_date,
                'end_date'       => $request->end_date,
                'approval_status'=> 'Pending',
            ]
        );

        // Notify manager
        $manager = User::find($managerId);
        if ($manager) $manager->notify(new TourPlanNotification($tourPlan));

        return response()->json([
            'status' => 200,
            'message' => 'Tour plan sent for manager approval.',
            'data' => $tourPlan
        ]);
    }

    // Delete tour plan
    public function destroy($id)
    {
         if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $tourPlan = TaskTourPlan::find($id);
        if (!$tourPlan) {
            return response()->json([
                'status' => 404,
                'message' => 'Tour plan not found.',
                'data' => null
            ]);
        }

        $task = Task::find($tourPlan->task_id);
        $isDeleted = $tourPlan->delete();

        if ($isDeleted) {
            if ($task) $task->update(['is_approval' => 'Pending']);
            return response()->json([
                'status' => 200,
                'message' => 'Tour plan deleted successfully.',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong!',
            'data' => null
        ]);
    }

    // Get updated tour plans for tomorrow
    public function updatedTourPlans()
    {
          if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $plans = TaskTourPlan::with('doctor')
            ->where('mr_id', Auth::id())
            ->whereDate('start_date', Carbon::now()->addDays(1))
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return response()->json([
            'status' => 200,
            'message' => $plans->count() ? 'Updated tour plans fetched successfully.' : 'No updated tour plans found.',
            'data' => $plans->count() ? $plans : null
        ]);
    }
}
