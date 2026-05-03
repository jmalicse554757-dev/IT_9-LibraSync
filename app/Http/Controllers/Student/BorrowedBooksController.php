<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Penalty;
use Illuminate\Http\Request;

class BorrowedBooksController extends Controller
{
            public function index(Request $request)
        {
            $user = auth()->user();

            $search = $request->input('search');
            $status = $request->input('status');

            // Active — always show all (no pagination needed, usually small)
            $activeBorrowings = Borrowing::with(['book.college', 'penalty'])
                ->where('user_id', $user->id)
                ->whereIn('borrow_status', ['pending', 'approved'])
                ->whereNull('date_returned')
                ->orderBy('created_at', 'desc')
                ->get();

            // History — with search + filter + pagination
            $historyQuery = Borrowing::with(['book.college', 'penalty'])
                ->where('user_id', $user->id)
                ->whereIn('borrow_status', ['returned', 'declined']);

            if ($search) {
                $historyQuery->whereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
                });
            }

            if ($status) {
                $historyQuery->where('borrow_status', $status);
            }

            $historyBorrowings = $historyQuery->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

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
                'totalPenalty',
                'search',
                'status'
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