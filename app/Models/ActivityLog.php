<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    public static function record(string $action, string $description, ?int $userId = null): void
    {
        self::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
