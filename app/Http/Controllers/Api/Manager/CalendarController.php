<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\MonthlyTask;
use App\Models\Events;

class CalendarController extends Controller
{
    /**
     * Return calendar data for tasks, events, or both based on the requested type.
     */
    public function calendar(Request $request, ?string $type = null)
    {
        $type = $type ?? $request->query('type');
        $type = $type ? strtolower($type) : null;

        if ($type && !in_array($type, ['task', 'event'])) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid type provided. Allowed values: task, event.',
                'data' => [],
            ], 422);
        }

        $responseData = [];
        $hasData = false;

        if (!$type || $type === 'task') {
            $tasks = $this->buildTaskCalendarData();
            $responseData['calendar_tasks'] = $tasks;
            $hasData = $hasData || !empty($tasks);
        }

        if (!$type || $type === 'event') {
            $events = $this->buildEventCalendarData();
            $responseData['calendar_events'] = $events;
            $hasData = $hasData || !empty($events);
        }

        if (!$hasData) {
            return response()->json([
                'status' => 404,
                'message' => 'No calendar data found.',
                'data' => $responseData,
            ], 404);
        }

        $message = match ($type) {
            'task' => 'Calendar tasks retrieved successfully.',
            'event' => 'Calendar events retrieved successfully.',
            default => 'Calendar data retrieved successfully.',
        };

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $responseData,
        ], 200);
    }

    /**
     * Prepare calendar data for manager tasks.
     */
    private function buildTaskCalendarData(): array
    {
        $formattedTasks = [];

        $monthlyTasks = MonthlyTask::with(['task_detail', 'doctor_detail', 'mr_detail'])
            ->where('manager_id', auth()->id())
            ->where('is_approval', '1')
            ->get();

        foreach ($monthlyTasks as $task) {
            $detail = $task->task_detail;

            if (!$detail) {
                continue;
            }

            $formattedTasks[] = [
                'id'          => $detail->id,
                'title'       => $detail->title,
                'start'       => $detail->start_date,
                'end'         => $detail->end_date,
                'doctor'      => $task->doctor_detail->doctor_name ?? 'N/A',
                'location'    => $detail->location ?? 'N/A',
                'description' => $detail->description ?? 'N/A',
                'mr_name'     => $task->mr_detail->name ?? 'N/A',
                'pin'         => $detail->pin_code ?? 'N/A',
                'status'      => $detail->status,
                'type'        => 'monthly_task',
            ];
        }

        $tasks = Task::with(['doctor', 'mr'])
            ->where('manager_id', auth()->id())
            ->where('is_active', '1')
            ->get();

        foreach ($tasks as $task) {
            $formattedTasks[] = [
                'id'          => $task->id,
                'title'       => $task->title,
                'start'       => $task->start_date,
                'end'         => $task->end_date,
                'doctor'      => $task->doctor->doctor_name ?? 'N/A',
                'location'    => $task->location ?? 'N/A',
                'description' => $task->description ?? 'N/A',
                'assigned_mr' => $task->mr->name ?? 'N/A',
                'pin'         => $task->pin_code ?? 'N/A',
                'status'      => $task->status,
                'type'        => 'task',
            ];
        }

        return $formattedTasks;
    }

    /**
     * Prepare calendar data for manager events.
     */
    private function buildEventCalendarData(): array
    {
        $events = Events::where('manager_id', auth()->id())
            ->where('is_active', '1')
            ->select('id', 'title', 'start_datetime as start', 'end_datetime as end', 'status', 'location')
            ->get();

        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'id'       => $event->id,
                'title'    => $event->title,
                'start'    => $event->start,
                'end'      => $event->end,
                'location' => $event->location,
                'status'   => $event->status,
                'type'     => 'event',
            ];
        }

        return $formattedEvents;
    }
}
