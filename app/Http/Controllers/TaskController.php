<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssigned;
use App\Models\User;

class TaskController extends Controller
{
   public function dashboard() 
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $taskCount = $user->tasks()->count();

    $completedTasks = Task::where('status', 'completed')->count();
    $pendingTasks = Task::where('status', 'pending')->count();

    $statusDistribution = [
        Task::where('status', 'pending')->count(),
        Task::where('status', 'in progress')->count(),
        $completedTasks
    ];

    $taskDates = Task::selectRaw('DATE(created_at) as date')
                     ->where('status', 'completed')
                     ->orderBy('date')
                     ->pluck('date')
                     ->toArray();

    $taskCounts = array_count_values($taskDates);

    return view('dashboard', [
        'taskCount' => $taskCount,
        'completedTasks' => $completedTasks,
        'pendingTasks' => $pendingTasks,
        'statusDistribution' => $statusDistribution,
        'taskDates' => array_keys($taskCounts),
        'taskCounts' => array_values($taskCounts),
    ]);
}

    /**
     * Display a list of tasks for the authenticated user.
     */
    public function index(Request $request)
{
    $user = $request->user();

    if ($user->isAdmin()) {
        // Admin sees all tasks + user info
        $tasks = Task::with('user')->latest()->paginate(10);
    } else {
        // Normal users see their own tasks
        $tasks = $user->tasks()->with('user')->latest()->paginate(10);
    }

    return view('tasks.index', compact('tasks'));
}
 
    /**
     * Show the task creation form.
     */
    public function create()
    {
        return view('tasks.create');
    }


public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $task = Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => 'pending',
        'user_id' => Auth::id(),
    ]);

    // If the request is AJAX (like from the modal), return JSON
    if ($request->ajax()) {
        return response()->json([
            'message' => 'Task created successfully!',
            'task' => $task,
        ]);
    }

    // Otherwise, continue with normal redirect
    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    // 3. Notify the assigned user

    $user = User::find($validated['assigned_to']);
    $user->notify(new TaskAssigned($task));

    // 4. Redirect back with success message
    return redirect()->route('tasks.index')->with('success', 'Task created and user notified!');
}

    /**
     * Show the form for editing a specific task.
     */
    public function edit(Task $task)
{
    return view('tasks.edit', compact('task'));
}


    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|string',
    ]);

    $task->update($request->only('title', 'description', 'status'));

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
}


    public function show(Task $task)
{
    return view('tasks.show', compact('task'));
}
public function destroy(Task $task)
{
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted.');
}



public function assignTask(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ]);

    $task = Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => 'pending',
        'user_id' => $request->user_id,
    ]);

    $user = User::find($request->user_id);
    $user->notify(new TaskAssigned($task));

    return back()->with('success', 'Task assigned and user notified!');
}
        public function via($notifiable)
    {
      return ['mail', 'database'];
    }

    

}