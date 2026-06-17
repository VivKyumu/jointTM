<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComplexity;
use App\Models\ActivityLog;
use App\Models\EmailLog;
use App\Mail\TaskStatusChangedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;




class AdminTaskController extends Controller
{public function taskdashboard()
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'User not authenticated.');
    }

    // 🔢 Basic task counts
    $userTasks = $user->tasks()->count();
    $userCompletedTasks = $user->tasks()->where('status', 'completed')->count();
    $userPendingTasks = $user->tasks()->where('status', 'pending')->count();
    $userInProgressTasks = $user->tasks()->where('status', 'in progress')->count();

    // 📈 Completion trend
    $taskData = $user->tasks()
        ->selectRaw('DATE(completed_at) as date, COUNT(*) as count')
        ->whereNotNull('completed_at')
        ->groupBy(DB::raw('DATE(completed_at)'))
        ->orderBy(DB::raw('DATE(completed_at)'))
        ->get();

    $taskDates = $taskData->pluck('date')->toArray();
    $taskCounts = $taskData->pluck('count')->toArray();

    // 📊 Status distribution
    $statusDistribution = [
        $user->tasks()->where('status', 'pending')->count(),
        $user->tasks()->where('status', 'in progress')->count(),
        $user->tasks()->where('status', 'completed')->count(),
    ];

    return view('admin.taskdashboard', compact(
        'userTasks',
        'userCompletedTasks',
        'userInProgressTasks',
        'userPendingTasks',
        'taskDates',
        'taskCounts',
        'statusDistribution'
    ));
}
    public function index(Request $request)
    {
        $query = Task::with(['user', 'complexity'])->latest();

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

    public function show(Task $task)
    {
        $task->load(['user', 'complexity', 'comments.user']);

        return view('tasks.show', compact('task'));
    }

    // ✏️ Show edit form for a task
   public function edit(Task $task)
{
    $users = User::all();
    $complexities = TaskComplexity::byLevel()->get(); // <- REQUIRED

    return view('admin.edit', compact('task', 'users', 'complexities'));
}
public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:pending,completed,in progress,on hold',
        'complexity_id' => 'required|exists:task_complexities,id',
    ]);

    $oldStatus = $task->status;
    // Prepare the data to update
    $data = [
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'complexity_id' => $request->complexity_id,
    ];

    // Set or clear completed_at based on status
    if ($request->status === 'completed' && is_null($task->completed_at)) {
        $data['completed_at'] = now();
    } elseif ($request->status !== 'completed') {
        $data['completed_at'] = null;
    }

    // Update the task
    $task->update($data);
    $changedFields = collect($task->getChanges())->except('updated_at')->keys()->implode(', ');
    ActivityLog::record('Task Updated', Auth::user()->name . " updated task: {$task->title}" . ($changedFields ? " ({$changedFields})" : '.'));
    if (strtolower($oldStatus) !== strtolower($task->status)) {
        ActivityLog::record('Task Status Changed', Auth::user()->name . " changed task: {$task->title} from {$oldStatus} to {$task->status}.");
        try {
            Mail::to($task->user->email)->send(new TaskStatusChangedMail($task->load('user'), $oldStatus, $task->status));
            EmailLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'recipient_email' => $task->user->email,
                'mail_type' => 'Task Status Changed',
                'subject' => 'Task Status Updated: ' . $task->title,
                'status' => 'logged',
            ]);
        } catch (\Throwable $exception) {
            EmailLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'recipient_email' => $task->user->email,
                'mail_type' => 'Task Status Changed',
                'subject' => 'Task Status Updated: ' . $task->title,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
            Log::warning('Task status changed email failed: ' . $exception->getMessage());
        }
    }

    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Task updated successfully.');
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

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);
        ActivityLog::record('Task Status Changed', Auth::user()->name . " changed task: {$task->title} from {$oldStatus} to {$task->status}.");
        try {
            Mail::to($task->user->email)->send(new TaskStatusChangedMail($task->load('user'), $oldStatus, $task->status));
            EmailLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'recipient_email' => $task->user->email,
                'mail_type' => 'Task Status Changed',
                'subject' => 'Task Status Updated: ' . $task->title,
                'status' => 'logged',
            ]);
        } catch (\Throwable $exception) {
            EmailLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'recipient_email' => $task->user->email,
                'mail_type' => 'Task Status Changed',
                'subject' => 'Task Status Updated: ' . $task->title,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
            Log::warning('Task status changed email failed: ' . $exception->getMessage());
        }

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
        ActivityLog::record('Task Deleted', Auth::user()->name . " deleted task: {$task->title}.");
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }

    // 🔧 Update complexity
    public function updateComplexity(Request $request, Task $task)
{
    $request->validate([
        'complexity_id' => 'required|exists:task_complexities,id',
    ]);

    $task->complexity_id = $request->input('complexity_id');
    $task->save();

    return back()->with('success', 'Complexity updated successfully.');
}
}
