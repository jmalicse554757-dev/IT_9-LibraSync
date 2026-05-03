@extends('layouts.student')

@section('title', 'Browse Books')
@section('page-title', 'Browse Books')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Browse Books</h1>
    <p style="color:var(--text-muted);font-size:13px;">Explore the library collection by program</p>
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

{{-- FLOOR MAP --}}
<div class="card" style="margin-bottom:22px;">
    <div class="card-title">Library Floor Map</div>
    <p style="font-size:12px;color:var(--text-muted);margin-bottom:16px;">Use this map to locate books by college section</p>
    <div style="overflow-x:auto;">
        <svg viewBox="0 0 820 500" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:820px;font-family:Lato,sans-serif;">
            <rect x="8" y="8" width="804" height="484" rx="10" fill="#faf7f2" stroke="#b08850" stroke-width="3"/>
            <rect x="330" y="476" width="160" height="16" rx="3" fill="#7a0000"/>
            <text x="410" y="488" text-anchor="middle" font-size="11" font-weight="700" fill="#fff">MAIN ENTRANCE</text>
            <text x="410" y="32" text-anchor="middle" font-size="13" font-weight="700" fill="#7a0000" letter-spacing="2">UNIVERSITY LIBRARY — FLOOR PLAN</text>
            <rect x="310" y="44" width="200" height="48" rx="6" fill="#fef3c7" stroke="#f59e0b" stroke-width="1.5"/>
            <text x="410" y="64" text-anchor="middle" font-size="11" font-weight="700" fill="#92400e">Librarian's Desk</text>
            <text x="410" y="80" text-anchor="middle" font-size="10" fill="#b45309">Check-out · Returns · Inquiries</text>
            <rect x="20" y="110" width="180" height="160" rx="6" fill="#dbeafe" stroke="#3b82f6" stroke-width="2"/>
            <text x="110" y="132" text-anchor="middle" font-size="12" font-weight="700" fill="#1d4ed8">CCE</text>
            <text x="110" y="147" text-anchor="middle" font-size="10" fill="#3b82f6">Computer Education</text>
            <line x1="30" y1="158" x2="190" y2="158" stroke="#93c5fd" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="173" x2="190" y2="173" stroke="#93c5fd" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="188" x2="190" y2="188" stroke="#93c5fd" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="203" x2="190" y2="203" stroke="#93c5fd" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="218" x2="190" y2="218" stroke="#93c5fd" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="110" y="258" text-anchor="middle" font-size="9" fill="#60a5fa">BSIT · BSCS</text>
            <rect x="20" y="285" width="180" height="130" rx="6" fill="#d1fae5" stroke="#10b981" stroke-width="2"/>
            <text x="110" y="307" text-anchor="middle" font-size="12" font-weight="700" fill="#065f46">CON</text>
            <text x="110" y="322" text-anchor="middle" font-size="10" fill="#10b981">College of Nursing</text>
            <line x1="30" y1="333" x2="190" y2="333" stroke="#6ee7b7" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="348" x2="190" y2="348" stroke="#6ee7b7" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="30" y1="363" x2="190" y2="363" stroke="#6ee7b7" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="110" y="403" text-anchor="middle" font-size="9" fill="#34d399">BSN</text>
            <rect x="215" y="110" width="175" height="160" rx="6" fill="#fef3c7" stroke="#f59e0b" stroke-width="2"/>
            <text x="302" y="132" text-anchor="middle" font-size="12" font-weight="700" fill="#92400e">COE</text>
            <text x="302" y="147" text-anchor="middle" font-size="10" fill="#d97706">College of Engineering</text>
            <line x1="225" y1="158" x2="380" y2="158" stroke="#fcd34d" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="173" x2="380" y2="173" stroke="#fcd34d" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="188" x2="380" y2="188" stroke="#fcd34d" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="203" x2="380" y2="203" stroke="#fcd34d" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="218" x2="380" y2="218" stroke="#fcd34d" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="302" y="258" text-anchor="middle" font-size="9" fill="#fbbf24">BSCE · BSEcE · BSME</text>
            <rect x="215" y="285" width="175" height="130" rx="6" fill="#dcfce7" stroke="#22c55e" stroke-width="2"/>
            <text x="302" y="307" text-anchor="middle" font-size="12" font-weight="700" fill="#14532d">CBA</text>
            <text x="302" y="322" text-anchor="middle" font-size="10" fill="#16a34a">Business Administration</text>
            <line x1="225" y1="333" x2="380" y2="333" stroke="#86efac" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="348" x2="380" y2="348" stroke="#86efac" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="225" y1="363" x2="380" y2="363" stroke="#86efac" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="302" y="403" text-anchor="middle" font-size="9" fill="#4ade80">BSBA · BSA</text>
            <rect x="405" y="110" width="175" height="160" rx="6" fill="#f3e8ff" stroke="#a855f7" stroke-width="2"/>
            <text x="492" y="132" text-anchor="middle" font-size="12" font-weight="700" fill="#6b21a8">CCJ</text>
            <text x="492" y="147" text-anchor="middle" font-size="10" fill="#a855f7">Criminal Justice</text>
            <line x1="415" y1="158" x2="570" y2="158" stroke="#d8b4fe" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="173" x2="570" y2="173" stroke="#d8b4fe" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="188" x2="570" y2="188" stroke="#d8b4fe" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="203" x2="570" y2="203" stroke="#d8b4fe" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="218" x2="570" y2="218" stroke="#d8b4fe" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="492" y="258" text-anchor="middle" font-size="9" fill="#c084fc">BSCrim</text>
            <rect x="405" y="285" width="175" height="130" rx="6" fill="#fce7f3" stroke="#ec4899" stroke-width="2"/>
            <text x="492" y="307" text-anchor="middle" font-size="12" font-weight="700" fill="#9d174d">CED</text>
            <text x="492" y="322" text-anchor="middle" font-size="10" fill="#ec4899">College of Education</text>
            <line x1="415" y1="333" x2="570" y2="333" stroke="#f9a8d4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="348" x2="570" y2="348" stroke="#f9a8d4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="415" y1="363" x2="570" y2="363" stroke="#f9a8d4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="492" y="403" text-anchor="middle" font-size="9" fill="#f472b6">BSED · BEED</text>
            <rect x="595" y="110" width="175" height="160" rx="6" fill="#ffedd5" stroke="#f97316" stroke-width="2"/>
            <text x="682" y="132" text-anchor="middle" font-size="12" font-weight="700" fill="#7c2d12">CAS</text>
            <text x="682" y="147" text-anchor="middle" font-size="10" fill="#ea580c">Arts and Sciences</text>
            <line x1="605" y1="158" x2="760" y2="158" stroke="#fdba74" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="173" x2="760" y2="173" stroke="#fdba74" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="188" x2="760" y2="188" stroke="#fdba74" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="203" x2="760" y2="203" stroke="#fdba74" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="218" x2="760" y2="218" stroke="#fdba74" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="682" y="258" text-anchor="middle" font-size="9" fill="#fb923c">AB · BS Programs</text>
            <rect x="595" y="285" width="175" height="130" rx="6" fill="#fdf6e3" stroke="#c8a882" stroke-width="2"/>
            <text x="682" y="307" text-anchor="middle" font-size="12" font-weight="700" fill="#7a4040">General</text>
            <text x="682" y="322" text-anchor="middle" font-size="10" fill="#a07050">General Collection</text>
            <line x1="605" y1="333" x2="760" y2="333" stroke="#e8d5c4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="348" x2="760" y2="348" stroke="#e8d5c4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="605" y1="363" x2="760" y2="363" stroke="#e8d5c4" stroke-width="1.5" stroke-dasharray="4,3"/>
            <text x="682" y="403" text-anchor="middle" font-size="9" fill="#b08860">Fiction · References</text>
            <rect x="20" y="428" width="200" height="44" rx="5" fill="#f9fafb" stroke="#d1d5db" stroke-width="1.5"/>
            <text x="120" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#6b7280">Reading Area</text>
            <rect x="232" y="428" width="90" height="44" rx="5" fill="#ede9fe" stroke="#8b5cf6" stroke-width="1.5"/>
            <text x="277" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#5b21b6">Collab</text>
            <text x="277" y="462" text-anchor="middle" font-size="9" fill="#7c3aed">Room 1</text>
            <rect x="330" y="428" width="90" height="44" rx="5" fill="#ede9fe" stroke="#8b5cf6" stroke-width="1.5"/>
            <text x="375" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#5b21b6">Collab</text>
            <text x="375" y="462" text-anchor="middle" font-size="9" fill="#7c3aed">Room 2</text>
            <rect x="428" y="428" width="100" height="44" rx="5" fill="#fef9ee" stroke="#fbbf24" stroke-width="1.5"/>
            <text x="478" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#92400e">Rest Zone</text>
            <text x="478" y="462" text-anchor="middle" font-size="9" fill="#b45309">Quiet Area</text>
            <rect x="536" y="428" width="80" height="44" rx="5" fill="#f0fdf4" stroke="#86efac" stroke-width="1.5"/>
            <text x="576" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#166534">CR</text>
            <rect x="624" y="428" width="160" height="44" rx="5" fill="#fef2f2" stroke="#fca5a5" stroke-width="1.5"/>
            <text x="704" y="446" text-anchor="middle" font-size="10" font-weight="700" fill="#991b1b">Exit / Fire Exit</text>
            <text x="785" y="60" text-anchor="middle" font-size="9" fill="#9ca3af" font-weight="700">N</text>
            <line x1="785" y1="64" x2="785" y2="78" stroke="#9ca3af" stroke-width="1.5"/>
            <polygon points="781,78 785,86 789,78" fill="#9ca3af"/>
        </svg>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;display:inline-block;"></span>CCE</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#10b981;display:inline-block;"></span>CON</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>COE</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>CBA</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#a855f7;display:inline-block;"></span>CCJ</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#ec4899;display:inline-block;"></span>CED</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#f97316;display:inline-block;"></span>CAS</span>
        <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;"><span style="width:10px;height:10px;border-radius:50%;background:#c8a882;display:inline-block;"></span>General</span>
    </div>
</div>

{{-- SEARCH & FILTERS --}}
<form method="GET" action="{{ route('student.browse-books') }}" id="filterForm">
    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px;">
        <div style="position:relative;flex:1;min-width:200px;">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);opacity:0.4;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by title or author..."
                style="width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;">
        </div>
        <select name="college_id" onchange="this.form.submit()"
            style="padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;background:#fff;min-width:160px;">
            <option value="">All Colleges</option>
            @foreach($colleges as $col)
                <option value="{{ $col->id }}" {{ $collegeId == $col->id ? 'selected' : '' }}>
                    {{ $col->code }} - {{ $col->name }}
                </option>
            @endforeach
        </select>
        <select name="category" onchange="this.form.submit()"
            style="padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;background:#fff;min-width:150px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="availability" onchange="this.form.submit()"
            style="padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;background:#fff;min-width:150px;">
            <option value="">All Status</option>
            <option value="available"   {{ $availability === 'available'   ? 'selected' : '' }}>Available</option>
            <option value="low stock"   {{ $availability === 'low stock'   ? 'selected' : '' }}>Low Stock</option>
            <option value="unavailable" {{ $availability === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
        <button type="submit"
            style="padding:10px 20px;background:var(--maroon-deep);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">
            Search
        </button>
        @if($search || $collegeId || $category || $availability)
        <a href="{{ route('student.browse-books') }}"
            style="padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;">
            Clear
        </a>
        @endif
    </div>
</form>


{{-- BOOK GRID --}}
@if($books->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
    @foreach($books as $book)
    @php $status = $book->status; @endphp
    <div onclick="openBookModal({{ $book->id }})"
        style="background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s;">
        <div style="height:130px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;">BOOK COVER</div>
            @endif
        </div>
        <div style="padding:10px 12px 12px;">
            <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-bottom:2px;line-height:1.3;">{{ $book->title }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;">{{ $book->author }}</div>
            <div style="font-size:10px;color:var(--text-muted);margin-bottom:8px;">{{ $book->college->code ?? 'General' }}</div>
            @if($status === 'unavailable')
                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(192,57,43,0.1);color:#c0392b;">Unavailable</span>
            @elseif($status === 'low stock')
                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(230,126,34,0.1);color:#e67e22;">Low Stock</span>
            @else
                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(39,174,96,0.1);color:#27ae60;">Available</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- PAGINATION --}}
@if($books->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;flex-wrap:wrap;gap:10px;">
    
    {{-- Results info --}}
    <div style="font-size:13px;color:var(--text-muted);">
        Showing <strong>{{ $books->firstItem() }}</strong> - <strong>{{ $books->lastItem() }}</strong>
        of <strong>{{ $books->total() }}</strong> books
    </div>

    {{-- Page buttons --}}
    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">

        {{-- Previous --}}
        @if($books->onFirstPage())
            <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">‹ Prev</span>
        @else
            <a href="{{ $books->previousPageUrl() }}"
               style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">‹ Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if($page == $books->currentPage())
                <span style="padding:7px 13px;border-radius:8px;background:var(--maroon-deep);color:#fff;font-size:13px;font-weight:700;">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                   style="padding:7px 13px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($books->hasMorePages())
            <a href="{{ $books->nextPageUrl() }}"
               style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">Next ›</a>
        @else
            <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">Next ›</span>
        @endif

    </div>
</div>
@endif

@else
<div style="text-align:center;padding:60px 20px;color:var(--text-muted);">
    <div style="font-size:36px;margin-bottom:12px;">📚</div>
    <div style="font-size:14px;font-weight:700;">No books found</div>
    <div style="font-size:12px;margin-top:4px;">Try a different search term or filter</div>
</div>
@endif

@endsection

{{-- MODALS --}}
@section('modals')
<div class="modal-overlay" id="bookDetailModal">
    <div class="modal" style="max-width:520px;">
        <button class="modal-close" onclick="closeModal('bookDetailModal')">✕</button>
        <div id="modalBookContent">
            <div style="text-align:center;padding:30px;color:var(--text-muted);">Loading...</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const booksData = {
    @foreach($books as $book)
    {{ $book->id }}: {
        id: {{ $book->id }},
        title: @json($book->title),
        author: @json($book->author),
        publisher: @json($book->publisher ?? 'N/A'),
        year: @json($book->year_published ?? 'N/A'),
        edition: @json($book->edition ?? 'N/A'),
        isbn: @json($book->isbn ?? 'N/A'),
        category: @json($book->category ?? 'N/A'),
        program: @json($book->college->name ?? 'General Collection'),
        shelf: @json($book->shelf_location ?? 'N/A'),
        stock: {{ $book->stock }},
        status: @json($book->status),
        description: @json($book->description ?? ''),
        cover: @json($book->cover_image ? asset('storage/' . $book->cover_image) : null),
        myRequest: {{ in_array($book->id, $myActiveBookIds) ? 'true' : 'false' }},
        bookId: @json($book->book_id),
    },
    @endforeach
};

function openBookModal(id) {
    const b = booksData[id];
    if (!b) return;

    const statusColor = b.status === 'available' ? '#27ae60' : b.status === 'low stock' ? '#e67e22' : '#c0392b';
    const statusBg    = b.status === 'available' ? 'rgba(39,174,96,0.1)' : b.status === 'low stock' ? 'rgba(230,126,34,0.1)' : 'rgba(192,57,43,0.1)';
    const statusLabel = b.status === 'available' ? 'Available' : b.status === 'low stock' ? 'Low Stock' : 'Unavailable';

    let actionBtn = '';
    if (b.myRequest) {
        actionBtn = `
            <div style="padding:10px;background:rgba(39,174,96,0.08);border:1px solid rgba(39,174,96,0.2);border-radius:8px;text-align:center;font-size:12px;font-weight:700;color:#27ae60;margin-bottom:8px;">
                ✔ Request Submitted — Awaiting Librarian Approval
            </div>
            <form method="POST" action="/student/browse-books/${b.id}/cancel">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" style="width:100%;padding:9px;background:none;border:1.5px solid #c0392b;border-radius:8px;font-size:12px;font-weight:700;color:#c0392b;cursor:pointer;">
                    Cancel Request
                </button>
            </form>`;
    } else if (b.status === 'unavailable') {
        actionBtn = `<button disabled style="width:100%;padding:10px;background:#f3f4f6;border:none;border-radius:8px;font-size:13px;font-weight:700;color:#9ca3af;cursor:not-allowed;">Unavailable</button>`;
    } else {
        actionBtn = `
            <form method="POST" action="/student/browse-books/${b.id}/request">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;">
                    Request to Borrow
                </button>
            </form>`;
    }

    document.getElementById('modalBookContent').innerHTML = `
        <div style="display:flex;gap:16px;margin-bottom:20px;">
            <div style="width:100px;height:140px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                ${b.cover ? `<img src="${b.cover}" style="width:100%;height:100%;object-fit:cover;">` : `<div style="font-size:9px;font-weight:700;color:#9ca3af;text-align:center;padding:8px;">BOOK COVER</div>`}
            </div>
            <div style="flex:1;">
                <div style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--maroon-deep);line-height:1.3;margin-bottom:4px;">${b.title}</div>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px;">by ${b.author}</div>
                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:${statusBg};color:${statusColor};">${statusLabel}</span>
                <div style="font-size:11px;color:var(--text-muted);margin-top:8px;">Book ID: <strong style="color:var(--red-main);">${b.bookId}</strong></div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Publisher</div>
                <div style="font-size:12px;color:var(--text-dark);margin-top:2px;">${b.publisher}</div>
            </div>
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Year / Edition</div>
                <div style="font-size:12px;color:var(--text-dark);margin-top:2px;">${b.year} · ${b.edition}</div>
            </div>
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Program / College</div>
                <div style="font-size:12px;color:var(--text-dark);margin-top:2px;">${b.program}</div>
            </div>
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Shelf Location</div>
                <div style="font-size:12px;color:var(--text-dark);margin-top:2px;">${b.shelf}</div>
            </div>
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">ISBN</div>
                <div style="font-size:12px;color:var(--text-dark);margin-top:2px;">${b.isbn}</div>
            </div>
            <div style="background:var(--cream);border-radius:8px;padding:10px 12px;">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Copies Available</div>
                <div style="font-size:12px;font-weight:700;color:var(--maroon-deep);margin-top:2px;">${b.stock}</div>
            </div>
        </div>
        ${b.description ? `<div style="font-size:12px;color:var(--text-muted);line-height:1.7;margin-bottom:16px;padding:12px;background:var(--cream);border-radius:8px;">${b.description}</div>` : ''}
        ${actionBtn}
    `;

    openModal('bookDetailModal');
}
</script>
@endsection