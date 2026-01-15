<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\MonthlyTask;
use App\Models\Events;

class CalendarController extends Controller
{
    //Function for show calendar
    public function index() {
        return view('manager.calendar.index');
    }

    //Function to show task calendar
    public function getTasks() {
        //Get approved MonthlyTasks
        $monthlyTasks = MonthlyTask::with('task_detail','doctor_detail','mr_detail')
            ->where('manager_id', auth()->id())
            ->where('is_approval', '1')
            ->get();

        //Get manager active Tasks
        $tasks = Task::with('doctor','mr')->where('manager_id', auth()->id())->where('created_by','manager')
            ->where('is_active', '1')
            ->get();

        //Format task
        $formattedTasks = [];
        //Get MonthlyTasks
        foreach ($monthlyTasks as $task) {
            $detail = $task->task_detail;
            //Task format
            $formattedTasks[] = [
                'id'          => $detail->id,
                'title'       => $detail->title,
                'start'       => $detail->start_date,
                'end'         => $detail->end_date,
                'doctor'      => $task->doctor_detail->doctor_name ?? 'N/A',
                'location'    => $detail->location ?? 'N/A',
                'description' => $detail->description ?? 'N/A',
                'mr_name'    => $task->mr_detail->name ?? 'N/A',
                'pin'         => $detail->pin_code ?? 'N/A',
                'status'      => $detail->status,
                'type'        => 'monthly_task',
            ];
        }
        //Get Tasks
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
        return response()->json($formattedTasks);
    }

    //Function for show event calendar
    public function getEvents() {
        //Get events
        $events = Events::with('doctor_detail', 'mr')
            ->where('manager_id', auth()->id())
            ->where('is_active', '1')
            ->get();

        //Format event
        $formattedEvents = [];
        //Event format
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id'           => $event->id,
                'title'        => $event->title,
                'start'        => $event->start_datetime,
                'end'          => $event->end_datetime,
                'description'  => $event->description,
                'location'     => $event->location,
                'pin_code'     => $event->pin_code,
                'doctor'       => $event->doctor_detail->doctor_name ?? 'N/A',
                'mr_name'      => $event->mr->name ?? 'N/A',
                'assigned_mr'  => $event->mr->name ?? 'N/A',
                'status'       => $event->status,
                'type'         => 'event',
            ];
        }

        return response()->json($formattedEvents);
    }
}
