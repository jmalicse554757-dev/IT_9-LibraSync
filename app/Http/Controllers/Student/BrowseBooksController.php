<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\College;
use Illuminate\Http\Request;

class BrowseBooksController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // All books grouped by college
        $colleges = College::with(['books' => function ($q) {
            $q->orderBy('title');
        }])->get();

        // Also get general/no-college books
        $generalBooks = Book::whereNull('college_id')->orderBy('title')->get();

        // Student's active pending/approved requests — to disable Request button
        $myActiveBookIds = Borrowing::where('user_id', $user->id)
            ->whereIn('borrow_status', ['pending', 'approved'])
            ->whereNull('date_returned')
            ->pluck('book_id')
            ->toArray();

        return view('student.browse-books', compact(
            'colleges',
            'generalBooks',
            'myActiveBookIds'
        ));
    }

    public function requestBook(Request $request, Book $book)
    {
        $user = auth()->user();

        // Check if already has active request
        $existing = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('borrow_status', ['pending', 'approved'])
            ->whereNull('date_returned')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active request for this book.');
        }

        // Check availability
        if ($book->stock === 0) {
            return back()->with('error', 'This book is currently unavailable.');
        }

                    Borrowing::create([
            'user_id'         => $user->id,
            'book_id'         => $book->id,
            'borrow_status'   => 'pending',
            'date_borrowed'   => now()->toDateString(),
            'due_date'        => now()->addDays(3)->toDateString(),
            'school_days_loan'=> 3,
        ]);

        return back()->with('success', 'Book request submitted! Please wait for librarian approval.');
    }

    public function cancelRequest(Book $book)
    {
        $user = auth()->user();

        $borrowing = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('borrow_status', 'pending')
            ->first();

        if (!$borrowing) {
            return back()->with('error', 'No pending request found to cancel.');
        }

        $borrowing->delete();

        return back()->with('success', 'Book request cancelled.');
    }
}