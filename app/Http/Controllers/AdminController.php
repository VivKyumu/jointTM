<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
{
     $totalTasks = Task::count();
    $completedTasks = Task::where('status', 'completed')->count();
    $pendingTasks = Task::where('status', 'pending')->count();

   $users = User::withCount('tasks')->paginate(10); // ✅ now it's a paginator


    // Example task completion chart data
    $taskData = Task::selectRaw('DATE(completed_at) as date, COUNT(*) as count')
        ->whereNotNull('completed_at')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $taskDates = $taskData->pluck('date')->toArray();
    $taskCounts = $taskData->pluck('count')->toArray();

    $statusDistribution = [
        Task::where('status', 'pending')->count(),
        Task::where('status', 'in_progress')->count(),
        Task::where('status', 'completed')->count(),
    ];

    return view('admin.dashboard', compact(
        'totalTasks',
        'completedTasks',
        'pendingTasks',
        'users',
        'taskDates',
        'taskCounts',
        'statusDistribution'
    ));
}


    // List all tasks
    

public function index()
{
    
        $tasks = Task::latest()->paginate(10);
        return view('admin.index', compact('tasks'));
}


    // Show edit form for a task
    public function edit(Task $task)
{
    $users = User::all(); // fetch all users
    return view('admin.edit', compact('task', 'users'));
}

    // Update a task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed,disabled',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    // Disable a task
    public function disable(Task $task)
    {
        $task->update(['status' => 'disabled']);
        return redirect()->route('admin.tasks.index')->with('success', 'Task disabled.');
    }

    // Show task status
    public function status(Task $task)
    {
        return view('admin.status', compact('task'));

    }
     /** Handle the dropdown POST and update the status */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in progress,on hold,completed',
        ]);

        $task->update(['status' => $request->status]);

        // Redirect back to the view page (GET) with a flash message
        return redirect()
            ->route('admin.tasks.status', $task->id)
            ->with('success', 'Task status updated.');
    }
public function showAllTaskStatuses()
{
    $tasks = Task::with('user')->latest()->paginate(10); // eager load user
    return view('admin.status', compact('tasks')); // adjust view name as needed
}
// Show the disable confirmation page for a task
public function showDisableForm(Task $task)
{
    return view('admin.disable', compact('task'));
}

public function destroy(Task $task)
{
    $task->delete();
    return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
}

// Disable the task via POST


    
}
