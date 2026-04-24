<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['posted_by', 'title', 'body', 'audience'];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}