<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Task;

class CalendarController extends Controller
{
    //function to show calendar view
    public function index()
    {
        return view('manager.calendar.index');
    }

    //function to show calendar view
    public function getTasks()
    {
        $tasks = Task::where('manager_id', auth()->id())
            ->get(['id', 'title', 'start_date as start', 'end_date as end', 'status']);

        $formattedTasks = [];
        foreach ($tasks as $task) {
            $formattedTasks[] = [
                'id'     => $task->id,
                'title'  => $task->title,
                'start'  => $task->start,
                'end'    => $task->end,
                'status' => $task->status,
                'type'   => 'task',
            ];
        }

        return response()->json($formattedTasks);
    }

    public function getEvents()
    {
        $events = Events::where('manager_id', auth()->id())->select('id', 'title', 'start_datetime as start',
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
