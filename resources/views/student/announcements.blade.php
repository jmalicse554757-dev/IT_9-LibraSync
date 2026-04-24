@extends('layouts.student')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Announcements</h1>
    <p style="color:var(--text-muted);font-size:13px;">Stay updated with the latest library announcements</p>
</div>

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
@endif

@endsection