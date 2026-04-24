<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks      = Book::count();
        $activeStudents  = User::where('role', 'student')->where('status', 'active')->count();
        $librarians      = User::where('role', 'librarian')->where('status', 'active')->count();
        $pendingRequests = User::where('status', 'pending')->count();

        // Recent activity — last 5 approved/pending users
        $recentActivity = User::whereIn('status', ['active', 'pending'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly borrowing data for chart (last 7 months)
        $monthlyData = [];
        $monthLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabels[] = $month->format('M');
            $monthlyData[] = Borrowing::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalBooks',
            'activeStudents',
            'librarians',
            'pendingRequests',
            'recentActivity',
            'monthlyData',
            'monthLabels'
        ));
    }
}