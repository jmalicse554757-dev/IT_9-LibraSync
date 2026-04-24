<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\College;
use Illuminate\Http\Request;

class BookReportController extends Controller
{
    public function index()
    {
        // Book stats
        $totalBooks     = Book::count();
        $availableBooks = Book::where('stock', '>', 0)->count();
        $borrowedOut    = Borrowing::whereNull('date_returned')->count();

        // Books by program
        $booksByProgram = Book::selectRaw('program, count(*) as total')
            ->groupBy('program')
            ->orderByDesc('total')
            ->get();

        // Top borrowed books
        $topBorrowed = Borrowing::selectRaw('book_id, count(*) as borrow_count')
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->with('book')
            ->take(5)
            ->get();

        return view('librarian.book-reports', compact(
            'totalBooks',
            'availableBooks',
            'borrowedOut',
            'booksByProgram',
            'topBorrowed'
        ));
    }

    public function export()
    {
        $books = Book::with('college')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="book-reports-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Book ID', 'Title', 'Author', 'Program', 'Category', 'Stock', 'Status']);
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->book_id,
                    $book->title,
                    $book->author,
                    $book->program,
                    $book->category,
                    $book->stock,
                    ucfirst($book->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}