<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\MonthlyTask;
use App\Models\MangerMR;
use Carbon\Carbon;
use App\Notifications\MonthlyTasksSent;

class TaskController extends Controller
{
    //Function for show all tasks
    public function index(Request $request) {
        //Get tasks
        $query = Task::with('doctor')->orderBy('ID','DESC');
        if($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }
        $all_tasks = $query->orderBy('ID','DESC')->where('mr_id', auth()->id())->paginate(5);
        return view('mr.tasks.all-tasks', compact('all_tasks'));
    }

    //Function for create task
    public function create() {
        //Get mrs doctors
        $mr = auth()->user();
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Get tasks
        $all_tasks = Task::where('mr_id', auth()->id())->orderBy('id','DESC')->get();
        //Get tasks events
        $events = [];
        foreach ($all_tasks as $task) {
            $events[] = [
                'id'    => $task->id,
                'title' => $task->title,
                'start' => $task->start_date,
                'end'   => $task->end_date,
                'color' => $task->status == 'completed' ? '#28a745' : 
                        ($task->status == 'in_progress' ? '#ffc107' : '#dc3545'),
                'extendedProps' => [
                    'doctor_id'   => $task->doctor_id,
                    'pin_code'    => $task->pin_code,
                    'description' => $task->description,
                    'location'    => $task->location,
                ]
            ];
        }

        return view('mr.tasks.create', compact('events','all_doctors'));
    }

    //Function for submnit task
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'title' =>'required|string|max:255',
        ]);
        //Get current MR ID
        $mrId = auth()->id();
        //Get manager ID linked to this MR
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Create task
        $is_create_task = Task::create([
            'mr_id' => $mrId,
            'manager_id' => $managerId,
            'doctor_id' => $request->doctor_id,
            'pin_code' => $request->pin_code,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => 'mr',
            'status' => 'Pending',
            'is_approval' => 'Pending',
            'is_active' => 0,
        ]);
        //Check if task created or not
        if ($is_create_task) {
            return back()->with('success', 'Task created successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for edit task
    public function edit($id) {
        //Get mrs doctors
        $mr = auth()->user();
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Get Task
        $task_detail = Task::find($id);
        return view('mr.tasks.edit-task', compact('all_doctors','task_detail'));
    }

    //Function for update task
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'title' =>'required|string|max:255',
        ]);
        //Get current MR ID
        $mrId = auth()->id();
        //Get manager ID linked to this MR
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Update task
        $is_update_task = Task::where('id', $id)->update([
            'mr_id' => $mrId,
            'manager_id' => $managerId,
            'doctor_id' => $request->doctor_id,
            'pin_code' => $request->pin_code,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        //Check if task updated or not
        if ($is_update_task) {
            return back()->with('success', 'Task updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for destroy task
    public function destroy($id) {
        //Delete task
        $is_delete_task = Task::where('id', $id)->delete();
        //Check if task deleted or not
        if ($is_delete_task) {
            return redirect()->route('mr.tasks.index')->with('success', 'Task deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    public function assign_manger() {
        //Get manager tasks
        $manager_id = auth()->user()->managers->pluck('id')->first();
        $manager_tasks = Task::OrderBy('ID','DESC')->where('manager_id', auth()->id())->where('created_by', 'Manager')->paginate(5);
        return view('mr.tasks.all-tasks-manager', compact('manager_tasks'));
    }

    //Function for himself tasks
    public function himself() {
        //Get himself tasks
        $himself_tasks = Task::OrderBy('ID','DESC')->where('mr_id', auth()->id())->where('created_by', 'mr')->paginate(5);
        return view('mr.tasks.all-tasks-mr', compact('himself_tasks'));
    }

    //function for pending approval
    public function pending_approval() {
        //Get pending tasks
        $pending_tasks = Task::OrderBy('ID','DESC')->where('mr_id', auth()->id())->where('created_by', 'mr')->where('is_active', 0)->paginate(5);
        return view('mr.tasks.pending-approval', compact('pending_tasks'));
    }

    //Function for send monthly tasks calender to manager approval
    public function sendMonthlyTasksToManager(Request $request) {
        //Get auth detail
        $mrId = auth()->id();
        //Get next month and year
        $nextMonth = now()->addMonth()->month;
        $nextYear = now()->addMonth()->year;
        //Get tasks
        $tasks = Task::where('mr_id', $mrId)
            ->whereYear('start_date', $nextYear)
            ->whereMonth('start_date', $nextMonth)
            ->get();
        //Check if task exists or not
        if ($tasks->isEmpty()) {
            return back()->with('error', 'No tasks found for next month!');
        }
        //Create monthly task
        foreach ($tasks as $task) {
            MonthlyTask::updateOrCreate(
                ['task_id' => $task->id, 'mr_id' => $mrId],
                [
                    'manager_id' => $task->manager_id,
                    'is_approval' => 0,
                    'task_month'  => \Carbon\Carbon::parse($task->start_date)->format('Y-m'),
                ]
            );
        }
        //Get monthly tasks
        $monthlyTask = MonthlyTask::where('mr_id', $mrId)
            ->whereIn('task_id', $tasks->pluck('id'))
            ->first();
        //Get task month
        $taskMonth = $monthlyTask->task_month;
        //Get auth detail
        $manager  = auth()->user()->managers->pluck('id');
        $manager  = $manager->first();
        $manager  = User::find($manager);
        //Send notification
        $manager->notify(new MonthlyTasksSent($taskMonth));

        return back()->with('success', 'Tasks sent to the manager for approval successfully.');
    }

    //Function for approved tasks by manager
    public function approved_tasks() {
        //Get tasks
        $tasks = MonthlyTask::with('task_detail','doctor_detail')->where('mr_id', auth()->id())->where('is_approval', '1')->get();
        //Get events task
        $events = [];
        foreach ($tasks as $task) {
            $status = $task->task_detail->status ?? 'pending';
          // Color based on status
        $color = match ($status) {
            'pending'      => '#dc3545',
            'in_progress'  => '#0d6efd', 
            'completed'    => '#28a745', 
            default        => '#7d756cff', 
        };
            //Events
            $events[] = [
                'id' => $task->task_detail->id,
                'title' => $task->task_detail->title ?? 'N/A',
                'start' => $task->task_detail->start_date ?? null, 
                'end' => $task->task_detail->end_date ?? null,  
                'doctor' => $task->doctor_detail->doctor_name ?? 'N/A', 
                'description' => $task->task_detail->description ?? 'N/A',
                'location' => $task->task_detail->location ?? 'N/A',
                'pin' => $task->task_detail->pin_code ?? 'N/A',
                'color'=> $color,
                'status'      => $status,
            ];
        }
        return view('mr.tasks.approved-by-manager', compact('events'));
    }

    //Function for rejected tasks by manager
    public function rajected_tasks() {
        $mr = auth()->user();
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Monthly tasks 
        $all_tasks = MonthlyTask::with(['task_detail', 'doctor_detail'])
            ->where('mr_id', auth()->id())
            ->where('is_approval', '0')
            ->get();
        //Events
        $events = [];
        foreach ($all_tasks as $task) {
            $taskDetail = $task->task_detail; 
            //Get task details
            if ($taskDetail) {
                $events[] = [
                    'id'    => $task->task_detail->id,
                    'title' => $taskDetail->title ?? 'N/A',
                    'start' => $taskDetail->start_date ?? null,
                    'end'   => $taskDetail->end_date ?? null,
                    'color' => $taskDetail->status == 'completed' ? '#28a745' :
                        ($taskDetail->status == 'in_progress' ? '#ffc107' : '#dc3545'),
                    'extendedProps' => [
                        'doctor_id'   => $taskDetail->doctor_id ?? null,
                        'pin_code'    => $taskDetail->pin_code ?? null,
                        'description' => $taskDetail->description ?? null,
                        'location'    => $taskDetail->location ?? null,
                    ]
                ];
            }
        }

        //// Get task month
        // $taskMonth = $all_tasks->pluck('task_month')->unique()->first();
        //// echo "<pre>"; print_r($taskMonth);exit;
        // $manager  = auth()->user()->managers->pluck('id');
        // $manager  = $manager->first();
        // $manager  = User::find($manager);
        // $manager->notify(new MonthlyTasksSent($taskMonth));

        return view('mr.tasks.rejected-by-manager', compact('events','all_doctors'));
    }

    //Function for update calender task status
    public function update_calender_status(Request $request) {
        //Validate input request
        $request->validate([
            'id' => 'required|exists:tasks,id', 
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        //Get task detail
        $task = Task::findOrFail($request->id);
        //Update status
        $task->status = $request->status;
        $task->save();

        return back()->with('success', 'Task status updated successfully.');
    }

    //Function for update task update
    public function update_status(Request $request, $id) {
        //Get task detail
        $task = Task::findOrFail($id);
        //Get status
        $task->status = $request->status;
        //Update
        $task->save();

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }
}
