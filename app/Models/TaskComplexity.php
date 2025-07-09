<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $query->orderBy('level');
    }
}