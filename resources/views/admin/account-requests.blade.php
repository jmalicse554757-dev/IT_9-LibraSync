@extends('layouts.admin')

@section('title', 'Account Requests')
@section('page-title', 'Account Requests')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Account Requests</h1>
        <p style="color:var(--text-muted);font-size:13px;">Review and approve applications</p>
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
    <button class="tab active" id="tabStudents" onclick="switchTab('students')">
        Students
        @if($pendingStudents->count() > 0)
            <span style="background:var(--red-main);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pendingStudents->count() }}</span>
        @endif
    </button>
    <button class="tab" id="tabLibrarians" onclick="switchTab('librarians')">
        Librarians
        @if($pendingLibrarians->count() > 0)
            <span style="background:var(--red-main);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pendingLibrarians->count() }}</span>
        @endif
    </button>
</div>

{{-- STUDENTS TAB --}}
<div id="panelStudents">
    <div class="card">
        @if($pendingStudents->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Program</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingStudents as $student)
                <tr>
                    <td style="font-weight:600;">{{ $student->full_name }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $student->student_id }}</td>
                    <td>
                        <span class="prog-badge prog-cce">{{ $student->program }}</span>
                    </td>
                    <td style="color:var(--text-muted);">{{ $student->email }}</td>
                    <td style="color:var(--text-muted);">{{ $student->created_at->format('M d, Y') }}</td>
                    <td><span class="badge badge-pending">Pending</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('admin.account-requests.approve', $student) }}">
                                @csrf
                                <button type="submit" class="btn btn-approve">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.account-requests.reject', $student) }}">
                                @csrf
                                <button type="submit" class="btn btn-reject">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:32px;margin-bottom:12px;">✅</div>
            <div style="font-size:14px;font-weight:600;">No pending student requests</div>
            <div style="font-size:12px;margin-top:4px;">All student applications have been reviewed</div>
        </div>
        @endif
    </div>
</div>

{{-- LIBRARIANS TAB --}}
<div id="panelLibrarians" style="display:none;">
    <div class="card">
        @if($pendingLibrarians->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingLibrarians as $librarian)
                <tr>
                    <td style="font-weight:600;">{{ $librarian->full_name }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $librarian->employee_id ?? 'N/A' }}</td>
                    <td>{{ $librarian->program ?? 'Librarian' }}</td>
                    <td style="color:var(--text-muted);">{{ $librarian->email }}</td>
                    <td style="color:var(--text-muted);">{{ $librarian->created_at->format('M d, Y') }}</td>
                    <td><span class="badge badge-pending">Pending</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('admin.account-requests.approve', $librarian) }}">
                                @csrf
                                <button type="submit" class="btn btn-approve">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.account-requests.reject', $librarian) }}">
                                @csrf
                                <button type="submit" class="btn btn-reject">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:32px;margin-bottom:12px;">✅</div>
            <div style="font-size:14px;font-weight:600;">No pending librarian requests</div>
            <div style="font-size:12px;margin-top:4px;">All librarian applications have been reviewed</div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
function switchTab(tab) {
    document.getElementById('panelStudents').style.display = tab === 'students' ? '' : 'none';
    document.getElementById('panelLibrarians').style.display = tab === 'librarians' ? '' : 'none';
    document.getElementById('tabStudents').classList.toggle('active', tab === 'students');
    document.getElementById('tabLibrarians').classList.toggle('active', tab === 'librarians');
}
</script>
@endsection