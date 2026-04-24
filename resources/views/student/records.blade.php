@extends('layouts.student')

@section('title', 'Records & History')
@section('page-title', 'Records & History')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Records & History</h1>
    <p style="color:var(--text-muted);font-size:13px;">Complete borrowing activity log</p>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Total Borrowed</div>
            <div class="stat-sub"><span>All time</span></div>
        </div>
        <div class="stat-value">{{ $totalBorrowed }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">On Time</div>
            <div class="stat-sub"><span>Returned on or before due date</span></div>
        </div>
        <div class="stat-value">{{ $onTime }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Late Returns</div>
            <div class="stat-sub"><span>Returned after due date</span></div>
        </div>
        <div class="stat-value">{{ $lateReturns }}</div>
    </div>
</div>

{{-- HISTORY TABLE --}}
<div class="card">
    <div class="card-title">Borrowing History</div>
    @if($history->count() > 0)
    <table class="tbl">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Book</th>
                <th>Borrowed</th>
                <th>Due Date</th>
                <th>Returned</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $record)
            @php
                $isLate = $record->date_returned->gt($record->due_date);
            @endphp
            <tr>
                <td style="color:var(--red-main);font-weight:700;">{{ $record->book?->book_id }}</td>
                <td style="font-weight:600;">{{ $record->book?->title }}</td>
                <td style="color:var(--text-muted);">{{ $record->date_borrowed?->format('M d, Y') }}</td>
                <td style="color:var(--text-muted);">{{ $record->due_date?->format('M d, Y') }}</td>
                <td style="color:var(--text-muted);">{{ $record->date_returned?->format('M d, Y') }}</td>
                <td>
                    @if($isLate)
                        <span class="badge badge-overdue">Late</span>
                    @else
                        <span class="badge badge-active">On Time</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center;padding:40px;color:var(--text-muted);">
        <div style="font-size:32px;margin-bottom:12px;"></div>
        <div style="font-size:14px;font-weight:600;">No borrowing history yet</div>
        <div style="font-size:12px;margin-top:4px;">Your returned books will appear here</div>
        <a href="{{ route('student.browse-books') }}" style="font-size:12px;color:var(--red-main);font-weight:700;text-decoration:none;margin-top:10px;display:inline-block;">Browse Books →</a>
    </div>
    @endif
</div>

@endsection