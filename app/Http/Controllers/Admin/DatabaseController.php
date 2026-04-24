<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\College;
use Illuminate\Support\Facades\Response;

class DatabaseController extends Controller
{
    public function index()
{
    $totalBooks       = Book::count();
    $availableBooks   = Book::where('stock', '>', 2)->count();
    $lowStockBooks    = Book::where('stock', '>', 0)->where('stock', '<=', 2)->count();
    $unavailableBooks = Book::where('stock', 0)->count();
    $booksByCollege   = College::withCount('books')->having('books_count', '>', 0)->get();
    $recentBooks      = Book::latest()->take(5)->get()->map(function($b) {
        return [
            'id'     => $b->book_id,
            'title'  => $b->title,
            'author' => $b->author,
            'stock'  => $b->stock,
            'status' => $b->stock == 0 ? 'unavailable' : ($b->stock <= 2 ? 'low stock' : 'available'),
        ];
    });

    $totalUsers      = User::count();
    $totalStudents   = User::where('role', 'student')->count();
    $totalLibrarians = User::where('role', 'librarian')->count();
    $totalAdmins     = User::where('role', 'admin')->count();
    $activeUsers     = User::where('status', 'active')->count();
    $pendingUsers    = User::where('status', 'pending')->count();
    $rejectedUsers   = User::where('status', 'rejected')->count();
    $usersByCollege  = College::withCount('users')->having('users_count', '>', 0)->get();
    $recentUsers     = User::latest()->take(5)->get()->map(function($u) {
        return [
            'name'   => $u->full_name,
            'role'   => $u->role,
            'status' => $u->status,
            'date'   => $u->created_at->format('M d, Y'),
        ];
    });

    $totalBorrowings    = Borrowing::count();
    $activeBorrowings   = Borrowing::whereNull('date_returned')->count();
    $returnedBorrowings = Borrowing::whereNotNull('date_returned')->count();
    $overdueBorrowings  = Borrowing::whereNull('date_returned')->whereDate('due_date', '<', now())->count();
    $topBooks           = Book::withCount('borrowings')->orderByDesc('borrowings_count')->take(5)->get()->map(function($b) {
        return [
            'title'  => $b->title,
            'author' => $b->author,
            'count'  => $b->borrowings_count,
        ];
    });
    $recentBorrowings   = Borrowing::with(['user', 'book'])->latest()->take(5)->get()->map(function($br) {
        return [
            'receipt' => $br->receipt_no ?? 'N/A',
            'user'    => optional($br->user)->full_name ?? 'N/A',
            'book'    => optional($br->book)->title ?? 'N/A',
            'status'  => $br->status ?? 'N/A',
            'date'    => $br->created_at->format('M d, Y'),
        ];
    });

    $booksByCollegeMapped = $booksByCollege->map(function($c) {
        return ['name' => $c->name, 'code' => $c->code, 'count' => $c->books_count];
    });

    $usersByCollegeMapped = $usersByCollege->map(function($c) {
        return ['name' => $c->name, 'code' => $c->code, 'count' => $c->users_count];
    });

    return view('admin.database', compact(
        'totalBooks', 'availableBooks', 'lowStockBooks', 'unavailableBooks',
        'booksByCollege', 'booksByCollegeMapped', 'recentBooks',
        'totalUsers', 'totalStudents', 'totalLibrarians', 'totalAdmins',
        'activeUsers', 'pendingUsers', 'rejectedUsers',
        'usersByCollege', 'usersByCollegeMapped', 'recentUsers',
        'totalBorrowings', 'activeBorrowings', 'returnedBorrowings',
        'overdueBorrowings', 'topBooks', 'recentBorrowings'
    ));
}

    public function exportCsv()
    {
        $books = Book::with('college')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="librasync_books_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Book ID','Title','Author','Publisher','Year','Edition','ISBN','Category','Program','College','Stock','Shelf Location']);
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->book_id, $book->title, $book->author,
                    $book->publisher, $book->year_published, $book->edition,
                    $book->isbn, $book->category, $book->program,
                    $book->college?->name ?? 'General',
                    $book->stock, $book->shelf_location,
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function auditLog()
    {
        $recentUsers      = User::latest()->take(10)->get();
        $recentBooks      = Book::latest()->take(10)->get();
        $recentBorrowings = Borrowing::with(['user', 'book'])->latest()->take(10)->get();

        return view('admin.audit-log', compact('recentUsers', 'recentBooks', 'recentBorrowings'));
    }
}