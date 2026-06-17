<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Notifications\TaskAssigned;
use App\Models\TaskComplexity;
use App\Models\Status;
use App\Models\ActivityLog;
use App\Models\EmailLog;
use App\Mail\TaskAssignedMail;
use App\Mail\TaskStatusChangedMail;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 📋 List tasks for authenticated user
    public function index(Request $request)
{
    $user = $request->user();

    $tasks = Task::with('user', 'status', 'complexity') // ✅ fixed relationship
                 ->where('user_id', $user->id)
                 ->latest()
                 ->paginate(10);

   $complexities = TaskComplexity::all();
   $status = Status::all(); // ✅ Add this line

    return view('tasks.index', compact('tasks', 'complexities', 'status'));
}

    // 📌 store() – with notifications and AJAX
 
   
public function store(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'complexity_id' => 'required|exists:task_complexities,id',
    ]);

    // Get complexity and compute due date
    $complexity = TaskComplexity::findOrFail($validated['complexity_id']);
    $due_at = now()->addDays($complexity->duration);

    // Create the task
    $task = new Task();
    $task->title = $validated['title'];
    $task->description = $validated['description'];
    $task->complexity_id = $complexity->id;
    $task->due_at = $due_at;
    $task->status = 'pending';
    $task->user_id = Auth::id();
    $task->save();
    ActivityLog::record('Task Created', Auth::user()->name . " created task: {$task->title} assigned to {$task->user->name}.");

    // Eager load relationships
    $task->load(['complexity', 'user']);
    try {
        Mail::to($task->user->email)->send(new TaskAssignedMail($task));
        EmailLog::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
            'recipient_email' => $task->user->email,
            'mail_type' => 'Task Assigned',
            'subject' => 'New Task Assigned: ' . $task->title,
            'status' => 'logged',
        ]);
    } catch (\Throwable $exception) {
        EmailLog::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
            'recipient_email' => $task->user->email,
            'mail_type' => 'Task Assigned',
            'subject' => 'New Task Assigned: ' . $task->title,
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
        Log::warning('Task assigned email failed: ' . $exception->getMessage());
    }

    // Return response
    if ($request->ajax()) {
        return response()->json([
            'message' => 'Task created successfully.',
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_at' => optional($task->due_at)->toDateString(),
                'created_at' => $task->created_at->toDateTimeString(),
                'updated_at' => $task->updated_at->toDateTimeString(),
                'user_id' => $task->user->id,
                'user_name' => $task->user->name,
                'complexity_name' => $task->complexity->name ?? 'N/A',
            ]
        ]);
    }

    return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
}


    // 📌 edit() – with user list
    public function edit(Task $task)
    {
        
        $this->authorize('update', $task);
        $users = User::all();
        $complexities = TaskComplexity::all(['id', 'name', 'duration']);
        $statuses = Status::all(); // ✅ Add this line

        return view('tasks.edit', compact('task', 'users', 'complexities', 'statuses'));
    }

    public function update(Request $request, Task $task)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'complexity_id' => 'required|exists:task_complexities,id',
        'due_at' => 'nullable|date',
        'status_id' => 'required|exists:statuses,id',
    ]);

    $oldStatus = $task->status_name;
    $task->title = $validated['title'];
    $task->description = $validated['description'];
    $task->complexity_id = $validated['complexity_id'];
    $task->due_at = $validated['due_at'];
    $task->status_id = $validated['status_id'];

    // dd($task->status_id);
    // Update the string column 'status' based on 'status_id'
    $status = Status::find($validated['status_id']);
    if ($status && $task->isFillable('status')) {
        $task->status = $status->name;
    }

    // dd($task->status);
    $task->save();
    $changedFields = collect($task->getChanges())->except('updated_at')->keys()->implode(', ');
    ActivityLog::record('Task Updated', Auth::user()->name . " updated task: {$task->title}" . ($changedFields ? " ({$changedFields})" : '.'));
    if (strtolower($oldStatus) !== strtolower($task->status_name)) {
        ActivityLog::record('Task Status Changed', Auth::user()->name . " changed task: {$task->title} from {$oldStatus} to {$task->status_name}.");
        try {
            Mail::to($task->user->email)->send(new TaskStatusChangedMail($task->load('user'), $oldStatus, $task->status_name));
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

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
}


    // 👁️ Show a task
    public function show(Task $task)
    {
        $task->load(['user', 'complexity', 'comments.user']);
        return view('tasks.show', compact('task'));
    }

    public function storeComment(Request $request, Task $task)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && (int) $task->user_id !== (int) $user->id) {
            abort(403, 'You are not allowed to comment on this task.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        ActivityLog::record('Task Commented', "{$user->name} commented on task: {$task->title}.");

        return back()->with('success', 'Comment added successfully.');
    }

    // ❌ Delete a task
    public function destroy(Task $task)
    {
        ActivityLog::record('Task Deleted', Auth::user()->name . " deleted task: {$task->title}.");
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
        ActivityLog::record('Task Created', Auth::user()->name . " created task: {$task->title} assigned to {$task->user->name}.");

        $user = User::find($request->user_id);
        $task->load(['user', 'complexity']);
        try {
            Mail::to($user->email)->send(new TaskAssignedMail($task));
            EmailLog::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'recipient_email' => $user->email,
                'mail_type' => 'Task Assigned',
                'subject' => 'New Task Assigned: ' . $task->title,
                'status' => 'logged',
            ]);
        } catch (\Throwable $exception) {
            EmailLog::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'recipient_email' => $user->email,
                'mail_type' => 'Task Assigned',
                'subject' => 'New Task Assigned: ' . $task->title,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
            Log::warning('Task assigned email failed: ' . $exception->getMessage());
        }
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

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }
}
