<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraSync — Library Management System</title>
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

        /* ── NAVBAR ── */
        nav{
            position:fixed;top:0;left:0;right:0;z-index:100;
            height:54px;
            background:var(--maroon-deep);
            display:flex;align-items:center;justify-content:space-between;
            padding:0 40px;
            border-bottom:1px solid rgba(255,255,255,0.08);
            box-shadow:0 2px 10px rgba(0,0,0,0.18);
        }
        .nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .nav-brand-box{
            width:32px;height:32px;
            background:var(--red-bright);
            border-radius:9px;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 3px 10px rgba(192,57,43,0.4);
        }
        .nav-brand-name{
            font-family:'Playfair Display',serif;
            font-size:20px;font-weight:700;color:#fff;letter-spacing:-0.5px;
        }
        .nav-brand-name em{color:var(--cream);font-style:normal;}
        .nav-links{display:flex;align-items:center;gap:10px;}
        .btn-outline{
            padding:8px 20px;
            border:1.5px solid rgba(255,255,255,0.3);
            border-radius:8px;
            font-family:'Lato',sans-serif;font-size:12px;font-weight:700;
            color:rgba(255,255,255,0.85);text-decoration:none;
            transition:all 0.2s;
        }
        .btn-outline:hover{border-color:#fff;color:#fff;}
        .btn-solid{
            padding:8px 20px;
            background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));
            border:none;border-radius:8px;
            font-family:'Lato',sans-serif;font-size:12px;font-weight:700;
            color:#fff;text-decoration:none;
            box-shadow:0 3px 10px rgba(107,0,0,0.28);
            transition:all 0.25s;
        }
        .btn-solid:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(107,0,0,0.38);}

        /* ── HERO — same as login left panel ── */
        .hero{
            min-height:calc(100vh - 54px);
            margin-top:54px;
            display:flex;
            overflow:hidden;
        }
        .hero-left{
            width:44%;
            background:linear-gradient(155deg,var(--maroon-deep) 0%,var(--maroon-mid) 55%,var(--red-main) 100%);
            display:flex;flex-direction:column;
            align-items:center;justify-content:center;
            padding:56px 44px;
            position:relative;overflow:hidden;
        }
        .hero-left::before{
            content:'';position:absolute;bottom:-90px;left:-90px;
            width:320px;height:320px;border-radius:50%;
            border:1.5px solid rgba(255,255,255,0.07);
        }
        .hero-left::after{
            content:'';position:absolute;top:-70px;right:-70px;
            width:250px;height:250px;border-radius:50%;
            border:1.5px solid rgba(255,255,255,0.07);
        }
        .brand{display:flex;align-items:center;gap:13px;margin-bottom:8px;position:relative;z-index:1;}
        .brand-box{
            width:58px;height:58px;
            background:rgba(255,255,255,0.12);
            border:2px solid rgba(255,255,255,0.2);
            border-radius:16px;
            display:flex;align-items:center;justify-content:center;
        }
        .brand-name{font-family:'Playfair Display',serif;font-size:38px;font-weight:700;color:#fff;letter-spacing:-1px;}
        .brand-name em{color:var(--cream);font-style:normal;}
        .brand-tag{color:rgba(255,255,255,0.4);font-size:11px;letter-spacing:3px;text-transform:uppercase;margin-bottom:44px;position:relative;z-index:1;}
        .feat-list{list-style:none;max-width:290px;position:relative;z-index:1;}
        .feat-list li{display:flex;align-items:center;gap:11px;color:rgba(255,255,255,0.68);font-size:13px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,0.08);}
        .feat-list li:last-child{border-bottom:none;}
        .fi{width:30px;height:30px;background:rgba(255,255,255,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .auth-copy{position:absolute;bottom:22px;color:rgba(255,255,255,0.2);font-size:10px;letter-spacing:1px;z-index:1;}

        /* ── RIGHT PANEL ── */
        .hero-right{
            flex:1;
            background:var(--cream);
            display:flex;align-items:center;justify-content:center;
            padding:44px 36px;
            position:relative;overflow-y:auto;
        }
        .hero-right::before{
            content:'';position:absolute;top:0;left:0;
            width:4px;height:100%;
            background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));
        }
        .right-content{width:100%;max-width:400px;}
        .right-title{
            font-family:'Playfair Display',serif;
            font-size:28px;font-weight:700;
            color:var(--maroon-deep);margin-bottom:5px;
        }
        .right-sub{color:var(--text-muted);font-size:13px;margin-bottom:28px;}

        /* Stat cards - same style as system */
        .stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;}
        .stat-card{
            background:var(--white);
            border:1px solid var(--border);
            border-radius:11px;
            padding:16px 14px 14px;
            position:relative;overflow:hidden;
            min-height:90px;
            display:flex;flex-direction:column;
        }
        .stat-card::before{
            content:'';position:absolute;left:0;top:0;
            width:4px;height:100%;
            background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));
        }
        .stat-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);}
        .stat-delta{font-size:10px;color:var(--text-muted);margin-top:2px;flex:1;}
        .stat-delta.up{color:var(--success);}
        .stat-delta.dn{color:var(--red-bright);}
        .stat-val{
            font-family:'Playfair Display',serif;
            font-size:32px;font-weight:700;
            color:var(--maroon-deep);
            line-height:1;margin-top:auto;
            align-self:flex-end;
        }

        /* CTA buttons */
        .cta-group{display:flex;flex-direction:column;gap:10px;margin-top:8px;}
        .btn-main{
            width:100%;padding:13px;
            background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));
            color:#fff;border:none;border-radius:9px;
            font-family:'Lato',sans-serif;font-size:14px;font-weight:700;
            cursor:pointer;text-decoration:none;text-align:center;
            display:block;
            transition:all .25s;
            box-shadow:0 5px 16px rgba(107,0,0,0.28);
        }
        .btn-main:hover{transform:translateY(-2px);box-shadow:0 9px 22px rgba(107,0,0,0.38);}
        .btn-secondary{
            width:100%;padding:12px;
            background:transparent;
            color:var(--maroon-deep);
            border:1.5px solid var(--border);
            border-radius:9px;
            font-family:'Lato',sans-serif;font-size:14px;font-weight:700;
            cursor:pointer;text-decoration:none;text-align:center;
            display:block;
            transition:all .25s;
        }
        .btn-secondary:hover{border-color:var(--maroon-deep);}
        .auth-sw{text-align:center;font-size:12px;color:var(--text-muted);margin-top:14px;}
        .auth-sw a{color:var(--red-main);font-weight:700;text-decoration:none;}

        /* ── FEATURES SECTION ── */
        .features{
            background:var(--cream);
            padding:80px 60px;
            position:relative;
        }
        .features::before{
            content:'';
            position:absolute;
            inset:0;
            background-image: radial-gradient(var(--border) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity:0.5;
            pointer-events:none;
        }
        .section-eyebrow{
            text-align:center;font-size:10px;font-weight:700;
            letter-spacing:2px;text-transform:uppercase;
            color:var(--red-main);margin-bottom:10px;
        }
        .section-title{
            text-align:center;
            font-family:'Playfair Display',serif;
            font-size:30px;font-weight:700;color:var(--maroon-deep);
            margin-bottom:8px;
        }
        .section-sub{
            text-align:center;font-size:13px;
            color:var(--text-muted);margin-bottom:44px;
        }
        .features-grid{
            display:grid;grid-template-columns:repeat(3,1fr);
            gap:14px;max-width:900px;margin:0 auto;
        }
        .feature-card{
            background:var(--white);
            border:1px solid var(--border);
            border-radius:11px;
            padding:20px 18px;
            position:relative;overflow:hidden;
            transition:all 0.2s;
            box-shadow:0 2px 7px rgba(59,0,0,0.04);
        }
        .feature-card::before{
            content:'';position:absolute;left:0;top:0;
            width:4px;height:100%;
            background:linear-gradient(180deg,var(--red-bright),var(--maroon-mid));
        }
        .feature-card:hover{transform:translateY(-3px);box-shadow:0 8px 22px rgba(59,0,0,0.09);}
        .feature-icon{
            width:36px;height:36px;
            background:var(--maroon-deep);
            border-radius:9px;
            display:flex;align-items:center;justify-content:center;
            margin-bottom:12px;
        }
        .feature-title{
            font-size:13px;font-weight:700;
            color:var(--maroon-deep);margin-bottom:5px;
        }
        .feature-desc{font-size:11px;color:var(--text-muted);line-height:1.6;}

        /* ── HOW IT WORKS ── */
        .how{padding:80px 60px;background:var(--cream);}
        .steps{
            display:flex;gap:0;max-width:680px;
            margin:0 auto;position:relative;    
        }
        .steps::before{display:none;}
        .step{flex:1;text-align:center;padding:0 14px;}
        .step-num{
            width:50px;height:50px;
            background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));
            border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            font-family:'Playfair Display',serif;
            font-size:18px;font-weight:700;color:#fff;
            margin:0 auto 14px;
            box-shadow:0 5px 16px rgba(107,0,0,0.28);
        }
        .step-title{font-size:13px;font-weight:700;color:var(--maroon-deep);margin-bottom:6px;}
        .step-desc{font-size:11px;color:var(--text-muted);line-height:1.6;}

        /* ── FOOTER ── */
        footer{
            padding:20px 40px;
            background:var(--maroon-deep);
            display:flex;align-items:center;justify-content:space-between;
            border-top:1px solid rgba(255,255,255,0.08);
        }
        .footer-brand{display:flex;align-items:center;gap:10px;}
        .footer-brand-box{
            width:28px;height:28px;
            background:rgba(255,255,255,0.12);
            border:1.5px solid rgba(255,255,255,0.2);
            border-radius:8px;
            display:flex;align-items:center;justify-content:center;
        }
        .footer-brand-name{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:#fff;letter-spacing:-0.3px;}
        .footer-brand-name em{color:var(--cream);font-style:normal;}
        .footer-copy{font-size:11px;color:rgba(255,255,255,0.25);}

        @media(max-width:768px){
            .hero-left{display:none;}
            .features-grid{grid-template-columns:1fr 1fr;}
            nav{padding:0 20px;}
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav>
    <a href="/" class="nav-logo">
        <div class="nav-brand-box">
            <svg width="18" height="18" viewBox="0 0 36 36" fill="none">
                <rect x="3" y="5" width="8" height="24" rx="2.5" fill="white" opacity=".95"/>
                <rect x="14" y="9" width="8" height="20" rx="2.5" fill="white" opacity=".75"/>
                <rect x="25" y="13" width="8" height="16" rx="2.5" fill="white" opacity=".55"/>
                <rect x="3" y="30" width="30" height="2.5" rx="1.25" fill="white" opacity=".8"/>
            </svg>
        </div>
        <span class="nav-brand-name">Libra<em>Sync</em></span>
    </a>
    <div class="nav-links">
        <a href="{{ route('login') }}" class="btn-outline">Login</a>
        <a href="{{ route('register') }}" class="btn-solid">Register</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">

    {{-- LEFT — same as login page --}}
    <div class="hero-left">
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
            <li>
                <div class="fi">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                Thousands of books across all programs
            </li>
            <li>
                <div class="fi">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                Book collab rooms &amp; rest zones
            </li>
            <li>
                <div class="fi">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                </div>
                Full borrowing history &amp; records
            </li>
            <li>
                <div class="fi">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                Analytics &amp; reports for librarians
            </li>
        </ul>
        <div class="auth-copy">© {{ date('Y') }} LibraSync · All rights reserved</div>
    </div>

    {{-- RIGHT — stats + CTA --}}
    <div class="hero-right">
        <div class="right-content">
            <div class="right-title">Welcome to LibraSync</div>
            <div class="right-sub">Your smart library management platform.</div>

            {{-- Stat Cards --}}
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Books</div>
                    <div class="stat-delta up">↑ 12 this week</div>
                    <div class="stat-val">3,482</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Active Students</div>
                    <div class="stat-delta up">↑ 34 new</div>
                    <div class="stat-val">1,204</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Books Borrowed</div>
                    <div class="stat-delta dn">8 overdue</div>
                    <div class="stat-val">247</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Room Bookings</div>
                    <div class="stat-delta up">↑ 22% this month</div>
                    <div class="stat-val">156</div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="cta-group">
                <a href="{{ route('register') }}" class="btn-main">Get Started — Register</a>
                <a href="{{ route('login') }}" class="btn-secondary">Login to Account</a>
            </div>
            <div class="auth-sw">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="features">
    <div class="section-eyebrow">What We Offer</div>
    <h2 class="section-title">Everything You Need</h2>
    <p class="section-sub">A complete library experience built for students and librarians.</p>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <div class="feature-title">Browse Books</div>
            <div class="feature-desc">Search and explore the full library catalog filtered by college, category, and availability.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <div class="feature-title">Borrow &amp; Return</div>
            <div class="feature-desc">Request books online and track your borrowing history with receipts and due dates.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
            </div>
            <div class="feature-title">Collab Rooms</div>
            <div class="feature-desc">Book collaboration rooms for group study sessions with easy online requests.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="feature-title">Rest Zones</div>
            <div class="feature-desc">Reserve a quiet rest zone slot and get confirmed entry by the librarian on duty.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div class="feature-title">Announcements</div>
            <div class="feature-desc">Stay updated with the latest library news, events, and important notices.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div class="feature-title">Analytics</div>
            <div class="feature-desc">Librarians and admins get full insights on borrowing trends and library usage.</div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="how">
    <div class="section-eyebrow">Simple Process</div>
    <h2 class="section-title">How It Works</h2>
    <p class="section-sub">Get started in just a few steps.</p>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <div class="step-title">Register</div>
            <div class="step-desc">Create your student account and wait for admin approval.</div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div class="step-title">Browse</div>
            <div class="step-desc">Explore the catalog and find the books you need.</div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-title">Borrow</div>
            <div class="step-desc">Request a book online and pick it up at the library.</div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">
        <div class="footer-brand-box">
            <svg width="16" height="16" viewBox="0 0 36 36" fill="none">
                <rect x="3" y="5" width="8" height="24" rx="2.5" fill="white" opacity=".95"/>
                <rect x="14" y="9" width="8" height="20" rx="2.5" fill="white" opacity=".75"/>
                <rect x="25" y="13" width="8" height="16" rx="2.5" fill="white" opacity=".55"/>
                <rect x="3" y="30" width="30" height="2.5" rx="1.25" fill="white" opacity=".8"/>
            </svg>
        </div>
        <span class="footer-brand-name">Libra<em>Sync</em></span>
    </div>
    <div class="footer-copy">© {{ date('Y') }} LibraSync · All rights reserved</div>
</footer>

</body>
</html>