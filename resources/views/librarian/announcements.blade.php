@extends('layouts.librarian')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">Announcements</h1>
    <p style="color:var(--text-muted);font-size:13px;">Post and manage library announcements</p>
</div>

@if(session('success'))
<div style="background:rgba(39,174,96,0.1);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#27ae60;font-weight:600;">
    {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:20px;align-items:start;">

    {{-- POST FORM --}}
    <div class="card" style="padding:20px;">
        <div style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--maroon-deep);margin-bottom:16px;">Post Announcement</div>

        <form method="POST" action="{{ route('librarian.announcements.store') }}">
            @csrf
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Title</label>
                <input type="text" name="title" required placeholder="Announcement title..."
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;box-sizing:border-box;"
                    value="{{ old('title') }}">
                @error('title')<div style="font-size:11px;color:#c0392b;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Audience</label>
                <select name="audience" required
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;box-sizing:border-box;">
                    <option value="all"     {{ old('audience') === 'all'     ? 'selected' : '' }}>Everyone (Students + Librarians)</option>
                    <option value="student" {{ old('audience') === 'student' ? 'selected' : '' }}>Students Only</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Message</label>
                <textarea name="body" required rows="5" placeholder="Write your announcement here..."
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;resize:vertical;box-sizing:border-box;">{{ old('body') }}</textarea>
                @error('body')<div style="font-size:11px;color:#c0392b;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                Post Announcement
            </button>
        </form>
    </div>

    {{-- ANNOUNCEMENTS LIST --}}
    <div>
        <div style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--maroon-deep);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
            <span>All Announcements</span>
           <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;background:var(--maroon-deep);color:#fff;">{{ $announcements->total() }}</span>
        </div>

        {{-- SEARCH --}}
<form method="GET" action="{{ route('librarian.announcements') }}" style="margin-bottom:16px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);opacity:0.4;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search announcements..."
                style="width:100%;padding:10px 14px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;outline:none;">
        </div>
        <button type="submit" style="padding:10px 20px;background:var(--maroon-deep);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;">Search</button>
        @if($search)
        <a href="{{ route('librarian.announcements') }}" style="padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;">Clear</a>
        @endif
    </div>
</form>

@if($announcements->count() === 0)
        <div class="card" style="text-align:center;padding:48px 20px;color:var(--text-muted);">
            <div style="font-size:14px;font-weight:700;color:var(--maroon-deep);">No announcements yet</div>
            <div style="font-size:12px;margin-top:6px;">Post your first announcement using the form</div>
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($announcements as $a)
            <div class="card" style="padding:0;overflow:hidden;">
                <div style="height:3px;background:{{ $a->audience === 'all' ? 'var(--maroon-deep)' : ($a->audience === 'student' ? '#3b82f6' : '#8b5cf6') }};"></div>
                <div style="padding:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px;">
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:14px;color:var(--maroon-deep);margin-bottom:4px;">{{ $a->title }}</div>
                            <div style="font-size:12px;color:var(--text-muted);line-height:1.5;">{{ $a->body }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0;">
                            @if($a->audience === 'all')
                                <span style="font-size:10px;font-weight:700;padding:2px 9px;border-radius:20px;background:rgba(139,92,246,0.1);color:#7c3aed;white-space:nowrap;">Everyone</span>
                            @elseif($a->audience === 'student')
                                <span style="font-size:10px;font-weight:700;padding:2px 9px;border-radius:20px;background:rgba(59,130,246,0.1);color:#2563eb;white-space:nowrap;">Students</span>
                            @else
                                <span style="font-size:10px;font-weight:700;padding:2px 9px;border-radius:20px;background:rgba(139,92,246,0.1);color:#7c3aed;white-space:nowrap;">Librarians</span>
                            @endif
                            @if($a->posted_by === auth()->id())
                            <button onclick="openModal('editModal-{{ $a->id }}')"
                                style="font-size:10px;font-weight:700;padding:4px 10px;border-radius:6px;border:1.5px solid var(--border);background:#fff;color:var(--maroon-deep);cursor:pointer;">
                                Edit
                            </button>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:10px;border-top:1px solid var(--border);">
                        <div style="font-size:11px;color:var(--text-muted);">
                            Posted by <strong>{{ $a->author?->full_name ?? 'Staff' }}</strong> · {{ $a->created_at->format('M d, Y h:i A') }}
                        </div>
                        @if($a->posted_by === auth()->id())
                        <form method="POST" action="{{ route('librarian.announcements.destroy', $a) }}" onsubmit="return confirm('Delete this announcement?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="font-size:10px;font-weight:700;padding:4px 10px;border-radius:6px;border:1.5px solid #fca5a5;background:none;color:#c0392b;cursor:pointer;">
                                Delete
                            </button>
                        </form>
                        @endif
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
    </div>
</div>

@endsection

@section('modals')
@foreach($announcements->items() as $a)
@if($a->posted_by === auth()->id())
<div class="modal-overlay" id="editModal-{{ $a->id }}">
    <div class="modal" style="max-width:500px;">
        <button class="modal-close" onclick="closeModal('editModal-{{ $a->id }}')">&#x2715;</button>
        <div class="modal-title">Edit Announcement</div>

        <form method="POST" action="{{ route('librarian.announcements.update', $a) }}">
            @csrf @method('PUT')
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Title</label>
                <input type="text" name="title" required value="{{ $a->title }}"
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Audience</label>
                <select name="audience" required
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;box-sizing:border-box;">
                    <option value="all"     {{ $a->audience === 'all'     ? 'selected' : '' }}>Everyone</option>
                    <option value="student" {{ $a->audience === 'student' ? 'selected' : '' }}>Students Only</option>
                </select>
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:block;margin-bottom:6px;">Message</label>
                <textarea name="body" required rows="5"
                    style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;resize:vertical;box-sizing:border-box;">{{ $a->body }}</textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeModal('editModal-{{ $a->id }}')" class="btn" style="flex:1;background:#f3f4f6;color:var(--text-dark);">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endsection