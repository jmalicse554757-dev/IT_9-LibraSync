<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CollabRoom;
use App\Models\CollabRoomRequest;
use App\Models\RestZone;
use App\Models\RestZoneAttendance;
use Illuminate\Http\Request;

class SpacesController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Collab Rooms
        $collabRooms = CollabRoom::all();

        // Student's own collab room requests
        $myRoomRequests = CollabRoomRequest::with('room')
                            ->where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

        // Approved requests where student is lead — needs attendance
        $approvedRequests = CollabRoomRequest::with('room')
                            ->where('user_id', $user->id)
                            ->where('status', 'approved')
                            ->get();

        // Check if student has pending room request
        $hasPendingRoomRequest = CollabRoomRequest::where('user_id', $user->id)
                                    ->where('status', 'pending')
                                    ->exists();

        // Rest Zones
        $restZones = RestZone::all();

        // Student's today attendance
        $todayAttendance = RestZoneAttendance::where('user_id', $user->id)
                            ->whereDate('attendance_date', today())
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->first();

        // Student's attendance history
        $myAttendanceHistory = RestZoneAttendance::with('restZone')
                                ->where('user_id', $user->id)
                                ->latest()
                                ->take(10)
                                ->get();

        return view('student.spaces', compact(
            'collabRooms',
            'myRoomRequests',
            'approvedRequests',
            'hasPendingRoomRequest',
            'restZones',
            'todayAttendance',
            'myAttendanceHistory'
        ));
    }

    public function requestRoom(Request $request, CollabRoom $collabRoom)
    {
        $user = auth()->user();

        // Block if already has pending request
        if (CollabRoomRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending room request. Please wait for it to be processed.');
        }

        $request->validate([
            'request_date'   => 'required|date|after_or_equal:today',
            'time_slot'      => 'required|string',
            'occupant_count' => 'required|integer|min:3|max:' . $collabRoom->capacity,
            'occupant_names' => 'required|string',
            'purpose'        => 'nullable|string|max:255',
        ]);

        CollabRoomRequest::create([
            'collab_room_id' => $collabRoom->id,
            'user_id'        => $user->id,
            'request_date'   => $request->request_date,
            'time_slot'      => $request->time_slot,
            'occupant_count' => $request->occupant_count,
            'occupant_names' => $request->occupant_names,
            'purpose'        => $request->purpose,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Room request submitted! Please wait for librarian approval.');
    }

    public function attendRestZone(Request $request, RestZone $restZone)
    {
        $user = auth()->user();

        // Block if already inside
        $alreadyInside = RestZoneAttendance::where('user_id', $user->id)
                            ->whereDate('attendance_date', today())
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->exists();

        if ($alreadyInside) {
            return back()->with('error', 'You already have an active attendance record today.');
        }

        $request->validate([
            'check_in_time'     => 'required',
            'expected_checkout' => 'required',
            'reason'            => 'nullable|string|max:255',
        ]);

        RestZoneAttendance::create([
            'rest_zone_id'      => $restZone->id,
            'user_id'           => $user->id,
            'attendance_date'   => today(),
            'check_in_time'     => $request->check_in_time,
            'expected_checkout' => $request->expected_checkout,
            'reason'            => $request->reason,
            'status'            => 'pending',
        ]);

        return back()->with('success', 'Attendance submitted! Please wait for librarian confirmation before entering.');
    }
}