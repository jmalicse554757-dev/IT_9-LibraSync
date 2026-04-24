<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\College;
use App\Models\CollabRoom;

class AnalyticsController extends Controller
{
    public function index()
    {
        // ── STAT CARDS ──
        $totalBorrowings     = Borrowing::count();
        $lastMonthBorrowings = Borrowing::whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)
                                ->count();
        $thisMonthBorrowings = Borrowing::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();
        $borrowingChange     = $lastMonthBorrowings > 0
                                ? round((($thisMonthBorrowings - $lastMonthBorrowings) / $lastMonthBorrowings) * 100)
                                : 0;

        $overdueCount    = Borrowing::whereNull('date_returned')
                            ->whereDate('due_date', '<', now())
                            ->count();
        $newOverdueToday = Borrowing::whereNull('date_returned')
                            ->whereDate('due_date', '<', now())
                            ->whereDate('due_date', '>=', now()->subDay())
                            ->count();

        $roomBookings = \App\Models\CollabRoomRequest::count();
        $roomBookingsThisMonth = \App\Models\CollabRoomRequest::whereMonth('created_at', now()->month)->count();

        // ── MONTHLY TREND (last 6 months) ──
        $monthlyLabels = [];
        $monthlyData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month           = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData[]   = Borrowing::whereYear('created_at', $month->year)
                                ->whereMonth('created_at', $month->month)
                                ->count();
        }

        // ── BORROWING BY PROGRAM ──
        $byProgram = Borrowing::with('user')
                        ->get()
                        ->groupBy(function($b) {
                            return $b->user?->program ?? 'Unknown';
                        })
                        ->map(function($group) {
                            return $group->count();
                        })
                        ->sortDesc()
                        ->take(6);

        $programLabels = $byProgram->keys()->toArray();
        $programData   = $byProgram->values()->toArray();

        // ── TOP BORROWED BOOKS ──
        $topBooks = Book::withCount('borrowings')
                        ->orderByDesc('borrowings_count')
                        ->take(5)
                        ->get();

        // ── COLLEGE SUMMARY ──
        $collegeSummary = College::with(['users' => function($q) {
                                $q->where('role', 'student')->where('status', 'active');
                            }, 'books'])
                            ->get()
                            ->map(function($college) {
                                $topBook = $college->books()
                                    ->withCount('borrowings')
                                    ->orderByDesc('borrowings_count')
                                    ->first();
                                return [
                                    'name'       => $college->name,
                                    'code'       => $college->code,
                                    'students'   => $college->users->count(),
                                    'books'      => $college->books->count(),
                                    'top_book'   => $topBook?->title ?? 'N/A',
                                    'top_borrows'=> $topBook?->borrowings_count ?? 0,
                                ];
                            });

        return view('admin.analytics', compact(
            'totalBorrowings', 'borrowingChange', 'thisMonthBorrowings',
            'overdueCount', 'newOverdueToday',
            'roomBookings', 'roomBookingsThisMonth',
            'monthlyLabels', 'monthlyData',
            'programLabels', 'programData',
            'topBooks',
            'collegeSummary'
        ));
    }
}