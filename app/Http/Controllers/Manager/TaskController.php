<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Task;
use App\Models\MonthlyTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TaskAssignedNotification;
use Carbon\Carbon;

class TaskController extends Controller
{
    //Function for show all tasks
    public function index(Request $request) {
        //Get tasks
        $query = Task::orderBy('ID','DESC');
        if($request->filled('created_by')) {
             $query->where('created_by', $request->created_by);
        }
        $tasks = $query->orderBy('ID','DESC')->where('manager_id', auth()->id())->paginate(5);
        return view('manager.tasks.index', compact('tasks'));
    }

    //function for waiting for approval
    public function waitingForApproval() {
        //Get tasks
        $tasks = Task::OrderBy('ID','DESC')->where('manager_id', auth()->id())->where('is_active', 0)->with('mr')->paginate(10);
        return view('manager.tasks.waiting-for-approval', compact('tasks'));
    }
    
    //function for approved tasks
    public function approvedtasks($id) {
        //Get tasks
        $task = Task::find($id);
        $task->is_active = 1;
        $task->save();
        return redirect()->back()->with('success', 'Task approved successfully.');
    }

    //function for rejected tasks
    public function rejectedtasks($id) {
        //Get tasks
        $task = Task::find($id);
        $task->is_active = 0;
        $task->save();
        return redirect()->back()->with('success', 'Task rejected successfully.');
    }
    //Function for create task
    public function create() {
        //Get mrs
        $mrs = User::find(auth()->id())->mrs;
        return view('manager.tasks.create', compact('mrs'));
    }

    //Function for submit task
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string', 
        ]);
        //Create task
        $task = Task::create([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => 'Manager',
            'status' => $request->status,
            'is_active' => 1,
        ]);
        //Get mr
        $user = User::find($request->mr_id);
        //Check if task assigned or not
        if($user){
            //Assign notification
            $user->notify(new TaskAssignedNotification($task));
        }
        return redirect()->route('manager.tasks.index')->with('success', 'Task assigned successfully.');
    }

    //Function for edit task
    public function edit($id) {
        //Get task
        $task_detail = Task::find($id);
        //get mrs
        $mrs = User::find(auth()->id())->mrs;
        return view('manager.tasks.edit-task', compact('task_detail','mrs'));
    }

    //Function for update task
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
        ]);
        //Find task
        $task = Task::findOrFail($id);
        //Store old MR
        $oldMrId = $task->mr_id;
        //Update task
        $task->update([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(),
            'doctor_id'  => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pin_code'   => $request->pin_code,
            'created_by' => 'Manager',
            'status' => $request->status,
        ]);
        //If MR changed, notify the new MR
        if ($oldMrId != $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task));
            }
        }
        return redirect()->route('manager.tasks.index')->with('success', 'Task updated successfully.');
    }

    //Function for delete task
    public function destroy($id) {
        //Delete task
        $is_delete_task = Task::where('id', $id)->delete();
        //Check if task deleted or not
        if($is_delete_task) {
            return redirect()->route('manager.tasks.index')->with('success', 'Task deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
     
      public function all_tasks()
    {
     

     //Get
    $doctors = Doctor::OrderBy('ID', 'DESC')->get();

        // Get all tasks with task details
        $tasks = MonthlyTask::with('task_detail','doctor_detail')
            ->where('manager_id', auth()->id())
            ->get();


        // Format tasks for calendar
        $events = [];
        foreach ($tasks as $task) {
            $taskDetail = $task->task_detail;
            $status = $task->is_approval;

            $color = match ($status) {
                1 => '#28a745', // approved
                0 => '#dc3545', // rejected
                default => '#ffc107', // pending
            };

            $events[] = [
                'id'    => $task->id,
                'title' => $taskDetail->title ?? 'N/A',
                'start' => $taskDetail->start_date ?? null,
                'end'   => $taskDetail->end_date ?? null,
                'color' => $color,
                'extendedProps' => [
                    'doctor_id'   => $taskDetail->doctor_id ?? null,
                    'pin_code'    => $taskDetail->pin_code ?? null,
                    'description' => $taskDetail->description ?? null,
                    'location'    => $taskDetail->location ?? null,
                    'status'      => $taskDetail->status ?? 'pending',
                ],
            ];
        }

        return view('manager.tasks.task-approval', compact('events', 'doctors'));
    }
    
    //Function for approvad tasks
    public function approveAll() {
        //Get month 
        $start = Carbon::now()->startOfMonth()->addMonth(); 
        $end = Carbon::now()->startOfMonth()->addMonths(2)->subSecond();
        //Get tasks
        $tasks = MonthlyTask::whereBetween('created_at', [$start, $end])->get();
        //Check if tasks exists or not
        if ($tasks->isEmpty()) {
            return back()->with('error', 'No tasks found this month!');
        }
        //Update task
        $tasks->each(function($task) {
            $task->update([
                'is_approval' => 1,
                'updated_at' => now(),
            ]);
        });
        return back()->with('success', 'Tasks approved successfully.');
    }

    //Function for rajected tasks
    public function rejectAll() {
        //Get month
        $start = Carbon::now()->startOfMonth()->addMonth();
        $end = Carbon::now()->startOfMonth()->addMonths(2)->subSecond(); 
        //Get tasks
        $tasks = MonthlyTask::whereBetween('created_at', [$start, $end])->get();
        //Check if tasks exists or not
        if ($tasks->isEmpty()) {
            return back()->with('error', 'No tasks found this month!');
        }
        //Update task
        $tasks->each(function($task) {
            $task->update([
                'is_approval' => 0,
                'updated_at' => now(),
            ]);
        });
        return back()->with('error', 'Tasks rejected successfully.');
    }
}