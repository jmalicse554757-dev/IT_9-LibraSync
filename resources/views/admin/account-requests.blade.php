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

{{-- SEARCH --}}
<form method="GET" action="{{ route('admin.account-requests') }}" style="margin-bottom:18px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:220px;">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);opacity:0.4;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, student ID, or email..."
                style="width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;background:var(--white);color:var(--text-dark);outline:none;font-family:'Lato',sans-serif;">
        </div>
        <button type="submit"
            style="padding:10px 20px;background:var(--maroon-deep);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">
            Search
        </button>
        @if($search)
        <a href="{{ route('admin.account-requests') }}"
            style="padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;">
            Clear
        </a>
        @endif
    </div>
</form>

{{-- TABS --}}
<div class="tabs">
    <button class="tab active" id="tabStudents" onclick="switchTab('students')">
        Students
        @if($pendingStudents->total() > 0)
            <span style="background:var(--red-main);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pendingStudents->total() }}</span>
        @endif
    </button>
    <button class="tab" id="tabLibrarians" onclick="switchTab('librarians')">
        Librarians
        @if($pendingLibrarians->total() > 0)
            <span style="background:var(--red-main);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pendingLibrarians->total() }}</span>
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

        {{-- STUDENTS PAGINATION --}}
        @if($pendingStudents->hasPages())
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;flex-wrap:wrap;gap:10px;">
            <div style="font-size:13px;color:var(--text-muted);">
                Showing <strong>{{ $pendingStudents->firstItem() }}</strong> - <strong>{{ $pendingStudents->lastItem() }}</strong>
                of <strong>{{ $pendingStudents->total() }}</strong> records
            </div>
            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                @if($pendingStudents->onFirstPage())
                    <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $pendingStudents->previousPageUrl() }}"
                       style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">‹ Prev</a>
                @endif
                @foreach($pendingStudents->getUrlRange(1, $pendingStudents->lastPage()) as $page => $url)
                    @if($page == $pendingStudents->currentPage())
                        <span style="padding:7px 13px;border-radius:8px;background:var(--maroon-deep);color:#fff;font-size:13px;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           style="padding:7px 13px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($pendingStudents->hasMorePages())
                    <a href="{{ $pendingStudents->nextPageUrl() }}"
                       style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">Next ›</a>
                @else
                    <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">Next ›</span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
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

        {{-- LIBRARIANS PAGINATION --}}
        @if($pendingLibrarians->hasPages())
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;flex-wrap:wrap;gap:10px;">
            <div style="font-size:13px;color:var(--text-muted);">
                Showing <strong>{{ $pendingLibrarians->firstItem() }}</strong> - <strong>{{ $pendingLibrarians->lastItem() }}</strong>
                of <strong>{{ $pendingLibrarians->total() }}</strong> records
            </div>
            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                @if($pendingLibrarians->onFirstPage())
                    <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">‹ Prev</span>
                @else
                    <a href="{{ $pendingLibrarians->previousPageUrl() }}"
                       style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">‹ Prev</a>
                @endif
                @foreach($pendingLibrarians->getUrlRange(1, $pendingLibrarians->lastPage()) as $page => $url)
                    @if($page == $pendingLibrarians->currentPage())
                        <span style="padding:7px 13px;border-radius:8px;background:var(--maroon-deep);color:#fff;font-size:13px;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           style="padding:7px 13px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($pendingLibrarians->hasMorePages())
                    <a href="{{ $pendingLibrarians->nextPageUrl() }}"
                       style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">Next ›</a>
                @else
                    <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">Next ›</span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
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
    document.getElementById('panelStudents').style.display  = tab === 'students'   ? '' : 'none';
    document.getElementById('panelLibrarians').style.display = tab === 'librarians' ? '' : 'none';
    document.getElementById('tabStudents').classList.toggle('active',   tab === 'students');
    document.getElementById('tabLibrarians').classList.toggle('active', tab === 'librarians');
}
</script>
@endsection