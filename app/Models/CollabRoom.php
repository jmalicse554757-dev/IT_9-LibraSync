<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollabRoom extends Model
{
    protected $fillable = ['name', 'capacity', 'status', 'rules'];

    // status: available | occupied

    public function requests()
    {
        return $this->hasMany(CollabRoomRequest::class);
    }
}