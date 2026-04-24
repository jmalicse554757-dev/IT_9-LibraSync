<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\CollabRoomRequest;
use App\Models\RestZone;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks        = Book::count();
        $currentlyBorrowed = Borrowing::whereNull('date_returned')->count();
        $overdueCount      = Borrowing::whereNull('date_returned')
                                ->whereDate('due_date', '<', now())
                                ->count();
        $roomRequests      = CollabRoomRequest::where('status', 'pending')->count();

        // Pending book requests
        $pendingBorrowings = Borrowing::with(['user', 'book'])
                                ->where('borrow_status', 'pending')
                                ->latest()
                                ->take(5)
                                ->get();

        // Overdue books
        $overdueBooks = Borrowing::with(['user', 'book'])
                            ->whereNull('date_returned')
                            ->whereDate('due_date', '<', now())
                            ->latest()
                            ->take(5)
                            ->get();

        // Pending room requests
        $pendingRooms = CollabRoomRequest::with(['room', 'user'])
                            ->where('status', 'pending')
                            ->latest()
                            ->take(3)
                            ->get();

        $availableBooks   = Book::where('stock', '>', 2)->count();
        $lowStockBooks    = Book::where('stock', '>', 0)->where('stock', '<=', 2)->count();
        $unavailableBooks = Book::where('stock', 0)->count();

        $dueSoonCount = Borrowing::whereNull('date_returned')
                            ->whereBetween('due_date', [now(), now()->addDays(3)])
                            ->count();

        $recentBooks = Book::latest()->take(10)->get();

        $borrowedBooks = Borrowing::with(['book', 'user'])
                            ->whereNull('date_returned')
                            ->latest()->take(10)->get();

        // Rest zones
        $restZones = RestZone::all();

        return view('librarian.dashboard', compact(
                        'totalBooks',
                        'currentlyBorrowed',
                        'overdueCount',
                        'roomRequests',
                        'pendingBorrowings',
                        'overdueBooks',
                        'pendingRooms',
                        'restZones',
                        'availableBooks',       
                        'lowStockBooks',       
                        'unavailableBooks',     
                        'dueSoonCount',        
                        'recentBooks',         
                        'borrowedBooks'         
                    ));
    }
}