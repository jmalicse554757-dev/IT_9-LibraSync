@extends('layouts.librarian')

@section('title', 'Book Requests')
@section('page-title', 'Book Requests')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Book Requests</h1>
        <p style="color:var(--text-muted);font-size:13px;">Process borrow and return requests</p>
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
    <button class="tab active" id="tabPending"  onclick="switchTab('pending')">
        Pending
        @if($pending->count() > 0)
            <span style="background:var(--red-main);color:#fff;border-radius:20px;padding:1px 7px;font-size:9px;margin-left:5px;">{{ $pending->count() }}</span>
        @endif
    </button>
    <button class="tab" id="tabApproved" onclick="switchTab('approved')">Approved</button>
    <button class="tab" id="tabReturned" onclick="switchTab('returned')">Returned</button>
</div>

{{-- PENDING TAB --}}
<div id="panelPending">
    <div class="card">
        @if($pending->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Book</th>
                    <th>Book ID</th>
                    <th>Date Requested</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $borrowing)
                <tr>
                    <td style="font-weight:600;">{{ $borrowing->user?->full_name }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $borrowing->user?->student_id }}</td>
                    <td>{{ $borrowing->book?->title }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->book?->book_id }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('librarian.book-requests.approve', $borrowing) }}">
                                @csrf
                                <button type="submit" class="btn btn-approve btn-sm">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('librarian.book-requests.decline', $borrowing) }}">
                                @csrf
                                <button type="submit" class="btn btn-decline btn-sm">Decline</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:32px;margin-bottom:12px;">📭</div>
            <div style="font-size:14px;font-weight:600;">No pending requests</div>
        </div>
        @endif
    </div>
</div>

{{-- APPROVED TAB --}}
<div id="panelApproved" style="display:none;">
    <div style="background:rgba(232,213,196,0.3);border:1px solid var(--border);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:12px;color:var(--text-muted);">
        Active Loans: Status is auto-tracked — 
        <span style="background:rgba(39,174,96,0.1);color:#27ae60;padding:2px 8px;border-radius:20px;font-weight:700;">Active</span> while within due date, 
        <span style="background:rgba(230,126,34,0.1);color:#e67e22;padding:2px 8px;border-radius:20px;font-weight:700;">Due Today</span> on the last day, 
        <span style="background:rgba(192,57,43,0.1);color:#c0392b;padding:2px 8px;border-radius:20px;font-weight:700;">Overdue</span> past due date.
    </div>
    <div class="card">
        @if($approved->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Book</th>
                    <th>Book ID</th>
                    <th>Date Borrowed</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approved as $borrowing)
                @php $loanStatus = $borrowing->status; @endphp
                <tr>
                    <td style="color:var(--red-main);font-weight:700;">{{ $borrowing->receipt_no }}</td>
                    <td style="font-weight:600;">{{ $borrowing->user?->full_name }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->user?->student_id }}</td>
                    <td>{{ $borrowing->book?->title }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->book?->book_id }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->date_borrowed?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->due_date?->format('M d, Y') }}</td>
                    <td>
                        @if($loanStatus === 'overdue')
                            <span class="badge badge-overdue">Overdue</span>
                        @elseif($loanStatus === 'due today')
                            <span class="badge badge-due">Due Today</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <button class="btn btn-sm" onclick="openModal('receipt-{{ $borrowing->id }}')"
                                style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">
                                Receipt
                            </button>
                            <button class="btn btn-approve btn-sm" onclick="openModal('return-{{ $borrowing->id }}')">
                                Confirm Return
                            </button>
                            @if($loanStatus === 'overdue')
                            <button class="btn btn-sm" style="background:rgba(192,57,43,0.08);color:#c0392b;border:1px solid rgba(192,57,43,0.2);">
                                Send Reminder
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <div style="font-size:32px;margin-bottom:12px;">📚</div>
            <div style="font-size:14px;font-weight:600;">No active loans</div>
        </div>
        @endif
    </div>
</div>

{{-- RETURNED TAB --}}
<div id="panelReturned" style="display:none;">
    <div class="card">
        @if($returned->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Book</th>
                    <th>Returned On</th>
                    <th>Loan Days</th>
                    <th>Condition</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returned as $borrowing)
                @php
                    $isLate = $borrowing->date_returned->gt($borrowing->due_date);
                @endphp
                <tr>
                    <td style="color:var(--red-main);font-weight:700;">{{ $borrowing->receipt_no }}</td>
                    <td style="font-weight:600;">{{ $borrowing->user?->full_name }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->user?->student_id }}</td>
                    <td>{{ $borrowing->book?->title }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->date_returned?->format('M d, Y') }}</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->school_days_loan }} school days</td>
                    <td style="color:var(--text-muted);">{{ $borrowing->book_condition ?? 'N/A' }}</td>
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
            <div style="font-size:32px;margin-bottom:12px;">📋</div>
            <div style="font-size:14px;font-weight:600;">No returned books yet</div>
        </div>
        @endif
    </div>
</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- RECEIPT MODALS --}}
@foreach($approved as $borrowing)
@php $loanStatus = $borrowing->status; @endphp
<div class="modal-overlay" id="receipt-{{ $borrowing->id }}">
    <div class="modal" style="max-width:560px;">
        <button class="modal-close" onclick="closeModal('receipt-{{ $borrowing->id }}')">✕</button>

        {{-- Receipt Header --}}
        <div class="receipt-header">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div class="receipt-label">Official Borrow Receipt</div>
                    <div style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#fff;margin-top:4px;">
                        {{ $borrowing->book?->title }}
                    </div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.5);margin-top:4px;">Book ID: {{ $borrowing->book?->book_id }}</div>
                </div>
                <div style="text-align:right;">
                    <div class="receipt-label">Receipt No.</div>
                    <div style="font-size:14px;font-weight:700;color:#fff;">{{ $borrowing->receipt_no }}</div>
                </div>
            </div>
        </div>

        {{-- Receipt Details --}}
        <div class="receipt-grid">
            <div class="receipt-field">
                <div class="receipt-label">Borrower</div>
                <div class="receipt-value">{{ $borrowing->user?->full_name }}</div>
            </div>
            <div class="receipt-field">
                <div class="receipt-label">Student ID</div>
                <div class="receipt-value">{{ $borrowing->user?->student_id }}</div>
            </div>
            <div class="receipt-field">
                <div class="receipt-label">Date Borrowed</div>
                <div class="receipt-value">{{ $borrowing->date_borrowed?->format('M d, Y') }}</div>
            </div>
            <div class="receipt-field">
                <div class="receipt-label">Due Date</div>
                <div class="receipt-value">{{ $borrowing->due_date?->format('M d, Y') }}</div>
            </div>
            <div class="receipt-field">
                <div class="receipt-label">Loan Period</div>
                <div class="receipt-value">{{ $borrowing->school_days_loan }} School Days</div>
            </div>
            <div class="receipt-field">
                <div class="receipt-label">Status</div>
                <div class="receipt-value">
                    @if($loanStatus === 'overdue')
                        <span class="badge badge-overdue">Overdue</span>
                    @elseif($loanStatus === 'due today')
                        <span class="badge badge-due">Due Today</span>
                    @else
                        <span class="badge badge-active">Active</span>
                    @endif
                </div>
            </div>
        </div>

        @if($loanStatus === 'overdue')
        <div style="background:rgba(192,57,43,0.07);border:1px solid rgba(192,57,43,0.2);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#c0392b;font-weight:600;">
            This book is overdue. Student must return it immediately.
        </div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;gap:8px;">
            <button class="btn btn-approve" style="flex:1;" onclick="openModal('return-{{ $borrowing->id }}');closeModal('receipt-{{ $borrowing->id }}')">
                Confirm Physical Return
            </button>
            <button class="btn" onclick="printReceipt('receipt-print-{{ $borrowing->id }}')"
                style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">
                Print
            </button>
        </div>

        {{-- Print area (hidden) --}}
        <div id="receipt-print-{{ $borrowing->id }}" style="display:none;">
            <h3>LibraSync Library Management System</h3>
            <p>Receipt No: {{ $borrowing->receipt_no }}</p>
            <p>Book: {{ $borrowing->book?->title }}</p>
            <p>Borrower: {{ $borrowing->user?->full_name }}</p>
            <p>Student ID: {{ $borrowing->user?->student_id }}</p>
            <p>Date Borrowed: {{ $borrowing->date_borrowed?->format('M d, Y') }}</p>
            <p>Due Date: {{ $borrowing->due_date?->format('M d, Y') }}</p>
            <p>Loan Period: {{ $borrowing->school_days_loan }} School Days</p>
            <p>LibraSync Library Management System · Librarian copy</p>
        </div>

        <div style="text-align:center;margin-top:16px;padding-top:16px;border-top:1px dashed var(--border);font-size:10px;color:var(--text-muted);">
            LibraSync Library Management System · Librarian copy
        </div>
    </div>
</div>

{{-- CONFIRM RETURN MODAL --}}
<div class="modal-overlay" id="return-{{ $borrowing->id }}">
    <div class="modal" style="max-width:500px;">
        <button class="modal-close" onclick="closeModal('return-{{ $borrowing->id }}')">✕</button>
        <div style="text-align:center;margin-bottom:20px;">
            <div style="width:56px;height:56px;background:rgba(39,174,96,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <svg width="24" height="24" fill="none" stroke="#27ae60" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="modal-title" style="margin-bottom:4px;">Confirm Physical Return</div>
            <div style="font-size:12px;color:var(--text-muted);">Receipt No.: <span style="color:var(--red-main);font-weight:700;">{{ $borrowing->receipt_no }}</span></div>
            <div style="font-size:12px;color:var(--text-muted);">Book: <span style="font-weight:700;color:var(--maroon-deep);">{{ $borrowing->book?->title }}</span></div>
        </div>

        <form method="POST" action="{{ route('librarian.book-requests.return', $borrowing) }}">
            @csrf
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Book Condition</label>
                <select name="book_condition" required style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                    <option value="">Select condition</option>
                    <option value="Good — No damage">Good — No damage</option>
                    <option value="Slightly worn">Slightly worn</option>
                    <option value="Damaged — Pages torn">Damaged — Pages torn</option>
                    <option value="Damaged — Cover worn">Damaged — Cover worn</option>
                    <option value="Lost">Lost</option>
                </select>
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Remarks (Optional)</label>
                <textarea name="remarks" rows="3" placeholder="Add any notes about the return..."
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;resize:none;"></textarea>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" class="btn" onclick="closeModal('return-{{ $borrowing->id }}')"
                    style="background:var(--cream);border:1px solid var(--border);color:var(--maroon-deep);">Cancel</button>
                <button type="submit" class="btn btn-approve" style="flex:1;">Confirm & Mark as Returned</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
function switchTab(tab) {
    ['pending','approved','returned'].forEach(t => {
        document.getElementById('panel' + t.charAt(0).toUpperCase() + t.slice(1)).style.display = t === tab ? '' : 'none';
        document.getElementById('tab'   + t.charAt(0).toUpperCase() + t.slice(1)).classList.toggle('active', t === tab);
    });
}

function printReceipt(id) {
    const content = document.getElementById(id).innerHTML;
    const win = window.open('', '_blank');
    win.document.write(`
        <html><head><title>Receipt</title>
        <style>
            body { font-family: 'Lato', sans-serif; padding: 40px; color: #1a0000; }
            h3 { font-size: 18px; margin-bottom: 20px; color: #3b0000; }
            p { margin: 8px 0; font-size: 14px; }
            hr { border: none; border-top: 1px dashed #ccc; margin: 20px 0; }
        </style>
        </head><body>${content}</body></html>
    `);
    win.document.close();
    win.print();
}
</script>
@endsection