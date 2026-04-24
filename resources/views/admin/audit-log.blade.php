@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Audit Log</h1>
        <p style="color:var(--text-muted);font-size:13px;margin-top:3px;">Recent system activity across all tables</p>
    </div>
    <a href="{{ route('admin.database') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:8px;background:rgba(59,0,0,0.06);color:var(--maroon-mid);border:1px solid var(--border);font-size:13px;font-weight:700;text-decoration:none;">
        ← Back to Database
    </a>
</div>

{{-- RECENT USERS --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:14px;">
    <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;">
        Recent User Registrations
        <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:8px;">Last 10 records</span>
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
        <thead>
            <tr style="background:rgba(59,0,0,0.04);">
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Name</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Email</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Role</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers as $user)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:9px 10px;font-weight:600;">{{ $user->full_name }}</td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $user->email }}</td>
                <td style="padding:9px 10px;">
                    <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;
                        background:{{ $user->role==='admin' ? 'rgba(245,166,35,0.15)' : ($user->role==='librarian' ? 'rgba(41,128,185,0.1)' : 'rgba(39,174,96,0.1)') }};
                        color:{{ $user->role==='admin' ? '#f5a623' : ($user->role==='librarian' ? '#2980b9' : '#27ae60') }};">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td style="padding:9px 10px;">
                    <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;
                        background:{{ $user->status==='active' ? 'rgba(39,174,96,0.11)' : ($user->status==='pending' ? 'rgba(160,0,0,0.07)' : 'rgba(192,57,43,0.11)') }};
                        color:{{ $user->status==='active' ? '#27ae60' : ($user->status==='pending' ? 'var(--red-main)' : 'var(--red-bright)') }};">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $user->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text-muted);">No users yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- RECENT BOOKS --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:14px;">
    <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;">
        Recently Added Books
        <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:8px;">Last 10 records</span>
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
        <thead>
            <tr style="background:rgba(59,0,0,0.04);">
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Book ID</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Title</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Author</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Stock</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBooks as $book)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:9px 10px;font-family:monospace;font-size:11px;color:var(--red-main);font-weight:700;">{{ $book->book_id ?? 'N/A' }}</td>
                <td style="padding:9px 10px;font-weight:600;">{{ $book->title }}</td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $book->author }}</td>
                <td style="padding:9px 10px;">{{ $book->stock }}</td>
                <td style="padding:9px 10px;">
                    @if($book->status === 'available')
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(39,174,96,0.11);color:#27ae60;">Available</span>
                    @elseif($book->status === 'low stock')
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(230,126,18,0.11);color:#e67e22;">Low Stock</span>
                    @else
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(192,57,43,0.11);color:var(--red-bright);">Unavailable</span>
                    @endif
                </td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $book->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:20px;text-align:center;color:var(--text-muted);">No books yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- RECENT BORROWINGS --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;">
    <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;">
        Recent Borrowing Transactions
        <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:8px;">Last 10 records</span>
    </div>
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
        <thead>
            <tr style="background:rgba(59,0,0,0.04);">
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Receipt</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Borrower</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Book</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                <th style="text-align:left;padding:9px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBorrowings as $borrowing)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:9px 10px;font-family:monospace;font-size:10px;color:var(--red-main);font-weight:700;">{{ $borrowing->receipt_no ?? 'N/A' }}</td>
                <td style="padding:9px 10px;font-weight:600;">{{ $borrowing->user?->full_name ?? 'N/A' }}</td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $borrowing->book?->title ?? 'N/A' }}</td>
                <td style="padding:9px 10px;">
                    @php $s = $borrowing->status; @endphp
                    @if($s === 'active')
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(39,174,96,0.11);color:#27ae60;">Active</span>
                    @elseif($s === 'overdue')
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(192,57,43,0.11);color:var(--red-bright);">Overdue</span>
                    @elseif($s === 'due today')
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(230,126,18,0.11);color:#e67e22;">Due Today</span>
                    @else
                        <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(122,64,64,0.07);color:var(--text-muted);">Returned</span>
                    @endif
                </td>
                <td style="padding:9px 10px;color:var(--text-muted);">{{ $borrowing->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text-muted);">No transactions yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection