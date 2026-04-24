<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'receipt_no',
        'date_borrowed',
        'due_date',
        'date_returned',
        'school_days_loan',
        'book_condition',
        'remarks',
        'borrow_status',
    ];

    protected $casts = [
        'date_borrowed' => 'date',
        'due_date'      => 'date',
        'date_returned' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function penalty()
    {
        return $this->hasOne(Penalty::class);
    }

    // Computed status — never stored
public function getStatusAttribute()
{
    if ($this->date_returned) return 'returned';

    if (!$this->due_date) return 'pending';

    $today = Carbon::today();

    if ($this->due_date->lt($today))      return 'overdue';
    if ($this->due_date->equalTo($today)) return 'due today';

    return 'active';
}

    // Auto-generate RCP-YYYY-XXXX on creation
    protected static function booted()
    {
        static::creating(function ($borrowing) {
            $year   = now()->year;
            $latest = static::whereYear('created_at', $year)->max('id') ?? 0;
            $borrowing->receipt_no = 'RCP-' . $year . '-' . str_pad($latest + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}