@extends('layouts.librarian')

@section('title', 'Collab & Rest Zones')
@section('page-title', 'Collab & Rest Zones')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Collab & Rest Zones</h1>
        <p style="color:var(--text-muted);font-size:13px;">Manage room requests and attendance</p>
    </div>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="background:rgba(39,174,96,0.1);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#27ae60;font-weight:600;">
    {{ session('success') }}
</div>
@endif

{{-- TABS --}}
<div class="tabs">
    <button class="tab active" id="tabCollab"   onclick="switchTab('collab')">Collab Rooms</button>
    <button class="tab"        id="tabRestZones" onclick="switchTab('rest')">Rest Zones</button>
</div>

{{-- COLLAB ROOMS TAB --}}
<div id="panelCollab">

    {{-- Pending Room Requests --}}
    @if($pendingRoomReqs->count() > 0)
    <div class="card" style="margin-bottom:16px;">
        <div class="card-title">Pending Room Requests</div>
        <form method="GET" action="{{ route('librarian.collab-zones') }}" style="margin-bottom:14px;">
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:220px;">
                <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);opacity:0.4;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name..."
                    style="width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;">
            </div>
            <button type="submit" style="padding:10px 20px;background:var(--maroon-deep);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">Search</button>
            @if(request('search'))
            <a href="{{ route('librarian.collab-zones') }}" style="padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;">Clear</a>
            @endif
        </div>
    </form>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Lead Student</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Occupants</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRoomReqs as $req)
                <tr>
                    <td style="font-weight:600;">{{ $req->user?->full_name }}</td>
                    <td>{{ $req->room?->name }}</td>
                    <td style="color:var(--text-muted);">{{ $req->request_date?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $req->time_slot }}</td>
                    <td style="color:var(--text-muted);">{{ $req->occupant_count }} students</td>
                    <td style="color:var(--text-muted);">{{ $req->purpose ?? 'N/A' }}</td>
                    <td><span class="badge badge-pending">Pending</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('librarian.collab-zones.approve', $req) }}">
                                @csrf
                                <button type="submit" class="btn btn-approve btn-sm">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('librarian.collab-zones.decline', $req) }}">
                                @csrf
                                <button type="submit" class="btn btn-decline btn-sm">Decline</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Room Status Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
        @forelse($collabRooms as $room)
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <div style="font-size:15px;font-weight:700;color:var(--maroon-deep);">{{ $room->name }}</div>
                @if($room->status === 'occupied')
                    <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#c0392b;">
                        <span style="width:8px;height:8px;background:#c0392b;border-radius:50%;display:inline-block;"></span>
                        Occupied
                    </span>
                @else
                    <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#27ae60;">
                        <span style="width:8px;height:8px;background:#27ae60;border-radius:50%;display:inline-block;"></span>
                        Available
                    </span>
                @endif
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Max {{ $room->capacity }} / Min 3 students</div>
            @if($room->rules)
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:12px;">{{ $room->rules }}</div>
            @endif
            <div style="display:flex;gap:8px;">
                    <button class="btn btn-approve btn-sm" onclick="openModal('manage-room-{{ $room->id }}')"
                        style="flex:1;">
                        Manage
                    </button>
                @if($room->status === 'occupied')
                <form method="POST" action="{{ route('librarian.collab-zones.available', $room) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-approve">Mark Available</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column:span 3;text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:14px;font-weight:600;">No collab rooms found</div>
        </div>
        @endforelse
    </div>

</div>

{{-- REST ZONES TAB --}}
<div id="panelRestZones" style="display:none;">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
        @forelse($restZones as $zone)
        @php
            $todayAttendances = $zone->attendances->whereIn('status', ['confirmed']);
            $pendingAttendances = $zone->attendances->where('status', 'pending');
            $currentOccupancy = $todayAttendances->count();
            $occupancyPercent = $zone->capacity > 0 ? ($currentOccupancy / $zone->capacity) * 100 : 0;
        @endphp
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <div style="font-size:15px;font-weight:700;color:var(--maroon-deep);">{{ $zone->name }}</div>
                @if($currentOccupancy >= $zone->capacity)
                    <span style="font-size:11px;font-weight:700;color:#c0392b;background:rgba(192,57,43,0.1);padding:3px 10px;border-radius:20px;">Full</span>
                @else
                    <span style="font-size:11px;font-weight:700;color:#27ae60;background:rgba(39,174,96,0.1);padding:3px 10px;border-radius:20px;">Available</span>
                @endif
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Max {{ $zone->capacity }} · Quiet rest only</div>
            @if($zone->assignedLibrarian)
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">Assigned: {{ $zone->assignedLibrarian->full_name }}</div>
            @endif

            {{-- Occupancy Bar --}}
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-bottom:4px;">
                <span>Occupancy</span>
                <span>{{ $currentOccupancy }}/{{ $zone->capacity }} students</span>
            </div>
            <div class="progress-bar" style="margin-bottom:14px;">
                <div class="progress-fill" style="width:{{ min($occupancyPercent, 100) }}%"></div>
            </div>

            @if($pendingAttendances->count() > 0)
            <div style="font-size:11px;color:#e67e22;margin-bottom:10px;font-weight:600;">
                {{ $pendingAttendances->count() }} pending confirmation
            </div>
            @endif

            <button class="btn btn-approve btn-sm" onclick="openModal('manage-zone-{{ $zone->id }}')">
                Manage Attendance
            </button>
        </div>
        @empty
        <div style="grid-column:span 2;text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:14px;font-weight:600;">No rest zones found</div>
        </div>
        @endforelse
    </div>
</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- MANAGE COLLAB ROOM MODALS --}}
@foreach($collabRooms as $room)
<div class="modal-overlay" id="manage-room-{{ $room->id }}">
    <div class="modal" style="max-width:640px;">
        <button class="modal-close" onclick="closeModal('manage-room-{{ $room->id }}')">✕</button>
        <div class="modal-title">{{ $room->name }}</div>

        @php
            $approvedReqs = $room->requests->where('status', 'approved');
        @endphp

        @if($approvedReqs->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Occupants</th>
                    <th>Purpose</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedReqs as $req)
                <tr>
                    <td style="font-weight:600;">{{ $req->user?->full_name }}</td>
                    <td>{{ $req->request_date?->format('M d, Y') }}</td>
                    <td>{{ $req->time_slot }}</td>
                    <td>{{ $req->occupant_count }} students</td>
                    <td>{{ $req->purpose ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px;">
            No approved requests for this room yet.
        </div>
        @endif
    </div>
</div>
@endforeach

{{-- MANAGE REST ZONE ATTENDANCE MODALS --}}
@foreach($restZones as $zone)
<div class="modal-overlay" id="manage-zone-{{ $zone->id }}">
    <div class="modal" style="max-width:700px;">
        <button class="modal-close" onclick="closeModal('manage-zone-{{ $zone->id }}')">✕</button>
        <div class="modal-title">{{ $zone->name }} Attendance — Confirm Entry</div>

        <div style="background:rgba(232,213,196,0.3);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:12px;color:var(--text-muted);">
            The librarian must verify and confirm each student's attendance form before they are allowed to use the rest zone.
        </div>

        @php
            $pendingEntries   = $zone->attendances->where('status', 'pending');
            $confirmedEntries = $zone->attendances->where('status', 'confirmed');
            $completedEntries = $zone->attendances->where('status', 'completed');
        @endphp

        {{-- Pending Confirmation --}}
        @if($pendingEntries->count() > 0)
        <div style="margin-bottom:18px;">
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:10px;">
                Pending Confirmation
                <span style="background:#e67e22;color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pendingEntries->count() }} waiting</span>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Check-in</th>
                        <th>Est. Checkout</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingEntries as $attendance)
                    <tr>
                        <td style="font-weight:600;">{{ $attendance->user?->full_name }}</td>
                        <td style="color:var(--red-main);font-weight:700;">{{ $attendance->user?->student_id }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>{{ $attendance->expected_checkout }}</td>
                        <td style="color:var(--text-muted);">{{ $attendance->reason ?? 'Rest / Sleep' }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <form method="POST" action="{{ route('librarian.collab-zones.confirm-entry', $attendance) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-approve btn-sm">Confirm Entry</button>
                                </form>
                                <form method="POST" action="{{ route('librarian.collab-zones.decline-entry', $attendance) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-decline btn-sm">Decline</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Currently Inside --}}
        @if($confirmedEntries->count() > 0)
        <div style="margin-bottom:18px;">
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:10px;">
                Currently Inside
                <span style="background:#27ae60;color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $confirmedEntries->count() }} inside</span>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Check-in</th>
                        <th>Est. Checkout</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($confirmedEntries as $attendance)
                    <tr>
                        <td style="font-weight:600;">{{ $attendance->user?->full_name }}</td>
                        <td style="color:var(--red-main);font-weight:700;">{{ $attendance->user?->student_id }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>{{ $attendance->expected_checkout }}</td>
                        <td>
                            <form method="POST" action="{{ route('librarian.collab-zones.checkout', $attendance) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">
                                    Check Out
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Completed Today --}}
        @if($completedEntries->count() > 0)
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:10px;">
                Completed Today
                <span style="background:var(--text-muted);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $completedEntries->count() }} done</span>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedEntries as $attendance)
                    <tr>
                        <td style="font-weight:600;">{{ $attendance->user?->full_name }}</td>
                        <td style="color:var(--red-main);font-weight:700;">{{ $attendance->user?->student_id }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>{{ $attendance->check_out_time ?? $attendance->actual_checkout }}</td>
                        <td><span class="badge badge-active">Completed</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($zone->attendances->count() === 0)
        <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px;">
            No attendance records for today.
        </div>
        @endif
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