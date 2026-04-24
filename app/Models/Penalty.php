<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'borrowing_id',
        'user_id',
        'overdue_days',
        'amount',
        'status', // unpaid | paid | waived
        'paid_at',
        'waived_at',
        'waived_by',
    ];

    protected $casts = [
        'paid_at'   => 'datetime',
        'waived_at' => 'datetime',
        'amount'    => 'decimal:2',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function waivedBy()
    {
        return $this->belongsTo(User::class, 'waived_by');
    }
}