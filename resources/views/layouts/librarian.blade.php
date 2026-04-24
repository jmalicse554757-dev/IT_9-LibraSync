<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraSync — @yield('title', 'Librarian')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root{
            --maroon-deep:#3b0000;--maroon-mid:#6b0000;--red-main:#a00000;
            --red-bright:#c0392b;--cream:#fdf6e3;--cream-dark:#f0e6cc;
            --white:#ffffff;--text-dark:#1a0000;--text-muted:#7a4040;
            --border:#e8d5c4;--success:#27ae60;--warning:#e67e22;
            --sidebar-w:215px;
        }
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Lato',sans-serif;background:var(--cream);color:var(--text-dark);display:flex;min-height:100vh;}

        /* SIDEBAR */
        .sidebar{width:var(--sidebar-w);background:var(--maroon-deep);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;}
        .sidebar-brand{display:flex;align-items:center;gap:10px;padding:18px 16px;border-bottom:1px solid rgba(255,255,255,0.08);}
        .sidebar-brand-box{width:36px;height:36px;background:var(--red-main);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .sidebar-brand-name{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#fff;}
        .sidebar-role{margin:0 12px 8px;padding:4px 10px;background:rgba(41,128,185,0.2);border:1px solid rgba(41,128,185,0.4);border-radius:20px;font-size:10px;font-weight:700;color:#5dade2;letter-spacing:1.5px;text-transform:uppercase;text-align:center;}
        .sidebar-menu-label{padding:14px 16px 6px;font-size:9px;font-weight:700;color:rgba(255,255,255,0.25);letter-spacing:2px;text-transform:uppercase;}
        .sidebar-nav{flex:1;overflow-y:auto;}
        .nav-item{display:flex;align-items:center;gap:11px;padding:10px 16px;color:rgba(255,255,255,0.55);font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;border-left:3px solid transparent;margin:1px 0;}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,0.06);}
        .nav-item.active{color:#fff;background:rgba(255,255,255,0.1);border-left-color:var(--red-bright);}
        .nav-icon{width:18px;height:18px;flex-shrink:0;opacity:0.7;}
        .nav-item.active .nav-icon,.nav-item:hover .nav-icon{opacity:1;}
        .sidebar-footer{padding:14px 16px;border-top:1px solid rgba(255,255,255,0.08);}
        .signout-btn{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,0.45);font-size:13px;font-weight:600;text-decoration:none;background:none;border:none;cursor:pointer;padding:8px 0;width:100%;transition:color .2s;}
        .signout-btn:hover{color:#ff6b6b;}

        /* TOPBAR */
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:56px;background:var(--maroon-deep);display:flex;align-items:center;justify-content:space-between;padding:0 24px;z-index:99;border-bottom:1px solid rgba(255,255,255,0.08);}
        .topbar-left{display:flex;align-items:center;gap:12px;}
        .topbar-right{display:flex;align-items:center;gap:14px;}
        .notif-btn{position:relative;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.6);padding:4px;}
        .notif-btn:hover{color:#fff;}
        .notif-badge{position:absolute;top:-2px;right:-2px;width:16px;height:16px;background:var(--red-bright);border-radius:50%;font-size:9px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;}

        /* MAIN */
        .main{margin-left:var(--sidebar-w);margin-top:56px;flex:1;padding:28px;min-height:calc(100vh - 56px);background:var(--cream);}

        /* STAT CARDS */
        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
        .stat-card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:16px 20px 18px;cursor:pointer;transition:all .2s;position:relative;overflow:hidden;display:flex;flex-direction:column;min-height:130px;}
        .stat-card::before{content:'';position:absolute;left:0;top:0;width:4px;height:100%;background:var(--red-main);}
        .stat-card:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(107,0,0,0.1);}
        .stat-card-top{display:flex;flex-direction:column;gap:2px;align-self:flex-start;width:100%;}
        .stat-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--text-muted);}
        .stat-sub{font-size:10px;color:var(--text-muted);margin-top:1px;}
        .stat-sub span{color:var(--red-main);font-weight:700;}
        .stat-value{font-family:'Playfair Display',serif;font-size:44px;font-weight:700;color:var(--maroon-deep);line-height:1;margin-top:auto;align-self:flex-end;}

        /* CARDS */
        .card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:20px;}
        .card-title{font-size:14px;font-weight:700;color:var(--maroon-deep);margin-bottom:16px;}

        /* GRID */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}

        /* TABLE */
        .tbl{width:100%;border-collapse:collapse;}
        .tbl th{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);padding:8px 12px;text-align:left;border-bottom:1px solid var(--border);}
        .tbl td{padding:10px 12px;font-size:13px;border-bottom:1px solid rgba(232,213,196,0.4);}
        .tbl tr:last-child td{border-bottom:none;}
        .tbl tr:hover td{background:rgba(253,246,227,0.6);}

        /* BADGES */
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;}
        .badge-pending{background:rgba(230,126,34,0.1);color:#e67e22;}
        .badge-active{background:rgba(39,174,96,0.1);color:#27ae60;}
        .badge-overdue{background:rgba(192,57,43,0.1);color:#c0392b;}
        .badge-due{background:rgba(230,126,34,0.1);color:#e67e22;}
        .badge-returned{background:rgba(122,64,64,0.07);color:var(--text-muted);}

        /* BUTTONS */
        .btn{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;border:none;transition:all .2s;}
        .btn-approve{background:var(--red-main);color:#fff;}
        .btn-approve:hover{background:var(--maroon-mid);}
        .btn-decline{background:rgba(107,0,0,0.08);color:var(--maroon-mid);border:1px solid var(--border);}
        .btn-decline:hover{background:rgba(192,57,43,0.1);color:var(--red-bright);}
        .btn-primary{background:linear-gradient(135deg,var(--maroon-mid),var(--red-bright));color:#fff;box-shadow:0 4px 12px rgba(107,0,0,0.25);}
        .btn-primary:hover{transform:translateY(-1px);}
        .btn-sm{padding:5px 12px;font-size:11px;}

        /* TABS */
        .tabs{display:flex;gap:4px;margin-bottom:18px;}
        .tab{padding:7px 18px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;border:1.5px solid var(--border);background:var(--white);color:var(--text-muted);transition:all .2s;}
        .tab.active{border-color:var(--red-main);background:var(--red-main);color:#fff;}

        /* MODAL */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:200;align-items:center;justify-content:center;}
        .modal-overlay.open{display:flex;}
        .modal{background:#fff;border-radius:16px;padding:28px;width:90%;max-width:640px;max-height:88vh;overflow-y:auto;position:relative;animation:mup .3s ease;}
        @keyframes mup{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
        .modal-close{position:absolute;top:16px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-muted);}
        .modal-close:hover{color:var(--text-dark);}
        .modal-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--maroon-deep);margin-bottom:18px;}

        /* PROGRESS BAR */
        .progress-bar{height:8px;background:rgba(59,0,0,0.07);border-radius:4px;overflow:hidden;}
        .progress-fill{height:100%;background:linear-gradient(90deg,var(--maroon-mid),var(--red-bright));border-radius:4px;transition:width .4s;}

        /* RECEIPT */
        .receipt-header{background:var(--maroon-deep);color:#fff;border-radius:10px;padding:16px 20px;margin-bottom:16px;}
        .receipt-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;opacity:0.6;margin-bottom:4px;}
        .receipt-value{font-size:13px;font-weight:600;}
        .receipt-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;}
        .receipt-field{background:var(--cream);border-radius:8px;padding:12px;}

        /* MODAL STAT BOXES */
        .modal-stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:18px;}
        .modal-stat{background:var(--cream);border:1px solid var(--border);border-radius:10px;padding:16px;display:flex;flex-direction:column;justify-content:space-between;min-height:80px;}
        .modal-stat-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);}
        .modal-stat-value{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:var(--maroon-deep);align-self:flex-end;}
        .prog-badge{display:inline-flex;padding:2px 8px;border-radius:12px;font-size:10px;font-weight:700;}
        .prog-cce{background:rgba(59,130,246,0.1);color:#3b82f6;}
        .prog-con{background:rgba(16,185,129,0.1);color:#10b981;}
        .prog-ccj{background:rgba(245,158,11,0.1);color:#f59e0b;}
        .prog-coe{background:rgba(239,68,68,0.1);color:#ef4444;}
        .prog-gen{background:rgba(107,114,128,0.1);color:#6b7280;}

        /* HAMBURGER */
        .hamburger{display:none;flex-direction:column;justify-content:center;gap:5px;background:none;border:none;cursor:pointer;padding:6px;margin-right:8px;}
        .hamburger span{display:block;width:20px;height:2px;background:rgba(255,255,255,0.8);border-radius:2px;transition:all .3s;}
        .hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg);}
        .hamburger.open span:nth-child(2){opacity:0;}
        .hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg);}

        /* SIDEBAR OVERLAY */
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:299;}
        .sidebar-overlay.open{display:block;}

        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);transition:transform .3s ease;z-index:300;}
            .sidebar.open{transform:translateX(0);}
            .main{margin-left:0;padding:16px;}
            .topbar{left:0;padding:0 16px;}
            .stat-grid{grid-template-columns:1fr 1fr;}
            .grid-2{grid-template-columns:1fr;}
            .hamburger{display:flex;}
            #notifPanel{width:calc(100vw - 32px);right:-8px;}
        }
        @media(max-width:480px){
            .stat-grid{grid-template-columns:1fr;}
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-box">
            <svg width="22" height="22" viewBox="0 0 36 36" fill="none">
                <rect x="3" y="5" width="8" height="24" rx="2.5" fill="white" opacity=".95"/>
                <rect x="14" y="9" width="8" height="20" rx="2.5" fill="white" opacity=".75"/>
                <rect x="25" y="13" width="8" height="16" rx="2.5" fill="white" opacity=".55"/>
                <rect x="3" y="30" width="30" height="2.5" rx="1.25" fill="white" opacity=".8"/>
            </svg>
        </div>
        <span class="sidebar-brand-name">LibraSync</span>
    </div>

    <div style="padding:10px 12px 4px;">
        <div class="sidebar-role">Librarian</div>
    </div>

    <div class="sidebar-menu-label">Menu</div>

    <nav class="sidebar-nav">
        <a href="{{ route('librarian.dashboard') }}" class="nav-item {{ request()->routeIs('librarian.dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('librarian.profile') }}" class="nav-item {{ request()->routeIs('librarian.profile') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            My Profile
        </a>
        <a href="{{ route('librarian.book-catalog') }}" class="nav-item {{ request()->routeIs('librarian.book-catalog*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Book Catalog
        </a>
        <a href="{{ route('librarian.book-reports') }}" class="nav-item {{ request()->routeIs('librarian.book-reports') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Book Reports
        </a>
        <a href="{{ route('librarian.book-requests') }}" class="nav-item {{ request()->routeIs('librarian.book-requests*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Book Requests
        </a>
        <a href="{{ route('librarian.collab-zones') }}" class="nav-item {{ request()->routeIs('librarian.collab-zones*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Collab & Rest Zones
        </a>
        <a href="{{ route('librarian.announcements') }}" class="nav-item {{ request()->routeIs('librarian.announcements*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/><circle cx="12" cy="12" r="1" fill="currentColor"/><path d="M3 5h2M19 5h2M12 2v2M12 20v2"/></svg>
            Announcements
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="signout-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- SIDEBAR OVERLAY --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- TOPBAR --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="hamburger" id="hamburger" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </button>
        <span style="color:rgba(255,255,255,0.5);font-size:12px;">@yield('page-title', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
        <div style="position:relative;">
            <button class="notif-btn" id="notifToggle" onclick="toggleNotifPanel()" style="position:relative;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </button>
            <div id="notifPanel" style="display:none;position:absolute;top:44px;right:0;width:340px;background:#fff;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,0.18);border:1px solid var(--border);z-index:999;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                    <div style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--maroon-deep);">Notifications</div>
                    <button onclick="markAllRead()" style="font-size:11px;font-weight:700;color:var(--red-main);background:none;border:none;cursor:pointer;">Mark all read</button>
                </div>
                <div id="notifList" style="max-height:360px;overflow-y:auto;">
                    <div style="text-align:center;padding:32px;color:var(--text-muted);font-size:13px;">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="main">
    @yield('content')
</main>

{{-- MODALS --}}
@yield('modals')

<script>
function openModal(id) {
    document.getElementById(id).classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});
</script>
@yield('scripts')
<script>
const notifPanel = document.getElementById('notifPanel');
const notifBadge = document.getElementById('notifBadge');
let notifOpen = false;

function toggleSidebar() {
    const sidebar   = document.querySelector('.sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburger');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    hamburger.classList.toggle('open');
}

function closeSidebar() {
    const sidebar   = document.querySelector('.sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburger');
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    hamburger.classList.remove('open');
}

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth <= 768) closeSidebar();
    });
});

function toggleNotifPanel() {
    notifOpen = !notifOpen;
    notifPanel.style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
}

function loadNotifications() {
    fetch('/notifications', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        notifBadge.style.display = 'none';
        notifBadge.textContent = '0';
        renderNotifications(data);
    });
}

function renderNotifications(items) {
    const list = document.getElementById('notifList');
    if (!items.length) {
        list.innerHTML = '<div style="text-align:center;padding:32px;color:#7a4040;font-size:13px;">No notifications yet</div>';
        return;
    }
    const typeColors = {
        approved:     { bg: 'rgba(39,174,96,0.08)',   color: '#27ae60' },
        penalty:      { bg: 'rgba(192,57,43,0.08)',   color: '#c0392b' },
        announcement: { bg: 'rgba(59,130,246,0.08)',  color: '#2563eb' },
        due_soon:     { bg: 'rgba(230,126,34,0.08)',  color: '#e67e22' },
        overdue:      { bg: 'rgba(192,57,43,0.08)',   color: '#c0392b' },
        book_request: { bg: 'rgba(139,92,246,0.08)',  color: '#7c3aed' },
        room_request: { bg: 'rgba(16,185,129,0.08)',  color: '#10b981' },
        rest_zone:    { bg: 'rgba(245,158,11,0.08)',  color: '#f59e0b' },
    };
    const typeIcons = {
        approved:     '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        penalty:      '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        announcement: '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        due_soon:     '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        overdue:      '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        book_request: '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
        room_request: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        rest_zone:    '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    };
    list.innerHTML = items.map(n => {
        const c = typeColors[n.type] || { bg: 'rgba(122,64,64,0.07)', color: '#7a4040' };
        const icon = typeIcons[n.type] || '<circle cx="12" cy="12" r="10"/>';
        const unreadDot = !n.is_read
            ? '<span style="width:7px;height:7px;border-radius:50%;background:#c0392b;flex-shrink:0;margin-top:4px;"></span>'
            : '<span style="width:7px;height:7px;flex-shrink:0;"></span>';
        const timeAgo = formatTimeAgo(n.created_at);
        const linkAttr = n.link ? `onclick="window.location='${n.link}'"` : '';
        const cursor = n.link ? 'cursor:pointer;' : '';
        return `<div ${linkAttr} style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-bottom:1px solid #f3ece4;${cursor}background:${n.is_read ? '#fff' : 'rgba(253,246,227,0.6)'};">
            <div style="width:34px;height:34px;border-radius:10px;background:${c.bg};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="${c.color}" stroke-width="2" viewBox="0 0 24 24">${icon}</svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;font-weight:700;color:#1a0000;margin-bottom:2px;line-height:1.3;">${n.title}</div>
                <div style="font-size:11px;color:#7a4040;line-height:1.4;">${n.message}</div>
                <div style="font-size:10px;color:#a07070;margin-top:4px;">${timeAgo}</div>
            </div>
            ${unreadDot}
        </div>`;
    }).join('');
}

function formatTimeAgo(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)    return 'Just now';
    if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    }).then(() => loadNotifications());
}

function pollUnreadCount() {
    fetch('/notifications/unread-count', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.count > 0) {
            notifBadge.style.display = 'flex';
            notifBadge.textContent = data.count > 9 ? '9+' : data.count;
        } else {
            notifBadge.style.display = 'none';
        }
    });
}

document.addEventListener('click', function(e) {
    const toggle = document.getElementById('notifToggle');
    if (notifOpen && !notifPanel.contains(e.target) && !toggle.contains(e.target)) {
        notifOpen = false;
        notifPanel.style.display = 'none';
    }
});

pollUnreadCount();
setInterval(pollUnreadCount, 30000);
</script>
</body>
</html>