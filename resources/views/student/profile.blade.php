@extends('layouts.student')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--maroon-deep);">My Profile</h1>
        <p style="color:var(--text-muted);font-size:13px;">Your personal and academic information</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('editProfileModal')">Edit Profile</button>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="background:rgba(39,174,96,0.1);border:1px solid rgba(39,174,96,0.3);border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#27ae60;font-weight:600;">
    {{ session('success') }}
</div>
@endif

{{-- PROFILE HEADER --}}
<div style="background:var(--maroon-deep);border-radius:14px;padding:24px 28px;margin-bottom:20px;display:flex;align-items:center;gap:20px;">
            {{-- Avatar --}}
            <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;flex-shrink:0;border:3px solid rgba(255,255,255,0.2);">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <div style="width:100%;height:100%;background:var(--red-main);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#fff;">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                </div>
            @endif
        </div>
    <div>
        <div style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#fff;">{{ $user->full_name }}</div>
        <div style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:3px;text-transform:uppercase;letter-spacing:1px;">
            {{ $user->college?->code ?? 'N/A' }} · {{ $user->program ?? 'N/A' }}
        </div>
        <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:2px;">ID: {{ $user->student_id ?? 'N/A' }}</div>
    </div>
</div>

        {{-- BORROWING STATS --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-label">Total Borrowed</div>
                </div>
                <div class="stat-value">{{ $totalBorrowed }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-label">On Time</div>
                </div>
                <div class="stat-value">{{ $onTime }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-label">Late Returns</div>
                </div>
                <div class="stat-value">{{ $late }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-label">Currently Held</div>
                </div>
                <div class="stat-value">{{ $currentlyHeld }}</div>
            </div>
        </div>

{{-- INFO CARDS --}}
<div class="grid-2">

    {{-- Personal Info --}}
    <div class="card">
        <div class="card-title">Personal Information</div>
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);width:40%;">Full Name</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->full_name }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Email</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->email }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Contact</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->contact_number ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Gender</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ ucfirst($user->gender ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Date of Birth</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    {{-- Academic Info --}}
    <div class="card">
        <div class="card-title">Academic Information</div>
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);width:40%;">Student ID</td>
                <td style="padding:10px 0;font-size:13px;font-weight:700;color:var(--red-main);">{{ $user->student_id ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">College</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->college?->name ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Program</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->program ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Year Level</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->year_level ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom:1px solid rgba(232,213,196,0.4);">
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Section</td>
                <td style="padding:10px 0;font-size:13px;color:var(--text-dark);">{{ $user->section ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Status</td>
                <td style="padding:10px 0;">
                    <span class="badge badge-active">{{ ucfirst($user->status) }}</span>
                </td>
            </tr>
        </table>
    </div>

</div>

@endsection

{{-- MODALS --}}
@section('modals')

{{-- EDIT PROFILE MODAL --}}
<div class="modal-overlay" id="editProfileModal">
    <div class="modal" style="max-width:520px;">
        <button class="modal-close" onclick="closeModal('editProfileModal')">✕</button>
        <div class="modal-title">Edit Profile</div>

        {{-- ERRORS --}}
        @if($errors->any())
        <div style="background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.2);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#c0392b;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
            @csrf

           {{-- Profile Picture --}}
            <div style="text-align:center;margin-bottom:20px;">
                <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto 10px;border:3px solid var(--border);">
                    @if($user->profile_picture)
                        <img id="previewImg" src="{{ asset('storage/' . $user->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div id="previewInitials" style="width:100%;height:100%;background:var(--red-main);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#fff;">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                        <img id="previewImg" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                    @endif
                </div>
                {{-- Upload button --}}
                    <label for="profile_picture" style="display:inline-block;margin-top:6px;padding:7px 18px;background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(107,0,0,0.25);">
                                    📷 Upload Photo
                    </label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
                    <div style="font-size:10px;color:var(--text-muted);margin-top:6px;">JPG or PNG, max 2MB</div>
                    </div>

            {{-- Personal Details --}}
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}"
                    placeholder="e.g. 09123456789"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Gender</label>
                    <select name="gender" style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                        <option value="">Select</option>
                        <option value="male"   {{ $user->gender === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ $user->gender === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-bottom:8px;">Save Changes</button>
        </form>

        {{-- Divider --}}
        <div style="display:flex;align-items:center;gap:10px;margin:16px 0;">
            <div style="flex:1;height:1px;background:var(--border);"></div>
            <div style="font-size:11px;color:var(--text-muted);font-weight:700;">CHANGE PASSWORD</div>
            <div style="flex:1;height:1px;background:var(--border);"></div>
        </div>

        <form method="POST" action="{{ route('student.profile.password') }}">
            @csrf
            <div style="margin-bottom:12px;">
                <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">New Password</label>
                    <input type="password" name="new_password" placeholder="Min 8 characters"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:6px;">Confirm Password</label>
                    <input type="password" name="new_password_confirmation" placeholder="Repeat new password"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;color:var(--text-dark);background:#fff;outline:none;">
                </div>
            </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Change Password</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('previewImg');
            const initials = document.getElementById('previewInitials');
            img.src = e.target.result;
            img.style.display = 'block';
            if (initials) initials.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection