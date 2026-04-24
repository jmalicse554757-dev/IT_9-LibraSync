<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'college_id',
        'first_name',
        'last_name',
        'email',
        'student_id',
        'employee_id',
        'password',
        'role',
        'status',
        'gender',
        'date_of_birth',
        'contact_number',
        'program',
        'year_level',
        'section',
        'profile_picture',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'date_of_birth'     => 'date',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin()     { return $this->role === 'admin'; }
    public function isLibrarian() { return $this->role === 'librarian'; }
    public function isStudent()   { return $this->role === 'student'; }
    public function isActive()    { return $this->status === 'active'; }
}