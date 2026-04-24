@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Admin Dashboard</h1>
        <p style="color:var(--text-muted);font-size:13px;">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->first_name }}!</p>
    </div>
    <button class="btn btn-primary" onclick="window.print()">Generate Report</button>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card" onclick="openModal('modalBooks')">
        <div class="stat-card-top">
            <div class="stat-label">Total Books</div>
            <div class="stat-sub"><span>{{ App\Models\Book::where('stock','>',0)->count() }} available</span> · Click to view</div>
        </div>
        <div class="stat-value">{{ number_format($totalBooks) }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalStudents')">
        <div class="stat-card-top">
            <div class="stat-label">Active Students</div>
            <div class="stat-sub"><span>{{ App\Models\User::where('role','student')->where('status','pending')->count() }} pending</span> · Click to view</div>
        </div>
        <div class="stat-value">{{ number_format($activeStudents) }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalLibrarians')">
        <div class="stat-card-top">
            <div class="stat-label">Librarians</div>
            <div class="stat-sub">
                @if($pendingRequests > 0)
                    <span>{{ App\Models\User::where('role','librarian')->where('status','pending')->count() }} pending</span> ·
                @endif
                Click to view
            </div>
        </div>
        <div class="stat-value">{{ $librarians }}</div>
    </div>
    <div class="stat-card" onclick="openModal('modalPending')">
        <div class="stat-card-top">
            <div class="stat-label">Pending Requests</div>
            <div class="stat-sub"><span>{{ App\Models\User::where('status','pending')->whereDate('created_at', today())->count() }} today</span> · Click to view</div>
        </div>
        <div class="stat-value">{{ $pendingRequests }}</div>
    </div>
</div>

{{-- CHART + ACTIVITY --}}
<div class="grid-2">
    <div class="card">
        <div class="card-title">Monthly Borrowing Activity</div>
        <canvas id="borrowChart" height="180"></canvas>
    </div>
    <div class="card">
        <div class="card-title">Recent Activity</div>
        @forelse($recentActivity as $activity)
        <div class="activity-item">
            <div class="activity-dot">
                {{ strtoupper(substr($activity->first_name, 0, 1)) }}{{ strtoupper(substr($activity->last_name, 0, 1)) }}
            </div>
            <div>
                <div class="activity-text">
                    @if($activity->status === 'pending')
                        New {{ $activity->role }} account request — {{ $activity->full_name }}
                    @else
                        {{ ucfirst($activity->role) }} approved — {{ $activity->full_name }}
                    @endif
                </div>
                <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:13px;">No recent activity.</p>
        @endforelse
    </div>
</div>

@endsection

@section('modals')

{{-- Books Modal --}}
<div class="modal-overlay" id="modalBooks">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('modalBooks')">&#x2715;</button>
        <div class="modal-title">Total Books — Catalog Overview</div>
        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Total Titles</div>
                <div class="modal-stat-value">{{ $totalBooks }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Available</div>
                <div class="modal-stat-value">{{ App\Models\Book::where('stock', '>', 0)->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Unavailable</div>
                <div class="modal-stat-value">{{ App\Models\Book::where('stock', 0)->count() }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Book ID</th><th>Title</th><th>Program</th><th>Stock</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach(App\Models\Book::latest()->take(6)->get() as $book)
                <tr>
                    <td style="color:var(--red-main);font-weight:700;">{{ $book->book_id }}</td>
                    <td>{{ $book->title }}</td>
                    <td><span class="prog-badge prog-cce">{{ $book->program }}</span></td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        @if($book->stock == 0)
                            <span class="badge badge-rejected">Unavailable</span>
                        @elseif($book->stock <= 2)
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

{{-- Students Modal --}}
<div class="modal-overlay" id="modalStudents">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('modalStudents')">&#x2715;</button>
        <div class="modal-title">Active Students</div>
        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Total Students</div>
                <div class="modal-stat-value">{{ $activeStudents }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">New This Month</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','student')->where('status','active')->whereMonth('created_at', now()->month)->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Pending</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','student')->where('status','pending')->count() }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Name</th><th>Student ID</th><th>Program</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach(App\Models\User::where('role','student')->latest()->take(6)->get() as $student)
                <tr>
                    <td style="font-weight:600;">{{ $student->full_name }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $student->student_id }}</td>
                    <td>{{ $student->program }}</td>
                    <td>
                        <span class="badge badge-{{ $student->status === 'active' ? 'active' : ($student->status === 'pending' ? 'pending' : 'rejected') }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Librarians Modal --}}
<div class="modal-overlay" id="modalLibrarians">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('modalLibrarians')">&#x2715;</button>
        <div class="modal-title">Librarians</div>
        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Total</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','librarian')->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Active</div>
                <div class="modal-stat-value">{{ $librarians }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Pending Approval</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','librarian')->where('status','pending')->count() }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Name</th><th>Employee ID</th><th>Position</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach(App\Models\User::where('role','librarian')->latest()->take(6)->get() as $lib)
                <tr>
                    <td style="font-weight:600;">{{ $lib->full_name }}</td>
                    <td style="color:var(--red-main);font-weight:700;">{{ $lib->employee_id ?? 'N/A' }}</td>
                    <td>{{ $lib->program ?? 'Librarian' }}</td>
                    <td>
                        <span class="badge badge-{{ $lib->status === 'active' ? 'active' : ($lib->status === 'pending' ? 'pending' : 'rejected') }}">
                            {{ ucfirst($lib->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Pending Modal --}}
<div class="modal-overlay" id="modalPending">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('modalPending')">&#x2715;</button>
        <div class="modal-title">Pending Requests</div>
        <div class="modal-stat-grid">
            <div class="modal-stat">
                <div class="modal-stat-label">Total Pending</div>
                <div class="modal-stat-value">{{ $pendingRequests }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Students</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','student')->where('status','pending')->count() }}</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Librarians</div>
                <div class="modal-stat-value">{{ App\Models\User::where('role','librarian')->where('status','pending')->count() }}</div>
            </div>
        </div>
        <table class="tbl">
            <thead>
                <tr><th>Name</th><th>Role</th><th>Email</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach(App\Models\User::where('status','pending')->latest()->take(8)->get() as $user)
                <tr>
                    <td style="font-weight:600;">{{ $user->full_name }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('M d') }}</td>
                    <td><span class="badge badge-pending">Pending</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
const ctx = document.getElementById('borrowChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthLabels) !!},
        datasets: [{
            data: {!! json_encode($monthlyData) !!},
            backgroundColor: 'rgba(160,0,0,0.7)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endsection