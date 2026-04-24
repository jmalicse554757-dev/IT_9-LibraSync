<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraSync — Login</title>
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
        .btn-main{width:100%;padding:13px;background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;border:none;border-radius:9px;font-family:'Lato',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 5px 16px rgba(107,0,0,0.28);margin-bottom:14px;}
        .btn-main:hover{transform:translateY(-2px);box-shadow:0 9px 22px rgba(107,0,0,0.38);}
        .auth-sw{text-align:center;font-size:12px;color:var(--text-muted);}
        .auth-sw a{color:var(--red-main);font-weight:700;text-decoration:none;}
        @media(max-width:768px){.auth-left{display:none;}}
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
        <div class="brand-tag">Library Management System</div>
        <ul class="feat-list">
            <li><div class="fi">📚</div>Thousands of books across all programs</li>
            <li><div class="fi">🗺️</div>Interactive library floor map by program</li>
            <li><div class="fi">🏫</div>Book collab rooms & rest zones</li>
            <li><div class="fi">📋</div>Full borrowing history & records</li>
        </ul>
        <div class="auth-copy">© 2025 LibraSync · All rights reserved</div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-title">Welcome back</div>
            <div class="auth-sub">Sign in to your account</div>

            {{-- Success message after registration --}}
            @if(session('success'))
            <div style="background:rgba(39,174,96,0.08);border:1px solid rgba(39,174,96,0.25);border-radius:9px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:#27ae60;">
                {{ session('success') }}
            </div>
            @endif

            {{-- Error message --}}
            @if($errors->any())
            <div style="background:rgba(192,57,43,0.07);border:1px solid rgba(192,57,43,0.2);border-radius:9px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:var(--red-bright);">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="fgroup">
                    <label class="flabel">Email / Username</label>
                    <input
                        type="text"
                        name="login"
                        class="finput"
                        placeholder="Enter your email or ID"
                        value="{{ old('login') }}"
                        autocomplete="username"
                    >
                </div>

                <div class="fgroup">
                    <label class="flabel">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="finput"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                </div>

                <div style="text-align:right;margin:-4px 0 18px">
                    <a href="#" style="font-size:11px;color:var(--red-main);font-weight:600">Forgot password?</a>
                </div>

                <button type="submit" class="btn-main">Sign In</button>
            </form>

            <div class="auth-sw">
                No account yet? <a href="{{ route('register') }}">Register here</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>