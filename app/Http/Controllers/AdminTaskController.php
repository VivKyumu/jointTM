<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class AdminTaskController extends Controller
{
    // 🧭 Admin Dashboard
    public function taskdashboard()
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $users = User::withCount('tasks')->paginate(10);

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

        return view('admin.taskdashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'users',
            'taskDates',
            'taskCounts',
            'statusDistribution'
        ));
    }

    // 📋 List all tasks
    public function index(Request $request)
    {
        $query = Task::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->paginate(10);
        return view('admin.index', compact('tasks'));
    }

    // ✏️ Show edit form for a task
    public function edit(Task $task)
    {
        $users = User::all();
        return view('admin.edit', compact('task', 'users'));
    }

    // 💾 Update a task
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

    // 🚫 Disable a user's account via a task
    public function disable(Task $task)
    {
        $user = $task->user;

        if ($user && $user->status !== 'inactive') {
            $user->status = 'inactive';
            $user->save();

            return redirect()->route('admin.tasks.index')->with('success', 'User has been set to inactive.');
        }

        return redirect()->route('admin.tasks.index')->with('info', 'User is already inactive.');
    }

    // 📄 Show status view for a specific task
    public function status(Task $task)
    {
        return view('admin.status', compact('task'));
    }

    // 🔄 Update task status
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in progress,on hold,completed',
        ]);

        $task->update(['status' => $request->status]);

        return redirect()
            ->route('admin.tasks.status', $task->id)
            ->with('success', 'Task status updated.');
    }

    // 📋 Show all task statuses
    public function showAllTaskStatuses()
    {
        $tasks = Task::with('user')->latest()->paginate(10);
        return view('admin.status', compact('tasks'));
    }

    // 👁️ Show disable form before disabling
    public function showDisableForm(Task $task)
    {
        return view('admin.disable', compact('task'));
    }

    // 🗑️ Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }
}