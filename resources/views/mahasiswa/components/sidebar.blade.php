<style>
/* User card inside sidebar */
.sidebar-user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 12px 12px;
    padding: 10px 12px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 12px;
}
.sidebar-user-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: #fff;
    color: #004E98;
    font-weight: 700;
    font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sidebar-user-info strong {
    display: block;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}
.sidebar-user-info span {
    font-size: 10.5px; color: rgba(255,255,255,.6);
}

/* Section label */
.sidebar-section-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    padding: 14px 12px 4px;
}

/* Nav item */
.sidebar-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    color: rgba(255,255,255,.6) !important;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none !important;
    transition: all 0.18s ease;
    margin-bottom: 2px;
}
.sidebar-nav-item:hover {
    background: rgba(255,255,255,.07);
    color: #fff !important;
}
.sidebar-nav-item.active, .sidebar-nav-item.is-active {
    background: linear-gradient(135deg,#11998e 0%,#38ef7d 100%);
    color: #fff !important;
    box-shadow: 0 3px 12px rgba(17,153,142,.3);
}
.sidebar-nav-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    background: rgba(255,255,255,.08);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 13px;
}
.sidebar-nav-item.active .sidebar-nav-icon,
.sidebar-nav-item.is-active .sidebar-nav-icon {
    background: rgba(255,255,255,.2);
}

/* Difficulty badges */
.diff-badge {
    margin-left: auto;
    font-size: 9.5px; font-weight: 600;
    padding: 2px 6px; border-radius: 8px;
}
.diff-badge.beginner  { background:rgba(255,255,255,.2); color:#fff; }
.diff-badge.medium    { background:rgba(255,220,80,.2);  color:#ffe066; }
.diff-badge.hard      { background:rgba(255,100,100,.2); color:#ffb3b3; }

/* Badge login */
.sidebar-nav-badge {
    margin-left: auto;
    background: rgba(255,255,255,.25); color: #fff;
    font-size: 9px; font-weight: 700;
    padding: 2px 6px; border-radius: 10px;
}

/* Logo in sidebar */
.sidebar .sidebar-logo {
    text-align: center;
    padding: 20px 20px 12px;
}
.sidebar .sidebar-logo img {
    max-height: 150px; width: auto;
    opacity: .95;
    transition: opacity 0.2s;
}
.sidebar .sidebar-logo img:hover { opacity: 1; }

/* Close button mobile */
.sidebar-close-btn {
    position: absolute; top: 12px; right: 12px;
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(255,255,255,.08); border: none;
    color: rgba(255,255,255,.5); display: none;
    align-items: center; justify-content: center;
    cursor: pointer; z-index: 10; font-size: 13px;
}
@media (max-width: 991.98px) {
    .sidebar-close-btn { display: flex; }
}

/* Override mahasiswa.css sidebar defaults */
@media (min-width: 992px) {
    .sidebar {
        background: linear-gradient(180deg, #004E98 0%, #0063c0 60%, #0074D9 100%) !important;
        border-right: 1px solid rgba(255,255,255,0.1) !important;
        box-shadow: 4px 0 20px rgba(0,78,152,0.2) !important;
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        /* Full height, stick to left edge */
        margin: 0 !important;
        height: 100vh !important;
        border-radius: 0 !important;
        width: 260px !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        z-index: 1030 !important;
    }
}
@media (max-width: 991.98px) {
    .sidebar {
        background: linear-gradient(180deg, #004E98 0%, #0063c0 60%, #0074D9 100%) !important;
        border-right: 1px solid rgba(255,255,255,0.1) !important;
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        border-radius: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
        width: 260px !important;
    }
}

/* ── SECTION TITLE: non-clickable divider ── */
.sidebar .sidebar-title {
    font-size: 9px !important;
    font-weight: 800 !important;
    letter-spacing: 1.5px !important;
    text-transform: uppercase !important;
    color: rgba(255,255,255,.45) !important;
    padding: 0 !important;
    margin: 18px 12px 6px !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    cursor: default !important;
    pointer-events: none !important;
    user-select: none !important;
}
.sidebar .sidebar-title::before {
    content: '' !important;
    flex: 1 !important;
    height: 1px !important;
    background: rgba(255,255,255,.2) !important;
    display: block !important;
}
.sidebar .sidebar-title::after {
    content: '' !important;
    flex: 1 !important;
    height: 1px !important;
    background: rgba(255,255,255,.2) !important;
    display: block !important;
}
/* ── MENU ITEMS: clearly clickable ── */
.sidebar .menu-item {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 9px 13px !important;
    border-radius: 10px !important;
    color: rgba(255,255,255,.9) !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    transition: all 0.18s ease !important;
    margin-bottom: 4px !important;
    background: rgba(255,255,255,.08) !important;
    border: 1px solid rgba(255,255,255,.1) !important;
    cursor: pointer !important;
}
.sidebar .menu-item:hover {
    background: rgba(255,255,255,.2) !important;
    color: #fff !important;
    transform: translateX(4px) !important;
    border-color: rgba(255,255,255,.25) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,.1) !important;
}
.sidebar .menu-item.active {
    background: rgba(255,255,255,.28) !important;
    color: #fff !important;
    font-weight: 700 !important;
    border-left: 3px solid #fff !important;
    border-color: rgba(255,255,255,.4) !important;
    box-shadow: 0 3px 12px rgba(0,0,0,.18) !important;
}
.sidebar .menu-item i {
    color: rgba(255,255,255,.9) !important;
    width: 18px !important;
    text-align: center !important;
    flex-shrink: 0 !important;
}
.sidebar .menu-item.active i { color: #fff !important; }
.sidebar .nav-menu { padding: 0 8px !important; }
.sidebar .materi-item { list-style: none !important; }
</style>

@php
    $sInitials = auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : '?';
    $sName     = auth()->check() ? auth()->user()->name : '';
@endphp

<div class="sidebar">

    {{-- Close btn (mobile) --}}
    <button class="sidebar-close-btn" id="sidebarCloseBtn">
        <i class="fas fa-times"></i>
    </button>

    {{-- Logo --}}
    <div class="sidebar-logo">
        <a href="{{ route('mahasiswa.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="OOPEDIA">
        </a>
    </div>

    {{-- User card --}}
    @auth
    <div class="sidebar-user-card">
        <div class="sidebar-user-avatar">{{ $sInitials }}</div>
        <div class="sidebar-user-info">
            <strong>{{ $sName }}</strong>
            <span>Mahasiswa</span>
        </div>
    </div>
    @endauth

    {{-- Main Menu --}}
    <div class="sidebar-header mt-1">
        <h5 class="sidebar-title">Menu Utama</h5>
    </div>
    <ul class="nav-menu">
        <li>
            <a href="{{ route('mahasiswa.dashboard') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.materials.index') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.materials.index') || (request()->segment(2) == 'materials' && !request()->segment(3)) ? 'active' : '' }}">
                <i class="fas fa-book"></i><span>Materi</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.materials.questions.index') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.materials.questions.index') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i><span>Latihan Soal</span>
            </a>
        </li>
        @auth
        <li>
            <a href="{{ route('virtual-lab.index') }}"
               class="menu-item {{ request()->routeIs('virtual-lab.index') ? 'active' : '' }}">
                <i class="fas fa-code"></i><span>Virtual Lab</span>
            </a>
        </li>
        @endauth
    </ul>

    {{-- Aktivitas --}}
    @auth
    <div class="sidebar-header mt-2">
        <h5 class="sidebar-title">Aktivitas</h5>
    </div>
    <ul class="nav-menu">
        <li>
            <a href="{{ route('mahasiswa.dashboard.in-progress') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.dashboard.in-progress') ? 'active' : '' }}">
                <i class="fas fa-spinner"></i><span>Sedang Dipelajari</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.dashboard.completed') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.dashboard.completed') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i><span>Selesai</span>
            </a>
        </li>
    </ul>
    @endauth

    {{-- Level Soal (contextual) --}}
    @if(isset($material) && $material instanceof App\Models\Material)
    <div class="sidebar-header mt-2">
        <h5 class="sidebar-title">Level Soal</h5>
    </div>
    <div class="px-3 mb-1" style="font-size:10.5px;color:rgba(255,255,255,.35)">{{ $material->title }}</div>
    <ul class="nav-menu">
        <li>
            <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'beginner']) }}"
               class="menu-item {{ request()->query('difficulty') == 'beginner' ? 'active' : '' }}">
                <i class="fas fa-star" style="color:#10b981!important"></i>
                <span>Beginner</span><span class="diff-badge beginner ms-auto">Mudah</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'medium']) }}"
               class="menu-item {{ request()->query('difficulty') == 'medium' ? 'active' : '' }}">
                <i class="fas fa-star" style="color:#f59e0b!important"></i>
                <span>Medium</span><span class="diff-badge medium ms-auto">Sedang</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'hard']) }}"
               class="menu-item {{ request()->query('difficulty') == 'hard' ? 'active' : '' }}">
                <i class="fas fa-star" style="color:#ef4444!important"></i>
                <span>Hard</span><span class="diff-badge hard ms-auto">Sulit</span>
            </a>
        </li>
    </ul>
    @endif

    {{-- Lainnya --}}
    <div class="sidebar-header mt-2">
        <h5 class="sidebar-title">Lainnya</h5>
    </div>
    <ul class="nav-menu">
        <li>
            @auth
            <a href="{{ route('mahasiswa.leaderboard') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.leaderboard') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i><span>Peringkat</span>
            </a>
            @else
            <a href="#" class="menu-item">
                <i class="fas fa-trophy"></i><span>Peringkat</span>
                <span class="sidebar-nav-badge ms-2">Login</span>
            </a>
            @endauth
        </li>
        @if(auth()->check() && auth()->user()->role_id == 3)
        <li>
            <a href="{{ route('mahasiswa.ueq.create') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.ueq.create') ? 'active' : '' }}">
                <i class="fas fa-poll"></i><span>UEQ Survey</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mahasiswa.profile') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i><span>Profil Saya</span>
            </a>
        </li>
        @endif
    </ul>

</div>{{-- /sidebar --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const sidebar  = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    function closeSidebar() {
        sidebar?.classList.remove('show');
        backdrop?.classList.remove('show');
    }
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    backdrop?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && sidebar?.classList.contains('show')) closeSidebar();
    });
    document.querySelectorAll('.sidebar .menu-item').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991.98) closeSidebar();
        });
    });
});
</script>