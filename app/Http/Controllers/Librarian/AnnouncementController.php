<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')
            ->latest()
            ->get();

        return view('librarian.announcements', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'audience' => 'required|in:all,student,librarian',
        ]);

        $announcement = Announcement::create([
            'posted_by' => auth()->id(),
            'title'     => $request->title,
            'body'      => $request->body,
            'audience'  => $request->audience,
        ]);

        $this->notifyAudience($announcement);

        return back()->with('success', 'Announcement posted successfully!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'audience' => 'required|in:all,student,librarian',
        ]);

        $announcement->update([
            'title'    => $request->title,
            'body'     => $request->body,
            'audience' => $request->audience,
        ]);

        return back()->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    private function notifyAudience(Announcement $announcement)
    {
        $roles = match($announcement->audience) {
            'all'       => ['student', 'librarian'],
            'student'   => ['student'],
            'librarian' => ['librarian'],
        };

        foreach ($roles as $role) {
            NotificationService::sendToAll(
                $role,
                'announcement',
                $announcement->title,
                \Str::limit($announcement->body, 100),
                '/student/announcements'
            );
        }
    }
}
