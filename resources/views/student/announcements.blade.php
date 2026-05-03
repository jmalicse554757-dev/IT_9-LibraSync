@extends('layouts.student')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Announcements</h1>
    <p style="color:var(--text-muted);font-size:13px;">Stay updated with the latest library announcements</p>
</div>

{{-- SEARCH --}}
<form method="GET" action="{{ route('student.announcements') }}" style="margin-bottom:16px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);opacity:0.4;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search announcements..."
                style="width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;">
        </div>
        <button type="submit" style="padding:10px 20px;background:var(--maroon-deep);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">Search</button>
        @if($search)
        <a href="{{ route('student.announcements') }}" style="padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;">Clear</a>
        @endif
    </div>
</form>

@if($announcements->count() === 0)
<div class="card" style="text-align:center;padding:60px 20px;color:var(--text-muted);">
    <div style="font-size:15px;font-weight:700;color:var(--maroon-deep);">No announcements yet</div>
    <div style="font-size:12px;margin-top:6px;">Check back later for updates from the library</div>
</div>
@else
<div style="display:flex;flex-direction:column;gap:14px;">
    @foreach($announcements as $a)
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="height:4px;background:var(--maroon-deep);"></div>
        <div style="padding:18px 20px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:10px;">
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:15px;color:var(--maroon-deep);margin-bottom:6px;">{{ $a->title }}</div>
                    <div style="font-size:13px;color:var(--text-dark);line-height:1.6;">{{ $a->body }}</div>
                </div>
                <div style="flex-shrink:0;">
                    @if($a->audience === 'all')
                        <span style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(59,0,0,0.07);color:var(--maroon-mid);">General</span>
                    @else
                        <span style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(160,0,0,0.08);color:var(--red-main);">Library</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;padding-top:10px;border-top:1px solid var(--border);">
                <div style="width:26px;height:26px;border-radius:50%;background:var(--maroon-deep);display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($a->author?->full_name ?? 'S', 0, 1)) }}
                </div>
                <div style="font-size:11px;color:var(--text-muted);">
                    <strong style="color:var(--text-dark);">{{ $a->author?->full_name ?? 'Library Staff' }}</strong>
                    · {{ $a->created_at->format('M d, Y') }}
                    · {{ $a->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- PAGINATION --}}
@if($announcements->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;flex-wrap:wrap;gap:10px;">
    <div style="font-size:13px;color:var(--text-muted);">
        Showing <strong>{{ $announcements->firstItem() }}</strong> - <strong>{{ $announcements->lastItem() }}</strong>
        of <strong>{{ $announcements->total() }}</strong> announcements
    </div>
    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
        @if($announcements->onFirstPage())
            <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">‹ Prev</span>
        @else
            <a href="{{ $announcements->previousPageUrl() }}"
               style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">‹ Prev</a>
        @endif
        @foreach($announcements->getUrlRange(1, $announcements->lastPage()) as $page => $url)
            @if($page == $announcements->currentPage())
                <span style="padding:7px 13px;border-radius:8px;background:var(--maroon-deep);color:#fff;font-size:13px;font-weight:700;">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                   style="padding:7px 13px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">{{ $page }}</a>
            @endif
        @endforeach
        @if($announcements->hasMorePages())
            <a href="{{ $announcements->nextPageUrl() }}"
               style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:var(--maroon-deep);text-decoration:none;font-weight:600;">Next ›</a>
        @else
            <span style="padding:7px 14px;border-radius:8px;border:1.5px solid var(--border);font-size:13px;color:#ccc;cursor:not-allowed;">Next ›</span>
        @endif
    </div>
</div>
@endif

@endif

@endsection