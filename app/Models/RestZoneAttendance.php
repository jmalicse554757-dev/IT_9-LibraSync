<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestZoneAttendance extends Model
{
    protected $fillable = [
        'rest_zone_id',
        'user_id',
        'attendance_date',
        'check_in_time',
        'expected_checkout',
        'check_out_time',
        'actual_checkout',
        'reason',
        'status',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function restZone()
    {
        return $this->belongsTo(RestZone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Auto checkout check
    public function isOverstayed(): bool
    {
        if ($this->check_out_time) return false;
        return now()->format('H:i') > $this->expected_checkout;
    }
}