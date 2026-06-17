<div style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2 style="color: #dc3545;">Task Manager System</h2>
    <p>Hello {{ $user->name }},</p>
    <p>The following tasks assigned to you are overdue:</p>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th align="left">Task</th>
                <th align="left">Due Date</th>
                <th align="left">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ optional($task->due_at)->format('d M Y') }}</td>
                    <td>{{ ucfirst($task->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Please log in to the system and update your task progress.</p>
</div>
