<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create tasks
    }

    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->isAdmin();
    }

    public function delete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->isAdmin();
    }
}