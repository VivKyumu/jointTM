<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a list of tasks for the authenticated user.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the task creation form.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
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




}