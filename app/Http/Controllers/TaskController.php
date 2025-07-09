<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Notifications\TaskAssigned;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 🧭 Dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $taskCount = Task::where('user_id', $user->id)->count();

        return view('dashboard', compact('taskCount'));
    }

    // 📋 List tasks for authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        $tasks = $user->tasks()->with('user')->latest()->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    // ===============================
    // 📌 Version A: store() – with notifications and AJAX
   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'user_id' => 'nullable|exists:users,id', // Optional
    ]);

    $userId = $request->user_id ?? Auth::id(); // Assign to another or self

    $task = Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'status' => 'pending',
        'user_id' => $userId,
    ]);

    // Notify only if assigned to someone else
    if ($request->user_id) {
        $user = User::find($userId);
        if ($user) {
            $user->notify(new TaskAssigned($task));
        }
    }

    if ($request->ajax()) {
        return response()->json([
            'message' => 'Task created successfully!',
            'task' => $task,
        ]);
    }

    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
}


    // ===============================
    // 📌 Version A: edit() – with user list
   public function edit(Task $task)
{
    $this->authorize('update', $task); // For security

    $users = User::all(); // Needed for reassign dropdown
    return view('tasks.edit', compact('task', 'users'));
}


    // ===============================
    // 📌 Version A: update() – no auth
    public function update(Request $request, Task $task)
{
    $this->authorize('update', $task);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => ['required', Rule::in(['pending', 'in progress', 'on hold', 'completed'])],
        'user_id' => 'nullable|exists:users,id',
    ]);

    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'user_id' => $request->user_id ?? $task->user_id,
    ]);

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
}


    // 👁️ Show a task
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    // ❌ Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    // 📤 Assign task to another user
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

    // 🔄 Update status (AJAX)
    public function updateStatus(Request $request, Task $task)
    {
        $validStatuses = ['pending', 'in progress', 'on hold', 'completed'];

        $request->validate([
            'status' => ['required', Rule::in($validStatuses)],
        ]);

        $task->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }

    // 🔧 Update complexity
    public function updateComplexity(Request $request, Task $task)
    {
        $validComplexities = [1, 2, 3, 4, 5];

        $request->validate([
            'complexity_id' => ['required', Rule::in($validComplexities)],
        ]);

        $task->update(['complexity_id' => $request->complexity_id]);

        return response()->json([
            'success' => true,
            'message' => 'Complexity updated successfully',
        ]);
    }
}
