<div style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2 style="color: #007bff;">Task Manager System</h2>
    <p>Hello {{ $task->user->name ?? 'User' }},</p>
    <p>The status of one of your tasks has been updated.</p>
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Previous Status:</strong> {{ ucfirst($oldStatus) }}</p>
    <p><strong>New Status:</strong> {{ ucfirst($newStatus) }}</p>
    <p>Please log in to the system for more details.</p>
</div>
