<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = ['name', 'code'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}