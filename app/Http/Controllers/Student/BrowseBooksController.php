<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\College;
use Illuminate\Http\Request;

class BrowseBooksController extends Controller
{
            public function index(Request $request)
        {
            $user = auth()->user();

            // Get filter inputs
            $search     = $request->input('search');
            $collegeId  = $request->input('college_id');
            $category   = $request->input('category');
            $availability = $request->input('availability');

            // Build query
            $query = Book::with('college')->orderBy('title');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
                });
            }

            if ($collegeId) {
                $query->where('college_id', $collegeId);
            }

            if ($category) {
                $query->where('category', $category);
            }

            if ($availability === 'available') {
                $query->where('stock', '>', 3);
            } elseif ($availability === 'low stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 3);
            } elseif ($availability === 'unavailable') {
                $query->where('stock', 0);
            }

            $books = $query->paginate(12)->withQueryString();

            // For filter dropdowns
            $colleges   = College::orderBy('name')->get();
            $categories = Book::select('category')
                            ->whereNotNull('category')
                            ->distinct()
                            ->orderBy('category')
                            ->pluck('category');

            // Student's active requests
            $myActiveBookIds = Borrowing::where('user_id', $user->id)
                ->whereIn('borrow_status', ['pending', 'approved'])
                ->whereNull('date_returned')
                ->pluck('book_id')
                ->toArray();

            return view('student.browse-books', compact(
                'books',
                'colleges',
                'categories',
                'myActiveBookIds',
                'search',
                'collegeId',
                'category',
                'availability'
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