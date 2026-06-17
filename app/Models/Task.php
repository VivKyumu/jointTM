<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'timestamp',
        'user_id',
        'status',
        'status_id',
        'complexity_id',
        'due_at',
        'completed_at',

        
    ];

    /**
     * The user who created/owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The status of the task.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * The complexity level of the task.
     */
    public function complexity(): BelongsTo
{
    return $this->belongsTo(TaskComplexity::class, 'complexity_id');
}

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->latest();
    }
    /**
     * Get the current status name.
     */
    public function getStatusNameAttribute(): string
    {
        $status = $this->getRelationValue('status');

        return $status?->name ?? $this->attributes['status'] ?? 'No Status';
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        $status = $this->getRelationValue('status');

        return $status?->color ?? '#777';
    }

    /**
     * Get the complexity name.
     */
    public function getComplexityNameAttribute(): string
    {
        return $this->complexity->name ?? 'Not Set';
    }

    /**
     * Get the complexity color.
     */
    public function getComplexityColorAttribute(): string
    {
        return $this->complexity->color ?? '#777';
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
protected $casts = [
    'due_at' => 'datetime',
    'completed_at' => 'datetime',
];

// In Task.php
protected static function booted()
{
    static::creating(function ($task) {
        if (!$task->due_at && $task->complexity_id) {
            $complexity = TaskComplexity::find($task->complexity_id);
            if ($complexity) {
                $task->due_at = $complexity->getDueDate();
            }
        }
    });

    static::saving(function ($task) {
        if ($task->status === 'completed' && is_null($task->completed_at)) {
            $task->completed_at = now();
        } elseif ($task->status !== 'completed') {
            $task->completed_at = null;
        }
    });
}

public function syncCompletedAt()
{
    $status = $this->attributes['status'] ?? null;

    if ($status === 'completed' && is_null($this->completed_at)) {
        $this->completed_at = now();
    } elseif ($status !== 'completed') {
        $this->completed_at = null;
    }
}




    
}
