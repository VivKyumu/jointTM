<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'order'];

    public function scopeDefaultOrder($query)
    {
        return $query->orderBy('order');
    }

    public function getTextColorAttribute()
    {
        // Return white text for dark colors, black for light colors
        $hex = str_replace('#', '', $this->color);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return ($brightness > 128) ? '#000000' : '#ffffff';
    }
}