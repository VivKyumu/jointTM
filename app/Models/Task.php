<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status_id',
        'complexity_id',
        'due_date',
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

    /**
     * Get the current status name.
     */
    public function getStatusNameAttribute(): string
    {
        return $this->status->name ?? $this->attributes['status'] ?? 'No Status';
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color ?? '#777';
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
}
