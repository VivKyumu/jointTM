<div style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2 style="color: #007bff;">Task Manager System</h2>
    <p>Hello {{ $task->user->name ?? 'User' }},</p>
    <p>You have been assigned a new task.</p>
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description ?? 'No description provided.' }}</p>
    <p><strong>Due Date:</strong> {{ optional($task->due_at)->format('d M Y') ?? 'Not set' }}</p>
    <p><strong>Complexity:</strong> {{ $task->complexity->name ?? 'Not set' }}</p>
    <p>Please log in to the system to review and update your progress.</p>
</div>
