<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'order'];

    /**
     * Scope to order statuses by the 'order' field.
     */
    public function scopeDefaultOrder($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope to get the default status (e.g., Pending).
     */
    public function scopeDefault($query)
    {
        return $query->where('name', 'Pending');
    }

    /**
     * Dynamically determine the best text color for this background.
     */
    public function getTextColorAttribute()
    {
        $hex = str_replace('#', '', $this->color);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($brightness > 128) ? '#000000' : '#ffffff';
    }
}
