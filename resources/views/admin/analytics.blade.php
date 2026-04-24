@extends('layouts.admin')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')

@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Analytics & Reports</h1>
        <p style="color:var(--text-muted);font-size:13px;margin-top:3px;">Visual insights on library usage</p>
    </div>
    <button onclick="window.print()" style="background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border:none;padding:10px 20px;border-radius:9px;font-family:'Lato',sans-serif;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(107,0,0,0.25);display:flex;align-items:center;gap:8px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Export PDF
    </button>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px;">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Books Borrowed</div>
            <div class="stat-sub">
                @if($borrowingChange >= 0)
                    <span>↑ {{ $borrowingChange }}% this month</span>
                @else
                    <span style="color:#e67e22;">↓ {{ abs($borrowingChange) }}% this month</span>
                @endif
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalBorrowings) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Overdue Returns</div>
            <div class="stat-sub">
                @if($overdueCount > 0)
                    <span style="color:#e67e22;">{{ $newOverdueToday }} new today · Needs follow-up</span>
                @else
                    <span style="color:#27ae60;">All clear!</span>
                @endif
            </div>
        </div>
        <div class="stat-value" style="{{ $overdueCount > 0 ? 'color:var(--red-bright)' : 'color:#27ae60' }}">{{ $overdueCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-label">Room Bookings</div>
            <div class="stat-sub"><span>↑ {{ $roomBookingsThisMonth }} this month</span></div>
        </div>
        <div class="stat-value">{{ number_format($roomBookings) }}</div>
    </div>

</div>

{{-- CHARTS ROW --}}
<div class="grid-2" style="margin-bottom:18px;">

    {{-- Monthly Trend Line Chart --}}
    <div class="card">
        <div class="card-title">Monthly Borrowing Trend</div>
        <canvas id="trendChart" height="200"></canvas>
    </div>

    {{-- Borrowing by Program Bar Chart --}}
    <div class="card">
        <div class="card-title">Borrowing by Program</div>
        @if(count($programLabels) > 0)
            <canvas id="programChart" height="200"></canvas>
        @else
            <div style="text-align:center;padding:40px;color:var(--text-muted);font-size:13px;">No borrowing data yet.</div>
        @endif
    </div>

</div>

{{-- TOP BOOKS + COLLEGE SUMMARY --}}
<div class="grid-2">

    {{-- Top Borrowed Books --}}
    <div class="card">
        <div class="card-title">Top Borrowed Books</div>
        @forelse($topBooks as $i => $book)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(232,213,196,0.4);">
            <div style="width:24px;height:24px;border-radius:50%;background:{{ $i === 0 ? 'var(--red-main)' : 'var(--cream-dark)' }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:{{ $i === 0 ? '#fff' : 'var(--maroon-mid)' }};flex-shrink:0;">
                {{ $i + 1 }}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $book->title }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $book->author }}</div>
                {{-- Low stock warning --}}
                @if($book->stock == 0)
                    <span style="font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;background:rgba(192,57,43,0.1);color:var(--red-bright);">⚠ Out of Stock</span>
                @elseif($book->stock <= 2)
                    <span style="font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;background:rgba(230,126,34,0.1);color:#e67e22;">⚠ Low Stock ({{ $book->stock }} left)</span>
                @endif
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:14px;font-weight:700;color:var(--red-main);">{{ $book->borrowings_count }}</div>
                <div style="font-size:10px;color:var(--text-muted);">borrows</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px;">No borrowing data yet.</div>
        @endforelse
    </div>

    {{-- College Summary --}}
    <div class="card">
        <div class="card-title">College Summary</div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>College</th>
                    <th>Students</th>
                    <th>Books</th>
                    <th>Top Book</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collegeSummary as $college)
                <tr>
                    <td>
                        <div style="font-weight:700;color:var(--maroon-deep);">{{ $college['code'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $college['name'] }}</div>
                    </td>
                    <td style="font-weight:600;">{{ $college['students'] }}</td>
                    <td style="font-weight:600;">{{ $college['books'] }}</td>
                    <td>
                        <div style="font-size:12px;font-weight:600;color:var(--maroon-deep);">{{ Str::limit($college['top_book'], 20) }}</div>
                        @if($college['top_borrows'] > 0)
                        <div style="font-size:10px;color:var(--text-muted);">{{ $college['top_borrows'] }} borrows</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:20px;">No data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('scripts')
<script>
// Monthly Trend Line Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyLabels),
        datasets: [{
            label: 'Borrowings',
            data: @json($monthlyData),
            borderColor: 'rgba(160,0,0,0.8)',
            backgroundColor: 'rgba(160,0,0,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: 'var(--red-main)',
            pointRadius: 4,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});

@if(count($programLabels) > 0)
// Borrowing by Program Bar Chart
const programCtx = document.getElementById('programChart').getContext('2d');
new Chart(programCtx, {
    type: 'bar',
    data: {
        labels: @json($programLabels),
        datasets: [{
            data: @json($programData),
            backgroundColor: [
                'rgba(160,0,0,0.75)',
                'rgba(107,0,0,0.65)',
                'rgba(192,57,43,0.65)',
                'rgba(160,0,0,0.5)',
                'rgba(107,0,0,0.45)',
                'rgba(192,57,43,0.4)',
            ],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
@endif
</script>
@endsection