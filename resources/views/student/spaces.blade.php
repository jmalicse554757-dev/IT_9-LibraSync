@extends('layouts.student')

@section('title', 'Spaces & Zones')
@section('page-title', 'Spaces & Zones')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Spaces & Zones</h1>
    <p style="color:var(--text-muted);font-size:13px;">Request collab rooms and rest zone access</p>
</div>

{{-- SUCCESS / ERROR MESSAGES --}}
@if(session('success'))
<div style="background:rgba(39,174,96,0.1);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#27ae60;font-weight:600;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.2);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#c0392b;font-weight:600;">
    {{ session('error') }}
</div>
@endif

{{-- TABS --}}
<div class="tabs">
    <button class="tab active" id="tabCollab"   onclick="switchTab('collab')">Collab Rooms</button>
    <button class="tab"        id="tabRestZones" onclick="switchTab('rest')">Rest Zones</button>
</div>

{{-- COLLAB ROOMS TAB --}}
<div id="panelCollab" style="display:block;">

    {{-- Guidelines Notice --}}
    <div style="background:rgba(232,213,196,0.4);border:1px solid var(--border);border-radius:10px;padding:14px 18px;margin-bottom:18px;display:flex;align-items:center;gap:12px;">
        <svg width="20" height="20" fill="none" stroke="var(--maroon-mid)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);">Before requesting a room, please read the guidelines.</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Proper use of collab rooms is required at all times.</div>
        </div>
        <button class="btn btn-sm btn-primary" onclick="openModal('guidelinesModal')"
            style="margin-left:auto;flex-shrink:0;">
            View Guidelines
        </button>
    </div>

    {{-- Pending Request Notice --}}
    @if($hasPendingRoomRequest)
    <div style="background:rgba(230,126,34,0.08);border:1px solid rgba(230,126,34,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#e67e22;font-weight:600;">
        ⏳ You have a pending room request. Please wait for librarian approval before submitting another.
    </div>
    @endif

    {{-- Room Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
        @forelse($collabRooms as $room)
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <div style="font-size:15px;font-weight:700;color:var(--maroon-deep);">{{ $room->name }}</div>
                @if($room->status === 'occupied')
                    <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#c0392b;">
                        <span style="width:8px;height:8px;background:#c0392b;border-radius:50%;display:inline-block;"></span>
                        Occupied
                    </span>
                @elseif($room->status === 'maintenance')
                    <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#e67e22;">
                        <span style="width:8px;height:8px;background:#e67e22;border-radius:50%;display:inline-block;"></span>
                        Maintenance
                    </span>
                @else
                    <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#27ae60;">
                        <span style="width:8px;height:8px;background:#27ae60;border-radius:50%;display:inline-block;"></span>
                        Available
                    </span>
                @endif
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Max {{ $room->capacity }} · Min 3 students</div>
            @if($room->rules)
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:12px;line-height:1.5;">{{ $room->rules }}</div>
            @endif
            <button class="btn btn-primary btn-sm"
                style="width:100%;{{ $hasPendingRoomRequest || $room->status === 'maintenance' ? 'opacity:0.5;cursor:not-allowed;' : '' }}"
                onclick="openModal('requestRoom-{{ $room->id }}')"
                {{ $hasPendingRoomRequest || $room->status === 'maintenance' ? 'disabled' : '' }}>
                Request Room
            </button>
        </div>
        @empty
        <div style="grid-column:span 3;text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:14px;font-weight:600;">No collab rooms available</div>
        </div>
        @endforelse
    </div>

    {{-- My Room Requests --}}
    @if($myRoomRequests->count() > 0)
    <div class="card">
        <div class="card-title">My Room Requests</div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Occupants</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myRoomRequests as $req)
                <tr>
                    <td style="font-weight:600;">{{ $req->room?->name }}</td>
                    <td style="color:var(--text-muted);">{{ $req->request_date?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $req->time_slot }}</td>
                    <td style="color:var(--text-muted);">{{ $req->occupant_count }} students</td>
                    <td style="color:var(--text-muted);">{{ $req->purpose ?? 'N/A' }}</td>
                    <td>
                        @if($req->status === 'approved')
                            <span class="badge badge-active">Approved</span>
                        @elseif($req->status === 'declined')
                            <span class="badge badge-overdue">Declined</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($req->status === 'approved')
                            <button class="btn btn-sm btn-primary" onclick="openModal('attendCollab-{{ $req->id }}')">
                                Fill Attendance
                            </button>
                        @else
                            <span style="font-size:11px;color:var(--text-muted);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

{{-- REST ZONES TAB --}}
<div id="panelRestZones" style="display:none;">

    {{-- Guidelines Notice --}}
    <div style="background:rgba(232,213,196,0.4);border:1px solid var(--border);border-radius:10px;padding:14px 18px;margin-bottom:18px;display:flex;align-items:center;gap:12px;">
        <svg width="20" height="20" fill="none" stroke="var(--maroon-mid)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);">Rest Zone Guidelines</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Please read before submitting your attendance.</div>
        </div>
        <button class="btn btn-sm btn-primary" onclick="openModal('restGuidelinesModal')"
            style="margin-left:auto;flex-shrink:0;">
            View Guidelines
        </button>
    </div>

    {{-- Today's Status --}}
    @if($todayAttendance && !session('success'))
    <div style="background:rgba(39,174,96,0.08);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#27ae60;font-weight:600;">
        @if($todayAttendance->status === 'pending')
            ⏳ Your attendance is submitted and waiting for librarian confirmation.
        @else
            ✅ You are currently confirmed inside {{ $todayAttendance->restZone?->name }}.
        @endif
    </div>
    @endif

    {{-- Rest Zone Cards --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">
        @forelse($restZones as $zone)
        @php
            $currentOccupancy = $zone->attendances()->whereDate('attendance_date', today())->whereIn('status', ['confirmed'])->count();
            $occupancyPercent = $zone->capacity > 0 ? ($currentOccupancy / $zone->capacity) * 100 : 0;
            $isFull = $currentOccupancy >= $zone->capacity;
        @endphp
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <div style="font-size:15px;font-weight:700;color:var(--maroon-deep);">{{ $zone->name }}</div>
                @if($isFull)
                    <span style="font-size:11px;font-weight:700;color:#c0392b;background:rgba(192,57,43,0.1);padding:3px 10px;border-radius:20px;">Full</span>
                @else
                    <span style="font-size:11px;font-weight:700;color:#27ae60;background:rgba(39,174,96,0.1);padding:3px 10px;border-radius:20px;">Available</span>
                @endif
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">Max {{ $zone->capacity }} students · Quiet rest only</div>

            {{-- Occupancy Bar --}}
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-bottom:4px;">
                <span>Occupancy</span>
                <span>{{ $currentOccupancy }}/{{ $zone->capacity }}</span>
            </div>
            <div class="progress-bar" style="margin-bottom:14px;">
                <div class="progress-fill" style="width:{{ min($occupancyPercent, 100) }}%"></div>
            </div>

            <button class="btn btn-primary btn-sm"
                style="width:100%;{{ $todayAttendance || $isFull ? 'opacity:0.5;cursor:not-allowed;' : '' }}"
                onclick="openModal('attendRest-{{ $zone->id }}')"
                {{ $todayAttendance || $isFull ? 'disabled' : '' }}>
                {{ $todayAttendance ? 'Already Submitted' : ($isFull ? 'Zone Full' : 'Fill Attendance Form') }}
            </button>
        </div>
        @empty
        <div style="grid-column:span 2;text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:14px;font-weight:600;">No rest zones available</div>
        </div>
        @endforelse
    </div>

    {{-- My Attendance History --}}
    @if($myAttendanceHistory->count() > 0)
    <div class="card">
        <div class="card-title">My Attendance History</div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Zone</th>
                    <th>Date</th>
                    <th>Check-in</th>
                    <th>Expected Out</th>
                    <th>Actual Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myAttendanceHistory as $attendance)
                <tr>
                    <td style="font-weight:600;">{{ $attendance->restZone?->name }}</td>
                    <td style="color:var(--text-muted);">{{ $attendance->attendance_date?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $attendance->check_in_time }}</td>
                    <td style="color:var(--text-muted);">{{ $attendance->expected_checkout }}</td>
                    <td style="color:var(--text-muted);">{{ $attendance->actual_checkout ?? '—' }}</td>
                    <td>
                        @if($attendance->status === 'confirmed')
                            <span class="badge badge-active">Confirmed</span>
                        @elseif($attendance->status === 'completed')
                            <span class="badge badge-returned">Completed</span>
                        @elseif($attendance->status === 'declined')
                            <span class="badge badge-overdue">Declined</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- GUIDELINES MODAL --}}
<div class="modal-overlay" id="guidelinesModal">
    <div class="modal" style="max-width:540px;">
        <button class="modal-close" onclick="closeModal('guidelinesModal')">✕</button>
        <div class="modal-title">📋 Collab Room Guidelines</div>
        <div style="font-size:13px;color:var(--text-dark);line-height:1.8;">
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>1. Minimum of 3 students required</strong> to book a collab room. Individual use is not allowed.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>2. Maximum session is 3 hours.</strong> Extensions must be re-requested and approved.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>3. Keep the room clean and orderly.</strong> Return furniture to original position after use.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>4. No food or drinks</strong> allowed inside the collab rooms.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>5. Noise must be kept to a reasonable level.</strong> Be mindful of other library users.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>6. All members must fill the attendance form</strong> upon librarian approval.
            </div>
            <div style="padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>7. Violation of guidelines</strong> may result in suspension of collab room privileges.
            </div>
        </div>
        <button class="btn btn-primary" style="width:100%;margin-top:20px;" onclick="closeModal('guidelinesModal')">
            I Understand
        </button>
    </div>
</div>

{{-- REST ZONE GUIDELINES MODAL --}}
<div class="modal-overlay" id="restGuidelinesModal">
    <div class="modal" style="max-width:540px;">
        <button class="modal-close" onclick="closeModal('restGuidelinesModal')">✕</button>
        <div class="modal-title">😴 Rest Zone Guidelines</div>
        <div style="font-size:13px;color:var(--text-dark);line-height:1.8;">
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>1. For rest purposes only.</strong> Studying or group activities are not allowed in rest zones.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>2. Maximum stay is 3 hours.</strong> You must check out on time.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>3. Maintain silence at all times.</strong> No loud talking or noise inside.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>4. No food or drinks</strong> allowed inside the rest zone.
            </div>
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>5. Wait for librarian confirmation</strong> before entering. Do not enter without approval.
            </div>
            <div style="padding:10px 14px;background:var(--cream);border-radius:8px;border-left:3px solid var(--red-main);">
                <strong>6. Keep the area clean.</strong> Dispose of personal trash before leaving.
            </div>
        </div>
        <button class="btn btn-primary" style="width:100%;margin-top:20px;" onclick="closeModal('restGuidelinesModal')">
            I Understand
        </button>
    </div>
</div>

{{-- REQUEST ROOM MODALS --}}
@foreach($collabRooms as $room)
<div class="modal-overlay" id="requestRoom-{{ $room->id }}">
    <div class="modal" style="max-width:520px;">
        <button class="modal-close" onclick="closeModal('requestRoom-{{ $room->id }}')">✕</button>
        <div class="modal-title">Request — {{ $room->name }}</div>
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:18px;">Max {{ $room->capacity }} students · Min 3 students</div>

        <form method="POST" action="{{ route('student.spaces.request-room', $room) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Date</label>
                    <input type="date" name="request_date" min="{{ date('Y-m-d') }}" required
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Time Slot</label>
                    <select name="time_slot" required style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                        <option value="">Select time</option>
                        <option value="8:00 AM - 9:00 AM">8:00 AM - 9:00 AM</option>
                        <option value="9:00 AM - 10:00 AM">9:00 AM - 10:00 AM</option>
                        <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM</option>
                        <option value="11:00 AM - 12:00 PM">11:00 AM - 12:00 PM</option>
                        <option value="1:00 PM - 2:00 PM">1:00 PM - 2:00 PM</option>
                        <option value="2:00 PM - 3:00 PM">2:00 PM - 3:00 PM</option>
                        <option value="3:00 PM - 4:00 PM">3:00 PM - 4:00 PM</option>
                        <option value="4:00 PM - 5:00 PM">4:00 PM - 5:00 PM</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Number of Occupants</label>
                <input type="number" name="occupant_count" min="3" max="{{ $room->capacity }}" required
                    placeholder="Min 3, Max {{ $room->capacity }}"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Occupant Names</label>
                <textarea name="occupant_names" rows="3" required
                    placeholder="List all members (including yourself), one per line..."
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;resize:none;"></textarea>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Purpose (Optional)</label>
                <input type="text" name="purpose" placeholder="e.g. Group study for IT9 project"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
            </div>

            <div style="display:flex;gap:8px;">
                <button type="button" class="btn" onclick="closeModal('requestRoom-{{ $room->id }}')"
                    style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- COLLAB ATTENDANCE MODALS --}}
@foreach($myRoomRequests as $req)
@if($req->status === 'approved')
<div class="modal-overlay" id="attendCollab-{{ $req->id }}">
    <div class="modal" style="max-width:480px;">
        <button class="modal-close" onclick="closeModal('attendCollab-{{ $req->id }}')">✕</button>
        <div class="modal-title">Attendance — {{ $req->room?->name }}</div>
        <div style="background:var(--cream);border-radius:8px;padding:12px;margin-bottom:18px;font-size:12px;color:var(--text-muted);">
            <strong style="color:var(--maroon-deep);">Date:</strong> {{ $req->request_date?->format('M d, Y') }} &nbsp;|&nbsp;
            <strong style="color:var(--maroon-deep);">Time:</strong> {{ $req->time_slot }}
        </div>
        <div style="background:rgba(232,213,196,0.4);border-radius:8px;padding:12px;margin-bottom:18px;font-size:12px;color:var(--text-muted);">
            All members listed in the request must fill this attendance form individually. Show this to the librarian upon entry.
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
            <div>
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Your Name</label>
                <input type="text" value="{{ auth()->user()->full_name }}" readonly
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-muted);background:var(--cream);outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Student ID</label>
                <input type="text" value="{{ auth()->user()->student_id }}" readonly
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-muted);background:var(--cream);outline:none;">
            </div>
        </div>
        <div style="text-align:center;padding:20px;color:var(--text-muted);">
            <div style="font-size:32px;margin-bottom:8px;">✅</div>
            <div style="font-size:13px;font-weight:600;color:var(--maroon-deep);">Your request is approved!</div>
            <div style="font-size:12px;margin-top:4px;">Present this to the librarian on the day of your session. The librarian will confirm your entry.</div>
        </div>
        <button class="btn btn-primary" style="width:100%;" onclick="closeModal('attendCollab-{{ $req->id }}')">
            Got it!
        </button>
    </div>
</div>
@endif
@endforeach

{{-- REST ZONE ATTENDANCE MODALS --}}
@foreach($restZones as $zone)
<div class="modal-overlay" id="attendRest-{{ $zone->id }}">
    <div class="modal" style="max-width:480px;">
        <button class="modal-close" onclick="closeModal('attendRest-{{ $zone->id }}')">✕</button>
        <div class="modal-title">Rest Zone Attendance — {{ $zone->name }}</div>
        <div style="background:rgba(232,213,196,0.4);border-radius:8px;padding:12px;margin-bottom:18px;font-size:12px;color:var(--text-muted);">
            Fill this form and submit. Wait for the librarian to confirm your entry before entering the rest zone.
        </div>
        <form method="POST" action="{{ route('student.spaces.attend-rest-zone', $zone) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Your Name</label>
                    <input type="text" value="{{ auth()->user()->full_name }}" readonly
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-muted);background:var(--cream);outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Student ID</label>
                    <input type="text" value="{{ auth()->user()->student_id }}" readonly
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-muted);background:var(--cream);outline:none;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Check-in Time</label>
                    <input type="time" name="check_in_time" required
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Expected Checkout</label>
                    <input type="time" name="expected_checkout" required
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Reason (Optional)</label>
                <input type="text" name="reason" placeholder="e.g. Rest between classes"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" class="btn" onclick="closeModal('attendRest-{{ $zone->id }}')"
                    style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Submit Attendance</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
function switchTab(tab) {
    document.getElementById('panelCollab').style.display    = tab === 'collab' ? '' : 'none';
    document.getElementById('panelRestZones').style.display = tab === 'rest'   ? '' : 'none';
    document.getElementById('tabCollab').classList.toggle('active',    tab === 'collab');
    document.getElementById('tabRestZones').classList.toggle('active', tab === 'rest');
}
</script>
@endsection