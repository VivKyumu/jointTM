<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskComplexity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'color'
    ];

    public function scopeByLevel($query)
    {
        return $query->orderBy('id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'complexity_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($task) {
        if (!$task->due_at && $task->complexity_id) {
            $complexity = TaskComplexity::find($task->complexity_id);
            if ($complexity) {
                $task->due_at = $complexity->getDueDate();
            }
        }
    });
}
    
public function getDueDate()
{
    switch (strtolower($this->name)) {
        case 'simple':
            return now()->addDays(2);
        case 'medium':
            return now()->addDays(4);
        case 'complex':
            return now()->addWeeks(3);
        case 'very complex':
            return now()->addMonth();
        default:
            return now()->addDays(5);
    }
}
}
