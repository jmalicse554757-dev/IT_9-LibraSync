<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenaltySetting extends Model
{
    protected $fillable = ['daily_fine_rate', 'updated_by'];

    protected $casts = ['daily_fine_rate' => 'decimal:2'];

    // Convenience: always grab the single active setting
    public static function current()
    {
        return static::latest()->first();
    }
}