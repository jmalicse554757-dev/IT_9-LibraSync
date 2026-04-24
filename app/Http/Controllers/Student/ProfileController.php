<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalBorrowed    = $user->borrowings()->count();
        $onTime           = $user->borrowings()
                                ->whereNotNull('date_returned')
                                ->whereColumn('date_returned', '<=', 'due_date')
                                ->count();
        $late             = $user->borrowings()
                                ->whereNotNull('date_returned')
                                ->whereColumn('date_returned', '>', 'due_date')
                                ->count();
        $currentlyHeld    = $user->borrowings()
                                ->where('borrow_status', 'approved')
                                ->whereNull('date_returned')
                                ->count();

        return view('student.profile', compact(
            'user',
            'totalBorrowed',
            'onTime',
            'late',
            'currentlyHeld'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'contact_number'  => 'nullable|string|max:20',
            'gender'          => 'nullable|in:male,female,other',
            'date_of_birth'   => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'contact_number' => $request->contact_number,
            'gender'         => $request->gender,
            'date_of_birth'  => $request->date_of_birth,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}