<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Penalty;
use Illuminate\Http\Request;

class BorrowedBooksController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Active — pending or approved, not yet returned
        $activeBorrowings = Borrowing::with(['book.college', 'penalty'])
            ->where('user_id', $user->id)
            ->whereIn('borrow_status', ['pending', 'approved'])
            ->whereNull('date_returned')
            ->orderBy('created_at', 'desc')
            ->get();

        // History — returned or declined
        $historyBorrowings = Borrowing::with(['book.college', 'penalty'])
            ->where('user_id', $user->id)
            ->whereIn('borrow_status', ['returned', 'declined'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Unpaid penalties
        $unpaidPenalties = Penalty::with('borrowing.book')
            ->where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->get();

        $totalPenalty = $unpaidPenalties->sum('amount');

        return view('student.borrowed-books', compact(
            'activeBorrowings',
            'historyBorrowings',
            'unpaidPenalties',
            'totalPenalty'
        ));
    }

    public function cancel(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if ($borrowing->borrow_status !== 'pending') {
            return back()->with('error', 'You can only cancel pending requests.');
        }

        $borrowing->delete();
        return back()->with('success', 'Borrow request cancelled successfully.');
    }
}