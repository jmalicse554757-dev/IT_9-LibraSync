<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraSync — Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root{
          --maroon-deep:#3b0000;--maroon-mid:#6b0000;--red-main:#a00000;
          --red-bright:#c0392b;--cream:#fdf6e3;--cream-dark:#f0e6cc;
          --white:#ffffff;--text-dark:#1a0000;--text-muted:#7a4040;
          --border:#e8d5c4;--success:#27ae60;--warning:#e67e22;
        }
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Lato',sans-serif;background:var(--cream);color:var(--text-dark);min-height:100vh;}
        .screen{display:none;min-height:100vh;}
        .screen.on{display:flex;}
        .auth-left{width:44%;background:linear-gradient(155deg,var(--maroon-deep) 0%,var(--maroon-mid) 55%,var(--red-main) 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:56px 44px;position:relative;overflow:hidden;}
        .auth-left::before{content:'';position:absolute;bottom:-90px;left:-90px;width:320px;height:320px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.07);}
        .auth-left::after{content:'';position:absolute;top:-70px;right:-70px;width:250px;height:250px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.07);}
        .brand{display:flex;align-items:center;gap:13px;margin-bottom:8px;position:relative;z-index:1;}
        .brand-box{width:58px;height:58px;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.2);border-radius:16px;display:flex;align-items:center;justify-content:center;}
        .brand-name{font-family:'Playfair Display',serif;font-size:38px;font-weight:700;color:#fff;letter-spacing:-1px;}
        .brand-name em{color:var(--cream);font-style:normal;}
        .brand-tag{color:rgba(255,255,255,0.4);font-size:11px;letter-spacing:3px;text-transform:uppercase;margin-bottom:44px;position:relative;z-index:1;}
        .feat-list{list-style:none;max-width:290px;position:relative;z-index:1;}
        .feat-list li{display:flex;align-items:center;gap:11px;color:rgba(255,255,255,0.68);font-size:13px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,0.08);}
        .feat-list li:last-child{border-bottom:none;}
        .fi{width:30px;height:30px;background:rgba(255,255,255,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
        .auth-copy{position:absolute;bottom:22px;color:rgba(255,255,255,0.2);font-size:10px;letter-spacing:1px;z-index:1;}
        .auth-right{flex:1;background:var(--cream);display:flex;align-items:center;justify-content:center;padding:44px 36px;position:relative;overflow-y:auto;}
        .auth-right::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));}
        .auth-card{width:100%;max-width:400px;animation:up .4s ease;}
        @keyframes up{from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);}}
        .auth-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:var(--maroon-deep);margin-bottom:5px;}
        .auth-sub{color:var(--text-muted);font-size:13px;margin-bottom:24px;}
        .flabel{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--maroon-mid);margin-bottom:6px;}
        .fgroup{margin-bottom:15px;}
        .finput{width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:9px;background:var(--white);font-family:'Lato',sans-serif;font-size:13px;color:var(--text-dark);outline:none;transition:border-color .2s;}
        .finput:focus{border-color:var(--red-main);box-shadow:0 0 0 3px rgba(160,0,0,0.08);}
        .finput::placeholder{color:rgba(122,64,64,0.38);}
        select.finput{appearance:none;cursor:pointer;}
        .btn-main{width:100%;padding:13px;background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border:none;border-radius:9px;font-family:'Lato',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 5px 16px rgba(107,0,0,0.28);margin-bottom:14px;}
        .btn-main:hover{transform:translateY(-2px);box-shadow:0 9px 22px rgba(107,0,0,0.38);}
        .auth-sw{text-align:center;font-size:12px;color:var(--text-muted);}
        .auth-sw a{color:var(--red-main);font-weight:700;cursor:pointer;text-decoration:none;}
        .steps{display:flex;margin-bottom:24px;}
        .si{flex:1;display:flex;flex-direction:column;align-items:center;position:relative;}
        .si:not(:last-child)::after{content:'';position:absolute;top:13px;left:50%;width:100%;height:2px;background:var(--border);}
        .si.done::after,.si.act::after{background:var(--red-main);}
        .sdot{width:26px;height:26px;border-radius:50%;border:2px solid var(--border);background:var(--white);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--text-muted);z-index:1;margin-bottom:4px;transition:all .3s;}
        .si.act .sdot,.si.done .sdot{border-color:var(--red-main);background:var(--red-main);color:#fff;}
        .slbl{font-size:9px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;font-weight:600;text-align:center;}
        .si.act .slbl{color:var(--red-main);}
        .reg-role{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;}
        .rrb{border:1.5px solid var(--border);border-radius:9px;padding:10px;text-align:center;cursor:pointer;background:var(--white);transition:all .2s;font-size:12px;font-weight:700;color:var(--maroon-mid);}
        .rrb:hover,.rrb.sel{border-color:var(--red-main);background:rgba(160,0,0,0.05);}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .fg{margin-bottom:12px;}
        .fg label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--maroon-mid);margin-bottom:4px;}
        .fc{width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:'Lato',sans-serif;font-size:13px;color:var(--text-dark);outline:none;background:var(--white);}
        .fc:focus{border-color:var(--red-main);}
        select.fc{appearance:none;cursor:pointer;}
        .success-icon{width:72px;height:72px;background:var(--red-main);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
        .success-icon svg{width:36px;height:36px;}
        @media(max-width:768px){.auth-left{display:none;}.form-row{grid-template-columns:1fr;}}
    </style>
</head>
<body>

<div class="screen on">
    {{-- LEFT PANEL --}}
    <div class="auth-left">
        <div class="brand">
            <div class="brand-box">
                <svg width="32" height="32" viewBox="0 0 36 36" fill="none">
                    <rect x="3" y="5" width="8" height="24" rx="2.5" fill="white" opacity=".95"/>
                    <rect x="14" y="9" width="8" height="20" rx="2.5" fill="white" opacity=".75"/>
                    <rect x="25" y="13" width="8" height="16" rx="2.5" fill="white" opacity=".55"/>
                    <rect x="3" y="30" width="30" height="2.5" rx="1.25" fill="white" opacity=".8"/>
                </svg>
            </div>
            <span class="brand-name">Libra<em>Sync</em></span>
        </div>
        <div class="brand-tag">Create your account</div>
        <ul class="feat-list">
            <li><div class="fi">✅</div>Fill in your personal details</li>
            <li><div class="fi">🏛️</div>Select your program / department</li>
            <li><div class="fi">🔒</div>Set a secure password</li>
            <li><div class="fi">⏳</div>Wait for admin approval to activate</li>
        </ul>
        <div class="auth-copy">© 2025 LibraSync · All rights reserved</div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="auth-right">
        <div class="auth-card">

            {{-- SUCCESS SCREEN --}}
            @if(session('registered'))
            <div class="steps">
                <div class="si done" id="rs1"><div class="sdot">✓</div><div class="slbl">Type</div></div>
                <div class="si done" id="rs2"><div class="sdot">✓</div><div class="slbl">Personal</div></div>
                <div class="si done" id="rs3"><div class="sdot">✓</div><div class="slbl">Academic</div></div>
                <div class="si done" id="rs4"><div class="sdot">✓</div><div class="slbl">Security</div></div>
            </div>
            <div style="text-align:center;padding:20px 0;">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="auth-title" style="margin-bottom:12px;">Registration Submitted!</div>
                <p style="color:var(--text-muted);font-size:13px;line-height:1.6;margin-bottom:28px;">
                    Your account is pending Admin approval. You will be notified via email once approved (1–2 business days).
                </p>
                <a href="{{ route('login') }}" class="btn-main" style="display:block;text-decoration:none;text-align:center;">Go to Login</a>
            </div>

            @else

            {{-- REGISTRATION FORM --}}
            <div class="auth-title">Create Account</div>
            <div class="auth-sub">Fill in the form to register</div>

            {{-- Step indicators --}}
            <div class="steps">
                <div class="si act" id="rs1"><div class="sdot">1</div><div class="slbl">Type</div></div>
                <div class="si" id="rs2"><div class="sdot">2</div><div class="slbl">Personal</div></div>
                <div class="si" id="rs3"><div class="sdot">3</div><div class="slbl" id="step3Label">Academic</div></div>
                <div class="si" id="rs4"><div class="sdot">4</div><div class="slbl">Security</div></div>
            </div>

            {{-- Server-side validation errors --}}
            @if($errors->any())
            <div style="background:rgba(192,57,43,0.07);border:1px solid rgba(192,57,43,0.2);border-radius:9px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:var(--red-bright);">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="regForm">
                @csrf

                {{-- STEP 1: TYPE --}}
                <div id="rp1">
                    <div class="flabel" style="margin-bottom:9px">Registering as</div>
                    <div class="reg-role">
                        <div class="rrb sel" id="rrS" onclick="setRR('student')">Student</div>
                        <div class="rrb" id="rrL" onclick="setRR('librarian')">Librarian</div>
                    </div>
                    <input type="hidden" name="role" id="roleInput" value="student">

                    {{-- Student ID (student only) --}}
                    <div class="fgroup" id="studentIdGroup">
                        <label class="flabel">Student ID Number</label>
                        <input type="text" name="student_id" class="finput" placeholder="2024-0001" value="{{ old('student_id') }}">
                    </div>

                    <div class="fgroup">
                        <label class="flabel">Email Address</label>
                        <input type="email" name="email" class="finput" placeholder="you@school.edu.ph" value="{{ old('email') }}">
                    </div>

                    <button type="button" class="btn-main" onclick="rGo(1)">Continue</button>
                    <div class="auth-sw">Have an account? <a href="{{ route('login') }}">Sign in</a></div>
                </div>

                {{-- STEP 2: PERSONAL --}}
                <div id="rp2" style="display:none">
                    <div class="form-row">
                        <div class="fg"><label>First Name</label><input type="text" name="first_name" class="fc" placeholder="Juan" value="{{ old('first_name') }}"></div>
                        <div class="fg"><label>Last Name</label><input type="text" name="last_name" class="fc" placeholder="Dela Cruz" value="{{ old('last_name') }}"></div>
                    </div>
                    <div class="form-row">
                        <div class="fg"><label>Date of Birth</label><input type="date" name="date_of_birth" class="fc" value="{{ old('date_of_birth') }}"></div>
                        <div class="fg"><label>Gender</label>
                            <select name="gender" class="fc">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                                <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                                <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="fg"><label>Contact Number</label><input type="text" name="contact_number" class="fc" placeholder="09XX-XXX-XXXX" value="{{ old('contact_number') }}"></div>
                    <div style="display:flex;gap:8px">
                        <button type="button" class="btn-main" style="background:rgba(107,0,0,0.1);color:var(--maroon-mid);box-shadow:none;flex:0 0 auto;width:auto;padding:13px 16px" onclick="rBack(2)">Back</button>
                        <button type="button" class="btn-main" style="flex:1" onclick="rGo(2)">Continue</button>
                    </div>
                </div>

                {{-- STEP 3: ACADEMIC (student) / WORK INFO (librarian) --}}
                <div id="rp3" style="display:none">

                    {{-- Student fields --}}
                    <div id="rp3Student">
                        <div class="fg"><label>College</label>
                            <select name="college_id" class="fc">
                                <option value="">Select College</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}" {{ old('college_id')==$college->id?'selected':'' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fg"><label>Program</label>
                            <input type="text" name="program" class="fc" placeholder="e.g. BSIT, BSN, BSCrim" value="{{ old('program') }}">
                        </div>
                        <div class="form-row">
                            <div class="fg"><label>Year Level</label>
                                <select name="year_level" class="fc">
                                    <option value="">Select</option>
                                    <option value="1st Year" {{ old('year_level')=='1st Year'?'selected':'' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level')=='2nd Year'?'selected':'' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level')=='3rd Year'?'selected':'' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level')=='4th Year'?'selected':'' }}>4th Year</option>
                                </select>
                            </div>
                            <div class="fg"><label>Section</label><input type="text" name="section" class="fc" placeholder="e.g. A" value="{{ old('section') }}"></div>
                        </div>
                    </div>

                    {{-- Librarian fields --}}
                    <div id="rp3Librarian" style="display:none">
                        <div class="fg"><label>Position</label>
                            <select name="position" class="fc">
                                <option value="">Select Position</option>
                                <option value="Head Librarian" {{ old('position')=='Head Librarian'?'selected':'' }}>Head Librarian</option>
                                <option value="Assistant Librarian" {{ old('position')=='Assistant Librarian'?'selected':'' }}>Assistant Librarian</option>
                                <option value="Library Aide" {{ old('position')=='Library Aide'?'selected':'' }}>Library Aide</option>
                            </select>
                        </div>
                        <div class="fg"><label>Employee ID</label>
                            <input type="text" name="employee_id" class="fc" placeholder="EMP-0001" value="{{ old('employee_id') }}">
                        </div>
                    </div>

                    <div style="display:flex;gap:8px;margin-top:4px;">
                        <button type="button" class="btn-main" style="background:rgba(107,0,0,0.1);color:var(--maroon-mid);box-shadow:none;flex:0 0 auto;width:auto;padding:13px 16px" onclick="rBack(3)">Back</button>
                        <button type="button" class="btn-main" style="flex:1" onclick="rGo(3)">Continue</button>
                    </div>
                </div>

                {{-- STEP 4: SECURITY --}}
                <div id="rp4" style="display:none">
                    <div class="fgroup">
                        <label class="flabel">Password</label>
                        <input type="password" name="password" class="finput" id="rPw" placeholder="Min. 8 characters" oninput="pwStr(this.value)">
                    </div>
                    <div style="margin:-6px 0 12px">
                        <div style="display:flex;gap:3px;margin-bottom:3px">
                            <div id="ps1" style="flex:1;height:3px;border-radius:2px;background:var(--border);transition:background .3s"></div>
                            <div id="ps2" style="flex:1;height:3px;border-radius:2px;background:var(--border);transition:background .3s"></div>
                            <div id="ps3" style="flex:1;height:3px;border-radius:2px;background:var(--border);transition:background .3s"></div>
                            <div id="ps4" style="flex:1;height:3px;border-radius:2px;background:var(--border);transition:background .3s"></div>
                        </div>
                        <div id="psL" style="font-size:10px;color:var(--text-muted)">Password strength</div>
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="finput" placeholder="Re-enter password">
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:7px;margin-bottom:14px">
                        <input type="checkbox" id="terms" style="margin-top:2px;accent-color:var(--red-main)" required>
                        <label for="terms" style="font-size:11px;color:var(--text-muted)">
                            I agree to the <a href="#" style="color:var(--red-main)">Terms of Use</a> and <a href="#" style="color:var(--red-main)">Privacy Policy</a>
                        </label>
                    </div>
                    <div style="display:flex;gap:8px">
                        <button type="button" class="btn-main" style="background:rgba(107,0,0,0.1);color:var(--maroon-mid);box-shadow:none;flex:0 0 auto;width:auto;padding:13px 16px" onclick="rBack(4)">Back</button>
                        <button type="submit" class="btn-main" style="flex:1">Submit Registration</button>
                    </div>
                </div>

            </form>
            @endif
        </div>
    </div>
</div>

<script>
let regRole = 'student';
let curRS = 1;

function setRR(r) {
    regRole = r;
    document.getElementById('rrS').classList.toggle('sel', r === 'student');
    document.getElementById('rrL').classList.toggle('sel', r === 'librarian');
    document.getElementById('roleInput').value = r;
    document.getElementById('studentIdGroup').style.display = r === 'student' ? '' : 'none';
    // Update step 3 label
    document.getElementById('step3Label').textContent = r === 'student' ? 'Academic' : 'Work Info';
}

function rGo(step) {
    document.getElementById('rp' + step).style.display = 'none';
    const n = step + 1;
    if (n > 4) { document.getElementById('regForm').submit(); return; }

    // Show correct step 3 fields
    if (n === 3) {
        document.getElementById('rp3Student').style.display = regRole === 'student' ? '' : 'none';
        document.getElementById('rp3Librarian').style.display = regRole === 'librarian' ? '' : 'none';
    }

    document.getElementById('rp' + n).style.display = '';
    updRS(n); curRS = n;
    document.querySelector('.auth-right').scrollTop = 0;
}

function rBack(step) {
    document.getElementById('rp' + step).style.display = 'none';
    const p = step - 1;
    document.getElementById('rp' + p).style.display = '';
    updRS(p); curRS = p;
}

function updRS(cur) {
    [1, 2, 3, 4].forEach(i => {
        const s = document.getElementById('rs' + i);
        s.classList.remove('act', 'done');
        if (i < cur) s.classList.add('done');
        if (i === cur) s.classList.add('act');
    });
}

function pwStr(v) {
    let s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/\d/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const c = ['#c0392b', '#e67e22', '#f1c40f', '#27ae60'];
    const l = ['Weak', 'Fair', 'Good', 'Strong'];
    [1, 2, 3, 4].forEach(i => {
        document.getElementById('ps' + i).style.background = i <= s ? c[s - 1] : 'var(--border)';
    });
    const lb = document.getElementById('psL');
    lb.textContent = s ? l[s - 1] : 'Password strength';
    lb.style.color = s ? c[s - 1] : 'var(--text-muted)';
}

@if($errors->any())
    [1,2,3].forEach(i => document.getElementById('rp'+i).style.display='none');
    document.getElementById('rp4').style.display='';
    updRS(4);
@endif
</script>

</body>
</html>