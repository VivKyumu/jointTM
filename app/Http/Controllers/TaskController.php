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
    
    $user = Auth::user();
    $taskCount = Task::where('user_id', $user->id)->count();

    return view('dashboard', compact('taskCount'));
}

    /**
     * Display a list of tasks for the authenticated user.
     */
    public function index(Request $request)
{
    $user = $request->user();

    // Everyone (admin or not) sees only their own tasks
    $tasks = $user->tasks()->with('user')->latest()->paginate(10);

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
    $users = User::all(); // Fetch all users
    return view('tasks.edit', compact('task', 'users'));
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