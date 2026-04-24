<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollabRoomRequest extends Model
{
    protected $fillable = [
        'collab_room_id',
        'user_id',
        'request_date',
        'time_slot',
        'occupant_count',
        'occupant_names',
        'purpose',
        'status',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(CollabRoom::class, 'collab_room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}