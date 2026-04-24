<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestZone extends Model
{
    protected $fillable = ['name', 'capacity', 'assigned_librarian_id'];

    public function attendances()
    {
        return $this->hasMany(RestZoneAttendance::class);
    }

    public function assignedLibrarian()
    {
        return $this->belongsTo(User::class, 'assigned_librarian_id');
    }

    // Current occupancy count
    public function getCurrentOccupancyAttribute()
    {
        return $this->attendances()->whereNull('check_out_time')->count();
    }
}