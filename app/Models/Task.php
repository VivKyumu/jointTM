<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status'
        

        

    ];
   public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

// In you r Task model
public function status()
{
    return $this->belongsTo(Status::class);
}
}

