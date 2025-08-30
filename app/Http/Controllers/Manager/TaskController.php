<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TaskAssignedNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('manager_id', auth()->id())->with('mr')->paginate(10);
        return view('manager.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $mrs = User::find(auth()->id())->mrs;
        return view('manager.tasks.create', compact('mrs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mr_id'       => 'required|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            'mr_id'       => $request->mr_id,
            'manager_id'  => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        $user = User::find($request->mr_id);
        // Notification
        if($user){
            $user->notify(new TaskAssignedNotification($task));
        }
        return redirect()->route('manager.tasks.index')->with('success', 'Task assigned successfully!');
    }
}
