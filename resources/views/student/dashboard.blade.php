@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">
        Welcome back, {{ auth()->user()->first_name }}!
    </h1>
    <p style="color:var(--text-muted);font-size:13px;margin-top:3px;">Here's your library activity overview.</p>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card" onclick="openModal('modalBorrowed')">
        <div class="stat-card-top">
            <div class="stat-label">Books Borrowed</div>
            <div class="stat-sub"><span>Click to view</span></div>
        </div>
        <div class="stat-value">{{ $booksBorrowed }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalDueSoon')">
        <div class="stat-card-top">
            <div class="stat-label">Due Soon</div>
            <div class="stat-sub">
                @if($dueSoon > 0)
                    <span>Within 2 days</span> · Click to view
                @else
                    No upcoming due dates
                @endif
            </div>
        </div>
        <div class="stat-value">{{ $dueSoon }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalBooksRead')">
        <div class="stat-card-top">
            <div class="stat-label">Books Read</div>
            <div class="stat-sub"><span>Click to view history</span></div>
        </div>
        <div class="stat-value">{{ $booksRead }}</div>
    </div>
</div>

{{-- CURRENTLY BORROWED + ANNOUNCEMENTS --}}
<div class="grid-2" style="margin-bottom:16px;">

    {{-- Currently Borrowed --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Currently Borrowed</div>
            <a href="{{ route('student.borrowed-books') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">View all</a>
        </div>
        @forelse($currentlyBorrowed as $borrowing)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $borrowing->book?->title }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Due {{ $borrowing->due_date?->format('M d, Y') ?? 'N/A' }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;margin-left:10px;">
                @if($borrowing->status === 'overdue')
                    <span class="badge badge-overdue">Overdue</span>
                @elseif($borrowing->status === 'due today')
                    <span class="badge badge-due">Due Today</span>
                @else
                    <span class="badge badge-active">Active</span>
                @endif
                <button class="btn btn-sm btn-primary" onclick="event.stopPropagation();openModal('returnModal')">Return</button>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:var(--text-muted);">
            <div style="font-size:13px;font-weight:600;margin-bottom:8px;">No books currently borrowed</div>
            <a href="{{ route('student.browse-books') }}" style="font-size:12px;color:var(--red-main);font-weight:700;text-decoration:none;">Browse Books</a>
        </div>
        @endforelse
    </div>

    {{-- Library Announcements --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title" style="margin-bottom:0;">Library Announcements</div>
            <a href="{{ route('student.announcements') }}" style="font-size:11px;color:var(--red-main);font-weight:700;text-decoration:none;">View all</a>
        </div>
        @forelse($announcements as $announcement)
        <div style="padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:3px;">{{ $announcement->title }}</div>
            <div style="font-size:11px;color:var(--text-muted);line-height:1.5;margin-bottom:4px;">{{ Str::limit($announcement->body, 80) }}</div>
            <div style="font-size:10px;color:var(--text-muted);">
                {{ $announcement->author?->full_name ?? 'Library Staff' }} ·
                @if($announcement->created_at->isToday())
                    Today
                @elseif($announcement->created_at->isYesterday())
                    Yesterday
                @else
                    {{ $announcement->created_at->format('M d') }}
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px;">No announcements yet.</div>
        @endforelse
    </div>

</div>

{{-- PENALTIES WIDGET --}}
@php
    $activePenalties = \App\Models\Penalty::where('user_id', auth()->id())
        ->where('status', 'unpaid')
        ->with('borrowing.book')
        ->get();
@endphp
@if($activePenalties->count() > 0)
<div class="card" style="border-left:4px solid var(--red-bright);margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <div>
            <div class="card-title" style="margin-bottom:2px;color:var(--red-bright);">Outstanding Penalties</div>
            <div style="font-size:11px;color:var(--text-muted);">Please settle your penalties at the library desk</div>
        </div>
        <div style="font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:var(--red-bright);">
            &#x20B1;{{ number_format($activePenalties->sum('amount'), 2) }}
        </div>
    </div>
    <table class="tbl">
        <thead>
            <tr><th>Book</th><th>Days Overdue</th><th>Amount</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($activePenalties as $penalty)
            <tr>
                <td style="font-weight:600;">{{ $penalty->borrowing?->book?->title ?? 'N/A' }}</td>
                <td>{{ $penalty->days_overdue ?? '—' }} days</td>
                <td style="color:var(--red-bright);font-weight:700;">&#x20B1;{{ number_format($penalty->amount, 2) }}</td>
                <td><span class="badge badge-overdue">Unpaid</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection

@section('modals')

{{-- BOOKS BORROWED MODAL --}}
<div class="modal-overlay" id="modalBorrowed">
    <div class="modal" style="max-width:600px;">
        <button class="modal-close" onclick="closeModal('modalBorrowed')">&#x2715;</button>
        <div class="modal-title">Currently Borrowed</div>
        @if($currentlyBorrowed->count() > 0)
        <table class="tbl">
            <thead>
                <tr><th>Book Title</th><th>Date Borrowed</th><th>Due Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($currentlyBorrowed as $borrowing)
                <tr>
                    <td style="font-weight:600;">{{ $borrowing->book?->title }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->date_borrowed?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->due_date?->format('M d, Y') }}</td>
                    <td>
                        @if($borrowing->status === 'overdue')
                            <span class="badge badge-overdue">Overdue</span>
                        @elseif($borrowing->status === 'due today')
                            <span class="badge badge-due">Due Today</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:30px;color:var(--text-muted);">
            <div style="font-size:13px;">No books currently borrowed.</div>
        </div>
        @endif
    </div>
</div>

{{-- DUE SOON MODAL --}}
<div class="modal-overlay" id="modalDueSoon">
    <div class="modal" style="max-width:600px;">
        <button class="modal-close" onclick="closeModal('modalDueSoon')">&#x2715;</button>
        <div class="modal-title">Due Soon</div>
        @php
            $dueSoonBooks = $currentlyBorrowed->filter(function($b) {
                return $b->due_date
                    && $b->due_date->lte(\Carbon\Carbon::today()->addDays(2))
                    && $b->due_date->gte(\Carbon\Carbon::today());
            });
        @endphp
        @if($dueSoonBooks->count() > 0)
        <table class="tbl">
            <thead>
                <tr><th>Book Title</th><th>Due Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($dueSoonBooks as $borrowing)
                <tr>
                    <td style="font-weight:600;">{{ $borrowing->book?->title }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $borrowing->due_date?->format('M d, Y') }}</td>
                    <td><span class="badge badge-due">Due Soon</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:30px;color:var(--text-muted);">
            <div style="font-size:13px;">No books due within 2 days.</div>
        </div>
        @endif
    </div>
</div>

{{-- BOOKS READ MODAL --}}
<div class="modal-overlay" id="modalBooksRead">
    <div class="modal" style="max-width:500px;">
        <button class="modal-close" onclick="closeModal('modalBooksRead')">&#x2715;</button>
        <div class="modal-title">Books Read</div>
        @if($booksRead > 0)
        <div style="text-align:center;padding:20px 0;">
            <div style="font-family:'Playfair Display',serif;font-size:64px;font-weight:700;color:var(--maroon-deep);line-height:1;">{{ $booksRead }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:8px;">books returned so far</div>
            <a href="{{ route('student.records') }}"
                style="display:inline-block;margin-top:20px;padding:10px 24px;background:var(--maroon-deep);color:#fff;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">
                View Full History
            </a>
        </div>
        @else
        <div style="text-align:center;padding:30px;color:var(--text-muted);">
            <div style="font-size:13px;">No books returned yet.</div>
            <a href="{{ route('student.browse-books') }}"
                style="font-size:12px;color:var(--red-main);font-weight:700;text-decoration:none;margin-top:8px;display:inline-block;">
                Browse Books
            </a>
        </div>
        @endif
    </div>
</div>

{{-- RETURN INSTRUCTIONS MODAL --}}
<div class="modal-overlay" id="returnModal">
    <div class="modal" style="text-align:center;">
        <button class="modal-close" onclick="closeModal('returnModal')">&#x2715;</button>
        <div style="width:56px;height:56px;background:rgba(107,0,0,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="24" height="24" fill="none" stroke="var(--maroon-mid)" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div class="modal-title" style="text-align:center;">How to Return a Book</div>
        <p style="font-size:13px;color:var(--text-muted);line-height:1.7;margin-bottom:20px;">
            Bring the physical book to the <strong style="color:var(--maroon-deep);">librarian's desk</strong>. The librarian will scan/confirm your receipt and mark it as returned.
            <br><br>
            <strong style="color:var(--maroon-deep);">No need to request online</strong> — just walk in!
        </p>
        <button class="btn btn-primary" onclick="closeModal('returnModal')" style="width:100%;">Got it</button>
    </div>
</div>

@endsection