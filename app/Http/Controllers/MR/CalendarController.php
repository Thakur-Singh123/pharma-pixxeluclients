<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthlyTask;
use App\Models\Events;
use Carbon\Carbon;

class CalendarController extends Controller
{
    //Function for show calendar
    public function index() {
        return view('mr.calendar.index');
    }

    //Function for get tasks
    public function getTasks() { 
        //Get approved tasks
        $tasks = MonthlyTask::with('task_detail','doctor_detail')
            ->where('mr_id', auth()->id())
            ->where('is_approval', '1')
            ->get(); 
        //Get task details
        $formattedTasks = []; 
        foreach ($tasks as $task) { 
            $detail = $task->task_detail; 

            $formattedTasks[] = [
                'id'       => $detail->id, 
                'title'    => $detail->title, 
                'start'    => $detail->start_date ? \Carbon\Carbon::parse($detail->start_date)->toIso8601String() : null, 
                'end'      => $detail->end_date ? \Carbon\Carbon::parse($detail->end_date)->toIso8601String() : null, 
                'doctor'   => $task->doctor_detail->doctor_name ?? 'N/A', 
                'location' => $detail->location ?? 'N/A', 
                'description' => $detail->description ?? 'N/A', 
                'pin'      => $detail->pin_code ?? 'N/A', 
                'status'   => $detail->status, 
                'type'     => 'task', 
            ]; 
        } 

        return response()->json($formattedTasks); 
    }


    public function getEvents()
    {
        $events = Events::where('mr_id', auth()->id())->where('is_active', '1')->select('id', 'title', 'start_datetime as start',
            'end_datetime as end', 'status', 'location')
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

        return response()->json($formattedEvents);
    }
}
