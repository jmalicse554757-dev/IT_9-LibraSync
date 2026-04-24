<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'college_id',
        'book_id',       // LIB-XXXX
        'title',
        'author',
        'publisher',
        'year_published',
        'edition',
        'isbn',
        'category',
        'program',
        'shelf_location',
        'stock',
        'cover_image',
        'description',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // Computed status — never stored
    public function getStatusAttribute()
    {
        if ($this->stock === 0)    return 'unavailable';
        if ($this->stock <= 2)     return 'low stock';
        return 'available';
    }

    // Auto-generate LIB-XXXX on creation
    protected static function booted()
    {
        static::creating(function ($book) {
            $latest = static::max('id') ?? 0;
            $book->book_id = 'LIB-' . str_pad($latest + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}