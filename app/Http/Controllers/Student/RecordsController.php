<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;

class RecordsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalBorrowed = Borrowing::where('user_id', $user->id)->count();

        $onTime = Borrowing::where('user_id', $user->id)
                    ->whereNotNull('date_returned')
                    ->whereColumn('date_returned', '<=', 'due_date')
                    ->count();

        $lateReturns = Borrowing::where('user_id', $user->id)
                        ->whereNotNull('date_returned')
                        ->whereColumn('date_returned', '>', 'due_date')
                        ->count();

        $history = Borrowing::with('book')
                    ->where('user_id', $user->id)
                    ->whereNotNull('date_returned')
                    ->latest()
                    ->get();

        return view('student.records', compact(
            'totalBorrowed',
            'onTime',
            'lateReturns',
            'history'
        ));
    }
}