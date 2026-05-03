<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Penalty;
use App\Models\PenaltySetting;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{
    private function addSchoolDays(Carbon $date, int $days): Carbon
    {
        $current = $date->copy();
        $added   = 0;
        while ($added < $days) {
            $current->addDay();
            if (!$current->isWeekend()) {
                $added++;
            }
        }
        return $current;
    }

    private function countSchoolDaysOverdue(Carbon $dueDate, Carbon $returnDate): int
    {
        $days    = 0;
        $current = $dueDate->copy()->addDay();
        while ($current->lte($returnDate)) {
            if (!$current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }
        return $days;
    }

    public function index(Request $request)
{
    $search = $request->input('search');

    $pending = Borrowing::with(['user', 'book'])
        ->where('borrow_status', 'pending')
        ->when($search, function ($q) use ($search) {
            $q->where(function($inner) use ($search) {
                $inner->whereHas('user', fn($u) => $u->where('first_name', 'like', "%$search%")
                                                      ->orWhere('last_name',  'like', "%$search%")
                                                      ->orWhere('student_id', 'like', "%$search%"))
                      ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%$search%"));
            });
        })
        ->latest()
        ->paginate(10, ['*'], 'pending_page')
        ->withQueryString();

    $approved = Borrowing::with(['user', 'book', 'penalty'])
        ->where('borrow_status', 'approved')
        ->whereNull('date_returned')
        ->when($search, function ($q) use ($search) {
            $q->where(function($inner) use ($search) {
                $inner->whereHas('user', fn($u) => $u->where('first_name', 'like', "%$search%")
                                                      ->orWhere('last_name',  'like', "%$search%")
                                                      ->orWhere('student_id', 'like', "%$search%"))
                      ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%$search%"));
            });
        })
        ->latest()
        ->paginate(10, ['*'], 'approved_page')
        ->withQueryString();

    $returned = Borrowing::with(['user', 'book', 'penalty'])
        ->where('borrow_status', 'approved')
        ->whereNotNull('date_returned')
        ->when($search, function ($q) use ($search) {
            $q->where(function($inner) use ($search) {
                $inner->whereHas('user', fn($u) => $u->where('first_name', 'like', "%$search%")
                                                      ->orWhere('last_name',  'like', "%$search%")
                                                      ->orWhere('student_id', 'like', "%$search%"))
                      ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%$search%"));
            });
        })
        ->latest()
        ->paginate(10, ['*'], 'returned_page')
        ->withQueryString();

    return view('librarian.book-requests', compact('pending', 'approved', 'returned', 'search'));
}


    public function approve(Borrowing $borrowing)
    {
        $dateBorrowed = Carbon::today();
        $dueDate      = $this->addSchoolDays($dateBorrowed, 3);

        $borrowing->update([
            'borrow_status'    => 'approved',
            'date_borrowed'    => $dateBorrowed,
            'due_date'         => $dueDate,
            'school_days_loan' => 3,
        ]);

        $borrowing->book->decrement('stock');

        NotificationService::send(
            $borrowing->user_id,
            'approved',
            'Book Request Approved!',
            "Your request for \"{$borrowing->book->title}\" has been approved. Due date: {$dueDate->format('M d, Y')}.",
            '/student/borrowed-books'
        );

        return back()->with('success', "Borrow request approved! Receipt: {$borrowing->receipt_no}");
    }

    public function decline(Borrowing $borrowing)
    {
        $borrowing->update(['borrow_status' => 'declined']);

        NotificationService::send(
            $borrowing->user_id,
            'approved',
            'Book Request Declined',
            "Your request for \"{$borrowing->book->title}\" was declined by the librarian.",
            '/student/borrowed-books'
        );

        return back()->with('success', "Request declined.");
    }

    public function confirmReturn(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'book_condition' => 'required|string',
            'remarks'        => 'nullable|string',
        ]);

        $returnDate = Carbon::today();

        $borrowing->update([
            'date_returned'  => $returnDate,
            'book_condition' => $request->book_condition,
            'remarks'        => $request->remarks,
        ]);

        $borrowing->book->increment('stock');

        if ($returnDate->gt($borrowing->due_date)) {
            $overdueDays = $this->countSchoolDaysOverdue($borrowing->due_date, $returnDate);
            $rate        = PenaltySetting::first()->fee_per_day ?? 5.00;
            $amount      = $overdueDays * $rate;

            Penalty::create([
                'borrowing_id' => $borrowing->id,
                'user_id'      => $borrowing->user_id,
                'overdue_days' => $overdueDays,
                'amount'       => $amount,
                'status'       => 'unpaid',
            ]);

            NotificationService::send(
                $borrowing->user_id,
                'penalty',
                'Penalty Fee Added',
                "You have a penalty of ₱{$amount} for returning \"{$borrowing->book->title}\" {$overdueDays} school day(s) late.",
                '/student/borrowed-books'
            );
        }

        return back()->with('success', "Book return confirmed!");
    }

    public function waivePenalty(Penalty $penalty)
    {
        $penalty->update([
            'status'    => 'waived',
            'waived_at' => now(),
            'waived_by' => auth()->id(),
        ]);

        NotificationService::send(
            $penalty->user_id,
            'penalty',
            'Penalty Fee Waived',
            "Your penalty of ₱{$penalty->amount} has been waived by the librarian.",
            '/student/borrowed-books'
        );

        return back()->with('success', "Penalty of ₱{$penalty->amount} has been waived.");
    }
}