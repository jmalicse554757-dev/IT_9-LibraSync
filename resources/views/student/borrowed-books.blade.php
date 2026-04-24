@extends('layouts.student')

@section('title', 'Borrowed Books')
@section('page-title', 'Borrowed Books')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Borrowed Books</h1>
    <p style="color:var(--text-muted);font-size:13px;">Loan period: 3 school days from date borrowed</p>
</div>

{{-- SUCCESS / ERROR --}}
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

{{-- PENALTY ALERT --}}
@if(isset($unpaidPenalties) && $unpaidPenalties->count() > 0)
<div style="background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.25);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:14px;">
    <div style="width:40px;height:40px;background:rgba(192,57,43,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#c0392b" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div style="flex:1;">
        <div style="font-size:13px;font-weight:700;color:#c0392b;">You have unpaid penalties!</div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Total amount due: <strong style="color:#c0392b;">₱{{ number_format($totalPenalty, 2) }}</strong> — Please settle at the librarian's desk.</div>
    </div>
    <button class="btn btn-sm" onclick="openModal('penaltyModal')"
        style="background:#c0392b;color:#fff;flex-shrink:0;">
        View Details
    </button>
</div>
@endif

{{-- BORROWING POLICY NOTICE --}}
<div style="background:#fffbf0;border:1px solid #f0d080;border-radius:10px;padding:13px 18px;margin-bottom:22px;font-size:12.5px;color:#7a5c00;display:flex;align-items:flex-start;gap:10px;">
    <span style="font-size:16px;flex-shrink:0;"></span>
    <div>
        <strong>Borrowing Policy:</strong> Each book has a <strong>3 school day</strong> loan period. Status updates automatically —
        <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(39,174,96,0.12);color:#27ae60;">Active</span>
        while within due date,
        <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(230,126,34,0.12);color:#e67e22;">Due Today</span>
        on the last day,
        <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(192,57,43,0.1);color:#c0392b;">Overdue</span>
        past due date. To return a book, bring it physically to the librarian's desk.
    </div>
</div>

{{-- ACTIVE BORROWINGS --}}
<div style="margin-bottom:28px;">
    <div style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
        <span>Active Requests & Borrowings</span>
        <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;background:var(--maroon-deep);color:#fff;">{{ $activeBorrowings->count() }}</span>
    </div>

    @if($activeBorrowings->count() === 0)
        <div class="card" style="text-align:center;padding:48px 20px;color:var(--text-muted);">
            <div style="font-size:36px;margin-bottom:12px;">📭</div>
            <div style="font-size:14px;font-weight:700;color:var(--maroon-deep);">No active requests</div>
            <div style="font-size:12px;margin-top:6px;">Browse books and submit a borrow request to get started</div>
            <a href="{{ route('student.browse-books') }}" class="btn btn-primary" style="display:inline-block;margin-top:16px;padding:10px 22px;font-size:12px;text-decoration:none;">Browse Books</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
            @foreach($activeBorrowings as $b)
            @php
                $now = \Carbon\Carbon::today();
                $due = $b->due_date ? \Carbon\Carbon::parse($b->due_date) : null;
                $isOverdue = $b->borrow_status === 'approved' && $due && $due->isPast() && !$due->isToday();
                $isDueToday = $b->borrow_status === 'approved' && $due && $due->isToday();
            @endphp
            <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column;border:1.5px solid {{ $isOverdue ? '#fca5a5' : ($isDueToday ? '#fcd34d' : 'var(--border)') }};">

                {{-- Card top strip --}}
                <div style="height:4px;background:{{ $isOverdue ? '#c0392b' : ($isDueToday ? '#e67e22' : ($b->borrow_status === 'pending' ? '#f59e0b' : '#27ae60')) }};"></div>

                <div style="padding:16px;display:flex;gap:14px;">
                    {{-- Book Cover --}}
                    <div style="width:60px;height:82px;border-radius:6px;overflow:hidden;flex-shrink:0;background:#f3f4f6;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;box-shadow:2px 2px 8px rgba(0,0,0,0.08);">
                        @if($b->book && $b->book->cover_image)
                            <img src="{{ asset('storage/' . $b->book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="text-align:center;">
                                <div style="font-size:22px;">📖</div>
                                <div style="font-size:8px;color:var(--text-muted);margin-top:2px;">No Cover</div>
                            </div>
                        @endif
                    </div>

                    {{-- Book Info --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;color:var(--maroon-deep);font-size:14px;line-height:1.3;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $b->book->title ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;">{{ $b->book->author ?? '—' }}</div>

                        <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px;">
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;background:#f3f4f6;color:var(--text-muted);">{{ $b->book->book_id ?? '—' }}</span>
                            @if($b->receipt_no)
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;background:#fef3c7;color:#92400e;">{{ $b->receipt_no }}</span>
                            @endif
                        </div>

                        {{-- Status Badge --}}
                        @if($b->borrow_status === 'pending')
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(245,158,11,0.1);color:#d97706;">⏳ Pending Approval</span>
                        @elseif($isOverdue)
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(192,57,43,0.1);color:#c0392b;">🔴 Overdue</span>
                        @elseif($isDueToday)
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(230,126,34,0.12);color:#e67e22;">⚠️ Due Today</span>
                        @else
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">✅ Active</span>
                        @endif
                    </div>
                </div>

                {{-- Penalty Badge --}}
                @if(isset($b->penalty) && $b->penalty)
                <div style="margin:0 16px 10px;padding:8px 12px;background:rgba(192,57,43,0.06);border:1px solid rgba(192,57,43,0.2);border-radius:8px;display:flex;justify-content:space-between;align-items:center;">
                    <div style="font-size:11px;color:#c0392b;font-weight:700;">
                        Penalty: {{ $b->penalty->overdue_days }} school day(s) overdue
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:13px;font-weight:700;color:#c0392b;">₱{{ number_format($b->penalty->amount, 2) }}</span>
                        @if($b->penalty->status === 'waived')
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(107,114,128,0.1);color:#6b7280;">Waived</span>
                        @elseif($b->penalty->status === 'paid')
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">Paid</span>
                        @else
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(192,57,43,0.1);color:#c0392b;">Unpaid</span>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Card Footer --}}
                <div style="padding:12px 16px;border-top:1px solid var(--border);background:#fafaf8;display:flex;justify-content:space-between;align-items:center;gap:10px;margin-top:auto;">
                    <div style="font-size:11px;color:var(--text-muted);">
                        @if($b->borrow_status === 'pending')
                            <span>Submitted {{ $b->created_at->diffForHumans() }}</span>
                        @else
                            <div>Borrowed: <strong style="color:var(--text-dark);">{{ $b->date_borrowed ? \Carbon\Carbon::parse($b->date_borrowed)->format('M d, Y') : '—' }}</strong></div>
                            <div style="margin-top:2px;">Due: <strong style="color:{{ $isOverdue ? '#c0392b' : ($isDueToday ? '#e67e22' : 'var(--text-dark)') }};">{{ $due ? $due->format('M d, Y') : '—' }}</strong>
                                @if($b->school_days_loan) <span style="color:var(--text-muted);">({{ $b->school_days_loan }} days)</span> @endif
                            </div>
                        @endif
                    </div>

                    <div style="display:flex;gap:7px;flex-shrink:0;">
                        @if($b->borrow_status !== 'pending')
                        <button onclick="openReceiptModal({{ $b->id }})" style="font-size:11px;font-weight:700;padding:6px 12px;border-radius:7px;border:1.5px solid var(--border);background:#fff;color:var(--maroon-deep);cursor:pointer;">View Receipt</button>
                        @endif
                        @if($b->borrow_status === 'pending')
                        <form method="POST" action="{{ route('student.borrowed-books.cancel', $b->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Cancel this borrow request?')" style="font-size:11px;font-weight:700;padding:6px 12px;border-radius:7px;border:1.5px solid #c0392b;background:none;color:#c0392b;cursor:pointer;">Cancel</button>
                        </form>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- HOW TO RETURN --}}
<div style="margin-top:18px;background:#f9fafb;border:1px solid var(--border);border-radius:10px;padding:13px 18px;font-size:12.5px;color:var(--text-muted);">
    <strong style="color:var(--text-dark);">How to Return:</strong> Bring the physical book to the librarian's desk. The librarian will scan/confirm your receipt and mark it as returned. No need to request — just walk in!
</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- PENALTY DETAILS MODAL --}}
@if(isset($unpaidPenalties) && $unpaidPenalties->count() > 0)
<div class="modal-overlay" id="penaltyModal">
    <div class="modal" style="max-width:540px;">
        <button class="modal-close" onclick="closeModal('penaltyModal')">✕</button>
        <div class="modal-title">Penalty Details</div>

        <div style="background:rgba(192,57,43,0.05);border:1px solid rgba(192,57,43,0.15);border-radius:10px;padding:14px;margin-bottom:18px;text-align:center;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Total Amount Due</div>
            <div style="font-family:'Playfair Display',serif;font-size:36px;font-weight:700;color:#c0392b;margin-top:4px;">₱{{ number_format($totalPenalty, 2) }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Please pay at the librarian's desk</div>
        </div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Overdue Days</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unpaidPenalties as $penalty)
                <tr>
                    <td style="font-weight:600;">{{ $penalty->borrowing?->book?->title }}</td>
                    <td style="color:var(--text-muted);">{{ $penalty->overdue_days }} school days</td>
                    <td style="color:#c0392b;font-weight:700;">₱{{ number_format($penalty->amount, 2) }}</td>
                    <td><span class="badge badge-overdue">Unpaid</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="background:var(--cream);border-radius:8px;padding:12px;margin-top:16px;font-size:12px;color:var(--text-muted);text-align:center;">
             Bring your receipt to the librarian's desk to settle your penalty.
        </div>

        <button class="btn btn-primary" style="width:100%;margin-top:16px;" onclick="closeModal('penaltyModal')">
            Close
        </button>
    </div>
</div>
@endif

{{-- RECEIPT MODAL --}}
<div class="modal-overlay" id="receiptModal">
    <div class="modal" style="max-width:460px;">
        <button class="modal-close" onclick="closeModal('receiptModal')">✕</button>

        <div style="text-align:center;margin-bottom:20px;">
            <div style="font-size:28px;margin-bottom:6px;"></div>
            <div class="modal-title" style="margin-bottom:4px;">Borrow Receipt</div>
            <div id="receiptNo" style="font-size:12px;font-weight:700;color:#92400e;letter-spacing:1px;"></div>
        </div>

        {{-- Book Info --}}
        <div style="display:flex;gap:14px;align-items:flex-start;margin-bottom:18px;padding:14px;background:var(--cream);border-radius:10px;border:1px solid var(--border);">
            <div id="receiptCover" style="width:54px;height:74px;border-radius:6px;overflow:hidden;flex-shrink:0;background:#f3f4f6;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:20px;"></div>
            <div>
                <div id="receiptTitle" style="font-weight:700;font-size:14px;color:var(--maroon-deep);margin-bottom:3px;"></div>
                <div id="receiptAuthor" style="font-size:12px;color:var(--text-muted);margin-bottom:4px;"></div>
                <div id="receiptBookId" style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:12px;background:#f3f4f6;color:var(--text-muted);display:inline-block;"></div>
            </div>
        </div>

        {{-- Details Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
            <div style="background:#f9fafb;border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Date Borrowed</div>
                <div id="receiptDateBorrowed" style="font-size:13px;font-weight:700;color:var(--text-dark);"></div>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Due Date</div>
                <div id="receiptDueDate" style="font-size:13px;font-weight:700;color:var(--text-dark);"></div>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">School Days</div>
                <div id="receiptSchoolDays" style="font-size:13px;font-weight:700;color:var(--text-dark);"></div>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Status</div>
                <div id="receiptStatus" style="font-size:13px;font-weight:700;"></div>
            </div>
            <div id="receiptReturnedBlock" style="background:#f9fafb;border-radius:8px;padding:10px 12px;display:none;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Date Returned</div>
                <div id="receiptDateReturned" style="font-size:13px;font-weight:700;color:var(--text-dark);"></div>
            </div>
            <div id="receiptConditionBlock" style="background:#f9fafb;border-radius:8px;padding:10px 12px;display:none;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px;">Book Condition</div>
                <div id="receiptCondition" style="font-size:13px;font-weight:700;color:var(--text-dark);"></div>
            </div>
        </div>

        {{-- Penalty Block in Receipt --}}
        <div id="receiptPenaltyBlock" style="background:rgba(192,57,43,0.06);border:1px solid rgba(192,57,43,0.2);border-radius:8px;padding:12px;margin-bottom:16px;display:none;">
            <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#c0392b;margin-bottom:8px;">Penalty Fee</div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div id="receiptPenaltyDays" style="font-size:12px;color:var(--text-muted);"></div>
                <div id="receiptPenaltyAmount" style="font-size:16px;font-weight:700;color:#c0392b;"></div>
            </div>
            <div id="receiptPenaltyStatus" style="margin-top:8px;"></div>
        </div>

        <div id="receiptRemarksBlock" style="background:#fff8e1;border:1px solid #f0d080;border-radius:8px;padding:10px 12px;margin-bottom:16px;display:none;">
            <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#92400e;margin-bottom:4px;">Remarks</div>
            <div id="receiptRemarks" style="font-size:12px;color:#7a5c00;"></div>
        </div>

        <div style="text-align:center;padding-top:12px;border-top:1px solid var(--border);">
            <div style="font-size:10px;color:var(--text-muted);">LibraSync University Library System</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const borrowings = {
    @foreach(array_merge($activeBorrowings->all(), $historyBorrowings->all()) as $b)
    {{ $b->id }}: {
        receiptNo: "{{ $b->receipt_no ?? '—' }}",
        title: @json($b->book->title ?? 'N/A'),
        author: @json($b->book->author ?? '—'),
        bookId: "{{ $b->book->book_id ?? '—' }}",
        cover: "{{ $b->book && $b->book->cover_image ? asset('storage/' . $b->book->cover_image) : '' }}",
        dateBorrowed: "{{ $b->date_borrowed ? \Carbon\Carbon::parse($b->date_borrowed)->format('M d, Y') : '—' }}",
        dueDate: "{{ $b->due_date ? \Carbon\Carbon::parse($b->due_date)->format('M d, Y') : '—' }}",
        schoolDays: "{{ $b->school_days_loan ? $b->school_days_loan . ' school days' : '—' }}",
        status: "{{ $b->borrow_status }}",
        dateReturned: "{{ $b->date_returned ? \Carbon\Carbon::parse($b->date_returned)->format('M d, Y') : '' }}",
        condition: "{{ $b->book_condition ?? '' }}",
        remarks: @json($b->remarks ?? ''),
        penaltyDays: "{{ $b->penalty ? $b->penalty->overdue_days . ' school day(s) overdue × ₱5.00' : '' }}",
        penaltyAmount: "{{ $b->penalty ? '₱' . number_format($b->penalty->amount, 2) : '' }}",
        penaltyStatus: "{{ $b->penalty ? $b->penalty->status : '' }}",
    },
    @endforeach
};

function openReceiptModal(id) {
    const d = borrowings[id];
    if (!d) return;

    document.getElementById('receiptNo').textContent = d.receiptNo;
    document.getElementById('receiptTitle').textContent = d.title;
    document.getElementById('receiptAuthor').textContent = d.author;
    document.getElementById('receiptBookId').textContent = d.bookId;
    document.getElementById('receiptDateBorrowed').textContent = d.dateBorrowed;
    document.getElementById('receiptDueDate').textContent = d.dueDate;
    document.getElementById('receiptSchoolDays').textContent = d.schoolDays;

    const coverEl = document.getElementById('receiptCover');
    if (d.cover) {
        coverEl.innerHTML = `<img src="${d.cover}" style="width:100%;height:100%;object-fit:cover;">`;
    } else {
        coverEl.innerHTML = '📖';
    }

    const statusMap = {
        approved: '<span style="color:#27ae60;">Approved</span>',
        pending:  '<span style="color:#d97706;">Pending</span>',
        returned: '<span style="color:#3b82f6;">Returned</span>',
        declined: '<span style="color:#c0392b;">Declined</span>',
    };
    document.getElementById('receiptStatus').innerHTML = statusMap[d.status] || d.status;

    const retBlock = document.getElementById('receiptReturnedBlock');
    const conBlock = document.getElementById('receiptConditionBlock');
    const remBlock = document.getElementById('receiptRemarksBlock');
    const penBlock = document.getElementById('receiptPenaltyBlock');

    retBlock.style.display = d.dateReturned ? 'block' : 'none';
    if (d.dateReturned) document.getElementById('receiptDateReturned').textContent = d.dateReturned;

    conBlock.style.display = d.condition ? 'block' : 'none';
    if (d.condition) document.getElementById('receiptCondition').textContent = d.condition;

    remBlock.style.display = d.remarks ? 'block' : 'none';
    if (d.remarks) document.getElementById('receiptRemarks').textContent = d.remarks;

    // Penalty block
    if (d.penaltyAmount) {
        penBlock.style.display = 'block';
        document.getElementById('receiptPenaltyDays').textContent = d.penaltyDays;
        document.getElementById('receiptPenaltyAmount').textContent = d.penaltyAmount;

        const statusBadges = {
            waived:  '<span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(107,114,128,0.1);color:#6b7280;">Waived by Librarian</span>',
            paid:    '<span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">Paid</span>',
            unpaid:  '<span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(192,57,43,0.1);color:#c0392b;">Unpaid — Pay at librarian\'s desk</span>',
        };
        document.getElementById('receiptPenaltyStatus').innerHTML = statusBadges[d.penaltyStatus] || '';
    } else {
        penBlock.style.display = 'none';
    }

    openModal('receiptModal');
}
</script>
@endsection