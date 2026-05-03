<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountRequestController extends Controller
{
            public function index(Request $request)
        {
            $search = $request->input('search');

            $pendingStudents = User::where('role', 'student')
                ->where('status', 'pending')
                ->when($search, function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name',  'like', "%$search%")
                    ->orWhere('student_id', 'like', "%$search%")
                    ->orWhere('email',      'like', "%$search%");
                })
                ->latest()
                ->paginate(10, ['*'], 'students_page')
                ->withQueryString();

            $pendingLibrarians = User::where('role', 'librarian')
                ->where('status', 'pending')
                ->when($search, function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name',  'like', "%$search%")
                    ->orWhere('email',      'like', "%$search%");
                })
                ->latest()
                ->paginate(10, ['*'], 'librarians_page')
                ->withQueryString();

            return view('admin.account-requests', compact('pendingStudents', 'pendingLibrarians', 'search'));
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