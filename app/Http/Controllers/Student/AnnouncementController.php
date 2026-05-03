<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $announcements = Announcement::with('author')
            ->where(function ($q) {
                $q->where('audience', 'all')
                ->orWhere('audience', 'student');
            })
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('body',  'like', "%$search%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('student.announcements', compact('announcements', 'search'));
    }
}