<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountRequestController extends Controller
{
    public function index()
    {
        $pendingStudents   = User::where('role', 'student')
                                ->where('status', 'pending')
                                ->latest()
                                ->get();

        $pendingLibrarians = User::where('role', 'librarian')
                                ->where('status', 'pending')
                                ->latest()
                                ->get();

        return view('admin.account-requests', compact('pendingStudents', 'pendingLibrarians'));
    }

    public function approve(User $user)
    {
        $user->update([
            'status'      => 'active',
            'approved_at' => now(),
        ]);

        return back()->with('success', "{$user->full_name} has been approved successfully.");
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);

        return back()->with('success', "{$user->full_name} has been rejected.");
    }
}