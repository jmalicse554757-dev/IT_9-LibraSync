@extends('layouts.librarian')

@section('title', 'Dashboard')
@section('page-title', 'Librarian Dashboard')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Librarian Dashboard</h1>
        <p style="color:var(--text-muted);font-size:13px;margin-top:3px;">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->first_name }}!</p>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card" onclick="openModal('modal-books-catalog')">
        <div class="stat-card-top">
            <div class="stat-label">Books in Catalog</div>
            <div class="stat-sub"><span>Click to view</span></div>
        </div>
        <div class="stat-value">{{ number_format($totalBooks) }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modal-currently-borrowed')">
        <div class="stat-card-top">
            <div class="stat-label">Currently Borrowed</div>
            <div class="stat-sub">
                @if($overdueCount > 0)
                    <span style="color:#e67e22;">{{ $overdueCount }} overdue</span> · Click to view
                @else
                    <span>Click to view</span>
                @endif
            </div>
        </div>
        <div class="stat-value">{{ $currentlyBorrowed }}</div>
    </div>
    <div class="stat-card" onclick="window.location='{{ route('librarian.book-requests') }}'">
        <div class="stat-card-top">
            <div class="stat-label">Book Requests</div>
            <div class="stat-sub"><span>Needs action</span> · Click to view</div>
        </div>
        <div class="stat-value">{{ $pendingBorrowings->count() }}</div>
    </div>
    <div class="stat-card" onclick="window.location='{{ route('librarian.collab-zones') }}'">
        <div class="stat-card-top">
            <div class="stat-label">Room Requests</div>
            <div class="stat-sub"><span>Pending</span> · Click to view</div>
        </div>
        <div class="stat-value">{{ $roomRequests }}</div>
    </div>
</div>

{{-- PENDING REQUESTS --}}
<div class="grid-2" style="margin-bottom:16px;">

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Pending Book Requests</div>
            <a href="{{ route('librarian.book-requests') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">View all</a>
        </div>
        @forelse($pendingBorrowings as $borrowing)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);">{{ $borrowing->user?->full_name ?? 'N/A' }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $borrowing->book?->title ?? 'N/A' }} · {{ $borrowing->created_at->format('M d') }}</div>
            </div>
            <form method="POST" action="{{ route('librarian.book-requests.approve', $borrowing) }}">
                @csrf
                <button type="submit" class="btn btn-approve btn-sm">Approve</button>
            </form>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:13px;">No pending requests</div>
        @endforelse
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Pending Room Requests</div>
            <a href="{{ route('librarian.collab-zones') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">View all</a>
        </div>
        @forelse($pendingRooms as $room)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);">{{ $room->user?->full_name ?? 'N/A' }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $room->room?->name ?? 'N/A' }} · {{ $room->date?->format('M d') }} · {{ $room->time_slot }}</div>
            </div>
            <form method="POST" action="{{ route('librarian.collab-zones.approve', $room) }}">
                @csrf
                <button type="submit" class="btn btn-approve btn-sm">Approve</button>
            </form>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:13px;">No pending room requests</div>
        @endforelse
    </div>

</div>

{{-- OVERDUE + REST ZONES --}}
<div class="grid-2">

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Overdue Books</div>
            <a href="{{ route('librarian.book-requests') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">View all</a>
        </div>
        @forelse($overdueBooks as $borrowing)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);">{{ $borrowing->book?->title ?? 'N/A' }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $borrowing->user?->full_name ?? 'N/A' }}</div>
            </div>
            @if($borrowing->due_date)
                <span class="badge badge-overdue">{{ $borrowing->due_date->diffInDays(now()) }}d overdue</span>
            @else
                <span class="badge badge-overdue">Overdue</span>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:#27ae60;font-size:13px;font-weight:700;">No overdue books</div>
        @endforelse
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Rest Zone Attendance Today</div>
            <a href="{{ route('librarian.collab-zones') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">Manage</a>
        </div>
        @forelse($restZones as $zone)
        @php
            $todayCount = $zone->attendances()->whereDate('created_at', today())->whereNull('check_out_time')->count();
        @endphp
        <div style="padding:12px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);">{{ $zone->name }}</div>
                <span style="font-size:11px;font-weight:700;color:var(--text-muted);">{{ $todayCount }}/{{ $zone->capacity }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $zone->capacity > 0 ? min(($todayCount / $zone->capacity) * 100, 100) : 0 }}%"></div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:13px;">No rest zones found</div>
        @endforelse
    </div>

</div>

@endsection

@section('modals')

<div class="modal-overlay" id="modal-books-catalog">
    <div class="modal" style="max-width:680px;">
        <button class="modal-close" onclick="closeModal('modal-books-catalog')">&#x2715;</button>
        <div class="modal-title">Books in Catalog</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:18px;">
            <div class="modal-stat">
                <div class="modal-stat-label">Total Titles</div>
                <div class="modal-stat-value">{{ $totalBooks }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Available</div>
                <div class="modal-stat-value">{{ $availableBooks }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Low Stock</div>
                <div class="modal-stat-value">{{ $lowStockBooks }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Unavailable</div>
                <div class="modal-stat-value">{{ $unavailableBooks }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Book ID</th><th>Title</th><th>Stock</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($recentBooks as $book)
                <tr>
                    <td style="color:var(--red-main);font-weight:700;">{{ $book->book_id }}</td>
                    <td style="font-weight:600;">{{ $book->title }}</td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        @if($book->stock > 2)
                            <span class="badge badge-active">Available</span>
                        @elseif($book->stock > 0)
                            <span class="badge badge-pending">Low Stock</span>
                        @else
                            <span class="badge badge-rejected">Unavailable</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="modal-currently-borrowed">
    <div class="modal" style="max-width:720px;">
        <button class="modal-close" onclick="closeModal('modal-currently-borrowed')">&#x2715;</button>
        <div class="modal-title">Currently Borrowed</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:18px;">
            <div class="modal-stat">
                <div class="modal-stat-label">Currently Borrowed</div>
                <div class="modal-stat-value">{{ $currentlyBorrowed }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Overdue</div>
                <div class="modal-stat-value">{{ $overdueCount }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Due This Week</div>
                <div class="modal-stat-value">{{ $dueSoonCount }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Book</th><th>Book ID</th><th>Borrowed By</th><th>Student ID</th><th>Due Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($borrowedBooks as $b)
                <tr>
                    <td style="font-weight:600;">{{ $b->book?->title ?? 'N/A' }}</td>
                    <td style="color:var(--red-main);font-weight:700;font-size:11px;">{{ $b->book?->book_id ?? 'N/A' }}</td>
                    <td>{{ $b->user?->full_name ?? 'N/A' }}</td>
                    <td style="color:var(--red-main);font-weight:700;font-size:11px;">{{ $b->user?->student_id ?? 'N/A' }}</td>
                    <td>{{ $b->due_date?->format('M d') ?? '—' }}</td>
                    <td>
                        @if(!$b->due_date)
                            <span class="badge badge-active">Active</span>
                        @elseif($b->due_date->lt(now()))
                            <span class="badge badge-overdue">Overdue</span>
                        @elseif($b->due_date->isToday())
                            <span class="badge badge-due">Due Today</span>
                        @elseif($b->due_date->diffInDays(now()) <= 3)
                            <span class="badge badge-due">Due Soon</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted);">No borrowed books currently</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection