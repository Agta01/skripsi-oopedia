@props(['activePage', 'userName', 'userRole'])

@php
    $dashboardRoute = auth()->user()->role_id === 3 ? 'mahasiswa.dashboard' : 'admin.dashboard';
    $pendingCount   = \App\Models\User::where('role_id', 2)->where('is_approved', false)->count();
    $initials       = strtoupper(substr($userName, 0, 1));
@endphp

<aside id="sidenav-main" class="oopsidebar">

    <div class="oopsidebar__logo">
        <a href="{{ route($dashboardRoute) }}" class="logo-link">
            <img src="{{ asset('images/logo.png') }}" alt="OOPEDIA">
        </a>
        <span class="logo-pulse"></span>
    </div>

    <div class="oopsidebar__user">
        <div class="avatar-wrapper">
            <div class="oopsidebar__user-avatar">{{ $initials }}</div>
            <span class="status-dot"></span>
        </div>
        <div class="oopsidebar__user-info">
            <strong>{{ $userName }}</strong>
            <span>{{ $userRole }}</span>
        </div>
    </div>

    <nav class="oopsidebar__nav">

        <a href="{{ route($dashboardRoute) }}"
           class="oopsidebar__item {{ $activePage == 'dashboard' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">dashboard</i></span>
            <span>Dashboard</span>
            <span class="hover-highlight"></span>
        </a>

             <div class="oopsidebar__section">
                <span>Kelola Pembelajaran</span>
            </div>

        <a href="{{ route('admin.materials.index') }}"
           class="oopsidebar__item {{ $activePage == 'materials' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">library_books</i></span>
            <span>Kelola Materi</span>
            <span class="hover-highlight"></span>
        </a>

        <a href="{{ route('admin.questions.dashboard') }}"
           class="oopsidebar__item {{ $activePage == 'questions-dashboard' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">quiz</i></span>
            <span>Kelola Soal</span>
            <span class="hover-highlight"></span>
        </a>

        @if(auth()->user()->role_id <= 2)
        <a href="{{ route('admin.question-banks.index') }}"
           class="oopsidebar__item {{ $activePage == 'question-banks' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">account_balance</i></span>
            <span>Bank Soal</span>
            <span class="hover-highlight"></span>
        </a>
        @endif

        <a href="{{ route('virtual-lab.index') }}"
           class="oopsidebar__item {{ $activePage == 'virtual-lab' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">code</i></span>
            <span>Virtual Lab</span>
            <span class="hover-highlight"></span>
        </a>

        @if(auth()->user()->role_id <= 2)
        <a href="{{ route('admin.virtual-lab-tasks.index') }}"
           class="oopsidebar__item {{ request()->routeIs('admin.virtual-lab-tasks.*') ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">assignment</i></span>
            <span>Kelola Tugas Lab</span>
            <span class="hover-highlight"></span>
        </a>
        @endif

             <div class="oopsidebar__section">
                <span>Data Mahasiswa</span>
            </div>

        <a href="{{ route('admin.students.index') }}"
           class="oopsidebar__item {{ $activePage == 'students' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">school</i></span>
            <span>Data Mahasiswa</span>
            <span class="hover-highlight"></span>
        </a>

        {{-- Section: Dosen (Superadmin only) --}}
        @if(auth()->user()->role_id == 1)
        <div class="oopsidebar__section">
            <span>Data Dosen</span>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="oopsidebar__item {{ $activePage == 'users' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">manage_accounts</i></span>
            <span>Data Dosen</span>
            <span class="hover-highlight"></span>
        </a>

        <a href="{{ route('admin.pending-admins') }}"
           class="oopsidebar__item {{ $activePage == 'pending-users' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">person_add</i></span>
            <span>Dosen Pending</span>
            @if($pendingCount > 0)
            <span class="oopsidebar__badge">{{ $pendingCount }}</span>
            @endif
            <span class="hover-highlight"></span>
        </a>
        @endif

        {{-- Section: Feedback & Analisis --}}
        @if(auth()->user()->role_id <= 2)
        <div class="oopsidebar__section">
            <span>Feedback &amp; Analisis</span>
        </div>

        <a href="{{ route('admin.ueq.index') }}"
           class="oopsidebar__item {{ $activePage == 'ueq' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">poll</i></span>
            <span>UEQ Survey</span>
            <span class="hover-highlight"></span>
        </a>

        <a href="{{ route('admin.tbut.index') }}"
           class="oopsidebar__item {{ $activePage == 'tbut' ? 'is-active' : '' }}">
            <span class="oopsidebar__icon"><i class="material-icons">timer</i></span>
            <span>Analisis TBUT</span>
            <span class="hover-highlight"></span>
        </a>
        @endif

    </nav>

    <div class="oopsidebar__footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="oopsidebar__logout">
                <i class="material-icons">logout</i>
                <span>Keluar</span>
                <span class="logout-shine"></span>
            </button>
        </form>
    </div>
</aside>

<style>
:root {
    --sidebar-bg-start: #0f1117;
    --sidebar-bg-end: #1a1d29;
    --sidebar-accent-primary: #4facfe;
    --sidebar-accent-secondary: #00f2fe;
    --sidebar-accent-purple: #667eea;
    --sidebar-accent-purple-end: #764ba2;
    --sidebar-text-primary: #fff;
    --sidebar-text-secondary: rgba(255,255,255,0.65);
    --sidebar-text-muted: rgba(255,255,255,0.3);
    --sidebar-hover-bg: rgba(255,255,255,0.07);
    --sidebar-active-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --sidebar-border: rgba(255,255,255,0.06);
    --sidebar-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 20px rgba(102,126,234,0.3); }
    50% { box-shadow: 0 0 30px rgba(102,126,234,0.6); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes iconBounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
}

.oopsidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
    background-size: 200% 200%;
    animation: gradientShift 15s ease infinite;
    border-right: 1px solid var(--sidebar-border);
    overflow: hidden;
    font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
}

.oopsidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(79,172,254,0.08) 0%, transparent 40%),
        radial-gradient(circle at 80% 80%, rgba(118,75,162,0.08) 0%, transparent 40%);
    pointer-events: none;
}

.oopsidebar__nav {
    flex: 1;
    overflow-y: auto;
    padding: 8px 12px 12px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
}

.oopsidebar__nav::-webkit-scrollbar {
    width: 4px;
}

.oopsidebar__nav::-webkit-scrollbar-track {
    background: transparent;
}

.oopsidebar__nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 4px;
}

.oopsidebar__nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.25);
}

.oopsidebar__logo {
    padding: 20px 20px 12px;
    text-align: center;
    flex-shrink: 0;
    position: relative;
}

.oopsidebar__logo .logo-link {
    display: inline-block;
    position: relative;
    transition: var(--sidebar-transition);
}

.oopsidebar__logo .logo-link:hover {
    transform: scale(1.05);
}

.oopsidebar__logo img {
    max-height: 56px;
    width: auto;
    filter: brightness(0) invert(1);
    opacity: .9;
    transition: var(--sidebar-transition);
}

.oopsidebar__logo img:hover {
    opacity: 1;
    filter: brightness(0) invert(1) drop-shadow(0 0 10px rgba(79,172,254,0.3));
}

.oopsidebar__logo .logo-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70px;
    height: 30px;
    background: radial-gradient(ellipse, rgba(79,172,254,0.15) 0%, transparent 70%);
    border-radius: 50%;
    animation: pulseGlow 3s ease-in-out infinite;
    pointer-events: none;
}

.oopsidebar__user {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 12px 8px;
    padding: 12px 14px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    flex-shrink: 0;
    transition: var(--sidebar-transition);
    position: relative;
    overflow: hidden;
}

.oopsidebar__user::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
    transition: 0.5s;
}

.oopsidebar__user:hover::before {
    left: 100%;
}

.oopsidebar__user:hover {
    background: rgba(255,255,255,0.08);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.oopsidebar__user .avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}

.oopsidebar__user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--sidebar-accent-purple) 0%, var(--sidebar-accent-purple-end) 100%);
    background-size: 200% 200%;
    animation: gradientShift 5s ease infinite;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--sidebar-transition);
    position: relative;
    z-index: 1;
}

.oopsidebar__user:hover .oopsidebar__user-avatar {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
}

.status-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: #22c55e;
    border: 2px solid #0f1117;
    border-radius: 50%;
    animation: float 2s ease-in-out infinite;
}

.oopsidebar__user-info {
    overflow: hidden;
    animation: fadeInUp 0.4s ease-out;
}

.oopsidebar__user-info strong {
    display: block;
    color: var(--sidebar-text-primary);
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: var(--sidebar-transition);
}

.oopsidebar__user:hover .oopsidebar__user-info strong {
    color: var(--sidebar-accent-secondary);
}

.oopsidebar__user-info span {
    display: block;
    font-size: 11px;
    color: var(--sidebar-text-muted);
    font-weight: 400;
    transition: var(--sidebar-transition);
}

.oopsidebar__user:hover .oopsidebar__user-info span {
    color: var(--sidebar-text-secondary);
}

.oopsidebar__section {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--sidebar-text-muted);
    padding: 18px 10px 6px;
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--sidebar-transition);
}

.oopsidebar__section::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    margin-left: 8px;
}

.oopsidebar__section span {
    animation: fadeInUp 0.4s ease-out;
}

.oopsidebar__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 12px;
    color: var(--sidebar-text-secondary);
    font-size: 13.5px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--sidebar-transition);
    margin-bottom: 2px;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.4s ease-out;
    animation-fill-mode: both;
}

.oopsidebar__item:nth-child(2) { animation-delay: 0.05s; }
.oopsidebar__item:nth-child(3) { animation-delay: 0.1s; }
.oopsidebar__item:nth-child(4) { animation-delay: 0.15s; }
.oopsidebar__item:nth-child(5) { animation-delay: 0.2s; }
.oopsidebar__item:nth-child(6) { animation-delay: 0.25s; }
.oopsidebar__item:nth-child(7) { animation-delay: 0.3s; }

.oopsidebar__item .hover-highlight {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, var(--sidebar-accent-primary), var(--sidebar-accent-secondary));
    border-radius: 0 3px 3px 0;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.oopsidebar__item:hover {
    background: var(--sidebar-hover-bg);
    color: var(--sidebar-text-primary);
    transform: translateX(4px);
}

.oopsidebar__item:hover .hover-highlight {
    transform: scaleY(1);
}

.oopsidebar__item.is-active {
    background: var(--sidebar-active-bg);
    color: var(--sidebar-text-primary);
    box-shadow: 0 4px 15px rgba(102,126,234,0.35), 0 0 20px rgba(102,126,234,0.2);
    transform: scale(1.02);
}

.oopsidebar__item.is-active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shimmer 2s infinite;
}

.oopsidebar__item.is-active .oopsidebar__icon {
    opacity: 1;
}

.oopsidebar__item.is-active .oopsidebar__icon i {
    animation: iconBounce 1s ease-in-out;
}

.oopsidebar__icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: rgba(255,255,255,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--sidebar-transition);
    position: relative;
}

.oopsidebar__item:hover .oopsidebar__icon {
    background: rgba(255,255,255,0.12);
    transform: scale(1.1);
}

.oopsidebar__item.is-active .oopsidebar__icon {
    background: rgba(255,255,255,.18);
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.oopsidebar__icon .material-icons {
    font-size: 18px;
    transition: var(--sidebar-transition);
}

.oopsidebar__item:hover .oopsidebar__icon .material-icons {
    color: var(--sidebar-accent-secondary);
}

.oopsidebar__item.is-active .oopsidebar__icon .material-icons {
    color: #fff;
}

.oopsidebar__badge {
    margin-left: auto;
    background: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
    line-height: 1.4;
    animation: pulseGlow 2s ease-in-out infinite;
    box-shadow: 0 0 10px rgba(239,68,68,0.4);
}

.oopsidebar__footer {
    padding: 12px;
    border-top: 1px solid rgba(255,255,255,.06);
    flex-shrink: 0;
    background: rgba(0,0,0,0.2);
}

.oopsidebar__logout {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(239,68,68,0.2);
    color: #f87171;
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--sidebar-transition);
    position: relative;
    overflow: hidden;
}

.oopsidebar__logout::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: 0.5s;
}

.oopsidebar__logout:hover::before {
    left: 100%;
}

.oopsidebar__logout:hover {
    background: rgba(239,68,68,0.25);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(239,68,68,0.3);
}

.oopsidebar__logout .material-icons {
    font-size: 18px;
    transition: var(--sidebar-transition);
}

.oopsidebar__logout:hover .material-icons {
    transform: translateX(3px);
}

.logout-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transform: skewX(-20deg);
    animation: shimmer 3s infinite;
}

.main-content {
    margin-left: 260px !important;
}

@media (max-width: 991px) {
    .oopsidebar { transform: translateX(-100%); }
    .main-content { margin-left: 0 !important; }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

@push('js')
@endpush
