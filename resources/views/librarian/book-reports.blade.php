@extends('layouts.librarian')

@section('title', 'Book Reports')
@section('page-title', 'Book Reports')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Book Reports</h1>
        <p style="color:var(--text-muted);font-size:13px;">Availability and borrowing statistics</p>
    </div>
    <a href="{{ route('librarian.book-reports.export') }}" class="btn btn-primary">Export CSV</a>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px;">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Total Titles</div>
        </div>
        <div class="stat-value">{{ number_format($totalBooks) }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalAvailable')" style="cursor:pointer;">
        <div class="stat-card-top">
            <div class="stat-label">Available</div>
            <div class="stat-sub">Click to view</div>
        </div>
        <div class="stat-value">{{ number_format($availableBooks) }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalBorrowedOut')" style="cursor:pointer;">
        <div class="stat-card-top">
            <div class="stat-label">Borrowed Out</div>
            <div class="stat-sub">Click to see who</div>
        </div>
        <div class="stat-value">{{ number_format($borrowedOut) }}</div>
    </div>
</div>

{{-- CHARTS + TOP BORROWED --}}
<div class="grid-2" style="margin-bottom:22px;">

    {{-- Books by Program Chart --}}
    <div class="card">
        <div class="card-title">Books by Program</div>
        @if($booksByProgram->isEmpty())
            <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:40px 0;">No data yet.</p>
        @else
            <canvas id="programChart" height="220"></canvas>
            <div style="margin-top:20px;">
                @foreach($booksByProgram as $item)
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                        <span style="font-size:12px;font-weight:700;color:var(--text-dark);">{{ $item->program }}</span>
                        <span style="font-size:12px;color:var(--text-muted);">{{ $item->total }} books</span>
                    </div>
                    <div style="background:var(--cream-dark);border-radius:99px;height:6px;overflow:hidden;">
                        <div style="height:100%;border-radius:99px;background:linear-gradient(90deg,var(--maroon-mid),var(--red-bright));width:{{ $totalBooks > 0 ? round(($item->total / $totalBooks) * 100) : 0 }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Top Borrowed Books --}}
    <div class="card">
        <div class="card-title">Top Borrowed Books</div>
        @if($topBorrowed->isEmpty())
            <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:40px 0;">No borrowing data yet.</p>
        @else
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Program</th>
                        <th>Borrows</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topBorrowed as $item)
                    <tr>
                        <td style="font-weight:600;">{{ $item->book->title ?? 'N/A' }}</td>
                        <td><span class="prog-badge prog-cce">{{ $item->book->program ?? '—' }}</span></td>
                        <td style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--maroon-deep);">{{ $item->borrow_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

{{-- FULL BOOK TABLE --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
        <div class="card-title" style="margin-bottom:0;">All Books</div>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Program</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach(App\Models\Book::orderBy('program')->get() as $book)
            <tr>
                <td style="color:var(--red-main);font-weight:700;font-size:12px;">{{ $book->book_id }}</td>
                <td style="font-weight:600;">{{ $book->title }}</td>
                <td style="color:var(--text-muted);">{{ $book->author }}</td>
                <td><span class="prog-badge prog-cce">{{ $book->program }}</span></td>
                <td style="font-weight:700;color:var(--maroon-deep);">{{ $book->stock }}</td>
                <td>
                    @if($book->status === 'unavailable')
                        <span class="badge badge-rejected">Unavailable</span>
                    @elseif($book->status === 'low stock')
                        <span class="badge badge-pending">Low Stock</span>
                    @else
                        <span class="badge badge-active">Available</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- Available Books Modal --}}
<div class="modal-overlay" id="modalAvailable">
    <div class="modal" style="max-width:640px;">
        <button class="modal-close" onclick="closeModal('modalAvailable')">✕</button>
        <div class="modal-title">Available Books</div>

        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Available</div>
                <div class="modal-stat-value">{{ $availableBooks }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Low Stock (≤2)</div>
                <div class="modal-stat-value">{{ App\Models\Book::where('stock','<=',2)->where('stock','>',0)->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Unavailable</div>
                <div class="modal-stat-value">{{ App\Models\Book::where('stock',0)->count() }}</div>
            </div>
        </div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Program</th>
                    <th>Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach(App\Models\Book::where('stock','>',0)->orderBy('stock')->take(8)->get() as $book)
                <tr>
                    <td style="color:var(--red-main);font-weight:700;font-size:12px;">{{ $book->book_id }}</td>
                    <td style="font-weight:600;">{{ $book->title }}</td>
                    <td><span class="prog-badge prog-cce">{{ $book->program }}</span></td>
                    <td style="font-weight:700;color:{{ $book->stock <= 2 ? '#e67e22' : 'var(--maroon-deep)' }};">{{ $book->stock }}</td>
                    <td>
                        @if($book->status === 'low stock')
                            <span class="badge badge-pending">Low Stock</span>
                        @else
                            <span class="badge badge-active">Available</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Borrowed Out Modal --}}
<div class="modal-overlay" id="modalBorrowedOut">
    <div class="modal" style="max-width:660px;">
        <button class="modal-close" onclick="closeModal('modalBorrowedOut')">✕</button>
        <div class="modal-title">Currently Borrowed Out</div>

        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Borrowed Out</div>
                <div class="modal-stat-value">{{ $borrowedOut }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Overdue</div>
                <div class="modal-stat-value">{{ App\Models\Borrowing::whereNull('date_returned')->whereDate('due_date','<',today())->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Due Today</div>
                <div class="modal-stat-value">{{ App\Models\Borrowing::whereNull('date_returned')->whereDate('due_date',today())->count() }}</div>
            </div>
        </div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Book</th>
                    <th>Borrower</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse(App\Models\Borrowing::with(['book','user'])->whereNull('date_returned')->latest()->take(8)->get() as $b)
                <tr>
                    <td style="color:var(--red-main);font-weight:700;font-size:11px;font-family:monospace;">{{ $b->receipt_no }}</td>
                    <td style="font-weight:600;">{{ $b->book?->title ?? '—' }}</td>
                    <td style="color:var(--text-muted);">{{ $b->user?->full_name ?? '—' }}</td>
                    <td style="font-size:12px;">{{ $b->due_date?->format('M d, Y') }}</td>
                    <td>
                        @if($b->status === 'overdue')
                            <span class="badge badge-overdue">Overdue</span>
                        @elseif($b->status === 'due today')
                            <span class="badge badge-due">Due Today</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:20px;">No borrowed books yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection