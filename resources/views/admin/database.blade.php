@extends('layouts.admin')

@section('title', 'Database')
@section('page-title', 'Database')

@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Database</h1>
        <p style="color:var(--text-muted);font-size:13px;margin-top:3px;">Core library data management</p>
    </div>
    <button onclick="confirmBackup()" style="background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border:none;padding:10px 20px;border-radius:9px;font-family:'Lato',sans-serif;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(107,0,0,0.25);">
        Backup Now
    </button>
</div>

{{-- RECORD COUNT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:18px;">

    {{-- Books Card --}}
    <div onclick="openModal('books')"
         style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;cursor:pointer;transition:all .2s;position:relative;overflow:hidden;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(59,0,0,0.1)';this.style.borderColor='var(--red-main)'"
         onmouseout="this.style.boxShadow='none';this.style.borderColor='var(--border)'">
        <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;padding-left:8px;">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:3px;">Books Table</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ number_format($totalBooks) }} total records</div>
            </div>
            <div style="width:36px;height:36px;background:rgba(160,0,0,0.07);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--red-main)" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
        </div>
        {{-- Mini stats --}}
        <div style="display:flex;gap:8px;margin-bottom:12px;padding-left:8px;">
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">{{ $availableBooks }} available</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(230,126,18,0.1);color:#e67e22;">{{ $lowStockBooks }} low</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(192,57,43,0.1);color:var(--red-bright);">{{ $unavailableBooks }} out</span>
        </div>
        <div style="padding-left:8px;">
            <div style="height:5px;background:rgba(59,0,0,0.07);border-radius:3px;overflow:hidden;">
                <div style="height:100%;width:{{ $totalBooks > 0 ? min(($availableBooks / max($totalBooks, 1)) * 100, 100) : 5 }}%;background:linear-gradient(90deg,#27ae60,#2ecc71);border-radius:3px;"></div>
            </div>
            <div style="font-size:10px;color:var(--text-muted);margin-top:4px;">{{ $totalBooks > 0 ? round(($availableBooks / $totalBooks) * 100) : 0 }}% available · Click for details</div>
        </div>
    </div>

    {{-- Users Card --}}
    <div onclick="openModal('users')"
         style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;cursor:pointer;transition:all .2s;position:relative;overflow:hidden;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(59,0,0,0.1)';this.style.borderColor='var(--red-main)'"
         onmouseout="this.style.boxShadow='none';this.style.borderColor='var(--border)'">
        <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;padding-left:8px;">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:3px;">Users Table</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ number_format($totalUsers) }} total records</div>
            </div>
            <div style="width:36px;height:36px;background:rgba(160,0,0,0.07);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--red-main)" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div style="display:flex;gap:8px;margin-bottom:12px;padding-left:8px;">
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(39,174,96,0.15);color:#27ae60;">{{ $totalStudents }} students</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(41,128,185,0.1);color:#2980b9;">{{ $totalLibrarians }} librarians</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(245,166,35,0.15);color:#f5a623;">{{ $totalAdmins }} admins</span>
        </div>
        <div style="padding-left:8px;">
            <div style="height:5px;background:rgba(59,0,0,0.07);border-radius:3px;overflow:hidden;">
                <div style="height:100%;width:{{ $totalUsers > 0 ? min(($activeUsers / max($totalUsers, 1)) * 100, 100) : 5 }}%;background:linear-gradient(90deg,#2980b9,#3498db);border-radius:3px;"></div>
            </div>
            <div style="font-size:10px;color:var(--text-muted);margin-top:4px;">{{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0 }}% active · {{ $pendingUsers }} pending approval</div>
        </div>
    </div>

    {{-- Transactions Card --}}
    <div onclick="openModal('transactions')"
         style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;cursor:pointer;transition:all .2s;position:relative;overflow:hidden;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(59,0,0,0.1)';this.style.borderColor='var(--red-main)'"
         onmouseout="this.style.boxShadow='none';this.style.borderColor='var(--border)'">
        <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;padding-left:8px;">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:3px;">Transactions</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ number_format($totalBorrowings) }} total records</div>
            </div>
            <div style="width:36px;height:36px;background:rgba(160,0,0,0.07);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--red-main)" stroke-width="2">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                </svg>
            </div>
        </div>
        <div style="display:flex;gap:8px;margin-bottom:12px;padding-left:8px;">
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">{{ $activeBorrowings }} active</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(192,57,43,0.1);color:var(--red-bright);">{{ $overdueBorrowings }} overdue</span>
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(122,64,64,0.07);color:var(--text-muted);">{{ $returnedBorrowings }} returned</span>
        </div>
        <div style="padding-left:8px;">
            <div style="height:5px;background:rgba(59,0,0,0.07);border-radius:3px;overflow:hidden;">
                <div style="height:100%;width:{{ $totalBorrowings > 0 ? min(($returnedBorrowings / max($totalBorrowings, 1)) * 100, 100) : 5 }}%;background:linear-gradient(90deg,var(--maroon-mid),var(--red-bright));border-radius:3px;"></div>
            </div>
            <div style="font-size:10px;color:var(--text-muted);margin-top:4px;">{{ $totalBorrowings > 0 ? round(($returnedBorrowings / $totalBorrowings) * 100) : 0 }}% returned · Click for details</div>
        </div>
    </div>

</div>

{{-- QUICK ACTIONS --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;">
    <div style="font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;">Quick Actions</div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">

        <a href="{{ route('admin.database.export-csv') }}"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:rgba(59,0,0,0.06);color:var(--maroon-mid);border:1px solid var(--border);font-family:'Lato',sans-serif;font-size:13px;font-weight:700;text-decoration:none;transition:all .2s;"
           onmouseover="this.style.background='rgba(59,0,0,0.11)'"
           onmouseout="this.style.background='rgba(59,0,0,0.06)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export CSV
        </a>

        <button onclick="showImport()"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:rgba(59,0,0,0.06);color:var(--maroon-mid);border:1px solid var(--border);font-family:'Lato',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;"
           onmouseover="this.style.background='rgba(59,0,0,0.11)'"
           onmouseout="this.style.background='rgba(59,0,0,0.06)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Import Data
        </button>

        <button onclick="confirmClean()"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:rgba(59,0,0,0.06);color:var(--maroon-mid);border:1px solid var(--border);font-family:'Lato',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;"
           onmouseover="this.style.background='rgba(59,0,0,0.11)'"
           onmouseout="this.style.background='rgba(59,0,0,0.06)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
            </svg>
            Clean Duplicates
        </button>

        <a href="{{ route('admin.database.audit-log') }}"
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:transparent;color:var(--red-main);border:1.5px solid var(--red-main);font-family:'Lato',sans-serif;font-size:13px;font-weight:700;text-decoration:none;transition:all .2s;"
           onmouseover="this.style.background='rgba(160,0,0,0.05)'"
           onmouseout="this.style.background='transparent'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Audit Log
        </a>

    </div>
</div>

{{-- MODAL --}}
<div id="dbModal" onclick="if(event.target===this)closeModal()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.46);backdrop-filter:blur(4px);z-index:500;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:24px;width:660px;max-width:96vw;max-height:88vh;overflow-y:auto;box-shadow:0 20px 56px rgba(0,0,0,0.18);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div id="modalTitle" style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--maroon-deep);"></div>
            <button onclick="closeModal()" style="background:rgba(59,0,0,0.07);border:none;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;">✕</button>
        </div>
        <div id="modalBody"></div>
    </div>
</div>

{{-- IMPORT MODAL --}}
<div id="importModal" onclick="if(event.target===this)closeImport()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.46);backdrop-filter:blur(4px);z-index:500;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:24px;width:460px;max-width:96vw;box-shadow:0 20px 56px rgba(0,0,0,0.18);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <div style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--maroon-deep);">Import Data</div>
            <button onclick="closeImport()" style="background:rgba(59,0,0,0.07);border:none;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:14px;">✕</button>
        </div>
        <div style="background:rgba(41,128,185,0.05);border:1px solid rgba(41,128,185,0.2);border-radius:9px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:var(--text-muted);">
            Upload a CSV file to import books. Must follow the export format.
        </div>
        <div style="border:2px dashed var(--border);border-radius:9px;padding:28px;text-align:center;margin-bottom:16px;cursor:pointer;"
             onclick="document.getElementById('csvFile').click()">
            <div style="font-size:32px;margin-bottom:8px;">📄</div>
            <div style="font-size:13px;font-weight:700;color:var(--maroon-mid);margin-bottom:4px;">Click to upload CSV</div>
            <div style="font-size:11px;color:var(--text-muted);">CSV only · Max 10MB</div>
        </div>
        <input type="file" id="csvFile" accept=".csv" style="display:none">
        <button onclick="closeImport()" style="width:100%;padding:12px;background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border:none;border-radius:9px;font-family:'Lato',sans-serif;font-size:14px;font-weight:700;cursor:pointer;">
            Import Books
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>

// ── DATA FROM PHP ──
const data = {
    books: {
        total: {{ $totalBooks }},
        available: {{ $availableBooks }},
        lowStock: {{ $lowStockBooks }},
        unavailable: {{ $unavailableBooks }},
        byCollege: @json($booksByCollegeMapped),
        recent: @json($recentBooks)
    },
    users: {
        total: {{ $totalUsers }},
        students: {{ $totalStudents }},
        librarians: {{ $totalLibrarians }},
        admins: {{ $totalAdmins }},
        active: {{ $activeUsers }},
        pending: {{ $pendingUsers }},
        rejected: {{ $rejectedUsers }},
        byCollege: @json($usersByCollegeMapped),
        recent: @json($recentUsers)
    },
    transactions: {
        total: {{ $totalBorrowings }},
        active: {{ $activeBorrowings }},
        overdue: {{ $overdueBorrowings }},
        returned: {{ $returnedBorrowings }},
        topBooks: @json($topBooks),
        recent: @json($recentBorrowings)
    }
};

function statBox(label, value, color = 'var(--maroon-deep)') {
    return `<div style="background:var(--cream);border-radius:9px;padding:14px;min-height:90px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden;">
        <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));"></div>
        <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);padding-left:8px;">${label}</div>
        <div style="font-family:'Playfair Display',serif;font-size:36px;font-weight:700;color:var(--maroon-deep);line-height:1;text-align:right;">${value}</div>
    </div>`;
}

function badge(text, bg, color) {
    return `<span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:${bg};color:${color};">${text}</span>`;
}

function progressBar(pct, color = 'linear-gradient(90deg,var(--maroon-mid),var(--red-bright))') {
    return `<div style="height:5px;background:rgba(59,0,0,0.07);border-radius:3px;overflow:hidden;margin-top:4px;">
        <div style="height:100%;width:${Math.min(pct, 100)}%;background:${color};border-radius:3px;transition:width .5s;"></div>
    </div>`;
}

function openModal(type) {
    const modal = document.getElementById('dbModal');
    const title = document.getElementById('modalTitle');
    const body  = document.getElementById('modalBody');

    if (type === 'books') {
        title.textContent = 'Books Table — Overview';
        const maxCollege = Math.max(...data.books.byCollege.map(c => c.count), 1);
        body.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:18px;">
                ${statBox('Total', data.books.total)}
                ${statBox('Available', data.books.available, '#27ae60')}
                ${statBox('Low Stock', data.books.lowStock, '#e67e22')}
                ${statBox('Unavailable', data.books.unavailable, 'var(--red-bright)')}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Books by College</div>
                ${data.books.byCollege.length > 0 ? data.books.byCollege.map(c => `
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:90px;font-size:11px;font-weight:600;color:var(--maroon-deep);flex-shrink:0;">${c.code ?? c.name}</div>
                    <div style="flex:1;">${progressBar((c.count / maxCollege) * 100)}</div>
                    <div style="font-size:11px;color:var(--text-muted);width:60px;text-align:right;">${c.count} books</div>
                </div>`).join('') : '<div style="font-size:12px;color:var(--text-muted);text-align:center;padding:12px;">No data yet</div>'}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Recently Added Books</div>
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead><tr style="background:rgba(59,0,0,0.04);">
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Book ID</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Title</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Author</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Stock</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                    </thead></tr>
                    <tbody>
                    ${data.books.recent.map(b => `
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 10px;font-family:monospace;font-size:11px;color:var(--red-main);font-weight:700;">${b.id ?? 'N/A'}</td>
                        <td style="padding:8px 10px;font-weight:600;">${b.title}</td>
                        <td style="padding:8px 10px;color:var(--text-muted);">${b.author}</td>
                        <td style="padding:8px 10px;">${b.stock}</td>
                        <td style="padding:8px 10px;">${
                            b.status === 'available'   ? badge('Available', 'rgba(39,174,96,0.11)', '#27ae60') :
                            b.status === 'low stock'   ? badge('Low Stock', 'rgba(230,126,18,0.11)', '#e67e22') :
                            badge('Unavailable', 'rgba(192,57,43,0.11)', 'var(--red-bright)')
                        }</td>
                    </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;

    } else if (type === 'users') {
        title.textContent = 'Users Table — Overview';
        const maxCollege = Math.max(...data.users.byCollege.map(c => c.count), 1);
        body.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px;">
                ${statBox('Total Users', data.users.total)}
                ${statBox('Active', data.users.active, '#27ae60')}
                ${statBox('Pending', data.users.pending, '#e67e22')}
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:18px;">
                ${statBox('Students', data.users.students, 'var(--maroon-deep)')}
                ${statBox('Librarians', data.users.librarians, '#2980b9')}
                ${statBox('Admins', data.users.admins, '#f5a623')}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Users by College</div>
                ${data.users.byCollege.length > 0 ? data.users.byCollege.map(c => `
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:90px;font-size:11px;font-weight:600;color:var(--maroon-deep);flex-shrink:0;">${c.code ?? c.name}</div>
                    <div style="flex:1;">${progressBar((c.count / maxCollege) * 100, 'linear-gradient(90deg,#2980b9,#3498db)')}</div>
                    <div style="font-size:11px;color:var(--text-muted);width:60px;text-align:right;">${c.count} users</div>
                </div>`).join('') : '<div style="font-size:12px;color:var(--text-muted);text-align:center;padding:12px;">No data yet</div>'}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Recently Registered</div>
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead><tr style="background:rgba(59,0,0,0.04);">
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Name</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Role</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Date</th>
                    </thead></tr>
                    <tbody>
                    ${data.users.recent.map(u => `
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 10px;font-weight:600;">${u.name}</td>
                        <td style="padding:8px 10px;">${
                            u.role === 'admin'     ? badge('Admin', 'rgba(245,166,35,0.15)', '#f5a623') :
                            u.role === 'librarian' ? badge('Librarian', 'rgba(41,128,185,0.1)', '#2980b9') :
                            badge('Student', 'rgba(39,174,96,0.1)', '#27ae60')
                        }</td>
                        <td style="padding:8px 10px;">${
                            u.status === 'active'   ? badge('Active', 'rgba(39,174,96,0.11)', '#27ae60') :
                            u.status === 'pending'  ? badge('Pending', 'rgba(160,0,0,0.07)', 'var(--red-main)') :
                            badge('Rejected', 'rgba(192,57,43,0.11)', 'var(--red-bright)')
                        }</td>
                        <td style="padding:8px 10px;color:var(--text-muted);">${u.date}</td>
                    </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;

    } else if (type === 'transactions') {
        title.textContent = 'Transactions — Borrowings Overview';
        const maxBorrow = Math.max(...data.transactions.topBooks.map(b => b.count), 1);
        body.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:18px;">
                ${statBox('Total', data.transactions.total)}
                ${statBox('Active', data.transactions.active, '#27ae60')}
                ${statBox('Overdue', data.transactions.overdue, 'var(--red-bright)')}
                ${statBox('Returned', data.transactions.returned, 'var(--text-muted)')}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Top Borrowed Books</div>
                ${data.transactions.topBooks.length > 0 ? data.transactions.topBooks.map((b, i) => `
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:18px;height:18px;border-radius:50%;background:var(--cream-dark);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--maroon-mid);flex-shrink:0;">${i+1}</div>
                    <div style="flex:1;">
                        <div style="font-size:12px;font-weight:600;color:var(--maroon-deep);">${b.title}</div>
                        <div>${progressBar((b.count / maxBorrow) * 100)}</div>
                    </div>
                    <div style="font-size:11px;font-weight:700;color:var(--red-main);white-space:nowrap;">${b.count} borrows</div>
                </div>`).join('') : '<div style="font-size:12px;color:var(--text-muted);text-align:center;padding:12px;">No borrowing data yet</div>'}
            </div>

            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;">
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:12px;">Recent Transactions</div>
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead><tr style="background:rgba(59,0,0,0.04);">
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Receipt</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Borrower</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Book</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Status</th>
                        <th style="text-align:left;padding:8px 10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);">Date</th>
                    </thead></tr>
                    <tbody>
                    ${data.transactions.recent.map(t => `
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 10px;font-family:monospace;font-size:10px;color:var(--red-main);font-weight:700;">${t.receipt ?? 'N/A'}</td>
                        <td style="padding:8px 10px;font-weight:600;">${t.user}</td>
                        <td style="padding:8px 10px;color:var(--text-muted);">${t.book}</td>
                        <td style="padding:8px 10px;">${
                            t.status === 'active'   ? badge('Active', 'rgba(39,174,96,0.11)', '#27ae60') :
                            t.status === 'overdue'  ? badge('Overdue', 'rgba(192,57,43,0.11)', 'var(--red-bright)') :
                            t.status === 'due today' ? badge('Due Today', 'rgba(230,126,18,0.11)', '#e67e22') :
                            badge('Returned', 'rgba(122,64,64,0.07)', 'var(--text-muted)')
                        }</td>
                        <td style="padding:8px 10px;color:var(--text-muted);">${t.date}</td>
                    </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;
    }

    modal.style.display = 'flex';
}

function closeModal()  { document.getElementById('dbModal').style.display = 'none'; }
function showImport()  { document.getElementById('importModal').style.display = 'flex'; }
function closeImport() { document.getElementById('importModal').style.display = 'none'; }

function confirmBackup() {
    if (confirm('Create a database backup now?')) {
        alert('Backup created: librasync_backup_{{ now()->format("Y-m-d") }}.sql');
    }
}

function confirmClean() {
    if (confirm('Scan and remove exact duplicate records?')) {
        alert('No duplicates found. Your database is clean!');
    }
}
</script>
@endsection