<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\CollabRoom;
use App\Models\CollabRoomRequest;
use App\Models\RestZone;
use App\Models\RestZoneAttendance;
use Illuminate\Http\Request;

class CollabZoneController extends Controller
{
    public function index()
    {
        $collabRooms      = CollabRoom::with(['requests' => function($q) {
                                $q->where('status', 'pending')
                                  ->with('user');
                            }])->get();

        $pendingRoomReqs  = CollabRoomRequest::with(['room', 'user'])
                                ->where('status', 'pending')
                                ->latest()
                                ->get();

        $restZones        = RestZone::with(['attendances' => function($q) {
                                $q->whereDate('attendance_date', today())
                                  ->with('user');
                            }, 'assignedLibrarian'])->get();

        return view('librarian.collab-zones', compact(
            'collabRooms',
            'pendingRoomReqs',
            'restZones'
        ));
    }

    public function approve(CollabRoomRequest $collabRoomRequest)
    {
        $collabRoomRequest->update(['status' => 'approved']);

        // Auto set room to occupied
        $collabRoomRequest->room->update(['status' => 'occupied']);

        return back()->with('success', "Room request approved for {$collabRoomRequest->user->full_name}!");
    }

    public function decline(CollabRoomRequest $collabRoomRequest)
    {
        $collabRoomRequest->update(['status' => 'declined']);
        return back()->with('success', "Room request declined.");
    }

    public function markRoomAvailable(CollabRoom $collabRoom)
    {
        $collabRoom->update(['status' => 'available']);
        return back()->with('success', "{$collabRoom->name} is now available.");
    }

    public function confirmEntry(RestZoneAttendance $restZoneAttendance)
    {
        $restZoneAttendance->update(['status' => 'confirmed']);
        return back()->with('success', "Entry confirmed for {$restZoneAttendance->user->full_name}!");
    }

    public function declineEntry(RestZoneAttendance $restZoneAttendance)
    {
        $restZoneAttendance->update(['status' => 'declined']);
        return back()->with('success', "Entry declined.");
    }

    public function checkOut(RestZoneAttendance $restZoneAttendance)
    {
        $restZoneAttendance->update([
            'check_out_time'  => now()->format('H:i'),
            'actual_checkout' => now()->format('H:i'),
            'status'          => 'completed',
        ]);
        return back()->with('success', "{$restZoneAttendance->user->full_name} checked out!");
    }
}