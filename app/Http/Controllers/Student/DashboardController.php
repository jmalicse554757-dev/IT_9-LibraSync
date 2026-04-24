<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Stat cards
        $booksBorrowed = Borrowing::where('user_id', $user->id)
                            ->where('borrow_status', 'approved')
                            ->whereNull('date_returned')
                            ->count();

        $dueSoon = Borrowing::where('user_id', $user->id)
                        ->where('borrow_status', 'approved')
                        ->whereNull('date_returned')
                        ->whereDate('due_date', '<=', Carbon::today()->addDays(2))
                        ->whereDate('due_date', '>=', Carbon::today())
                        ->count();

        $booksRead = Borrowing::where('user_id', $user->id)
                        ->whereNotNull('date_returned')
                        ->count();

        // Currently borrowed list
        $currentlyBorrowed = Borrowing::with('book')
                                ->where('user_id', $user->id)
                                ->where('borrow_status', 'approved')
                                ->whereNull('date_returned')
                                ->latest()
                                ->get();

        // Announcements
        $announcements = Announcement::whereIn('audience', ['all', 'student'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('student.dashboard', compact(
            'booksBorrowed',
            'dueSoon',
            'booksRead',
            'currentlyBorrowed',
            'announcements'
        ));
    }
}