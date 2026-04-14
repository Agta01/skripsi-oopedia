<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet">
@php
    $isGuest = !auth()->check() || (auth()->check() && auth()->user()->role_id == 4);
    $userName = auth()->user()->name ?? 'Guest';
@endphp

<style>
/* ─── Modern Floating Navbar Overrides ────────────────────────────────── */
.navbar {
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(12px) saturate(180%);
    -webkit-backdrop-filter: blur(12px) saturate(180%);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03) !important;
    padding: 0 20px !important;
    height: 70px !important;
    position: fixed !important;
    top: 0 !important;
    z-index: 1020 !important;
    transition: all 0.3s ease-in-out !important;
}

@media (min-width: 992px) {
    .navbar {
        margin: 0 0 0 {{ $isGuest ? '0' : '260px' }} !important;
        width: {{ $isGuest ? '100%' : 'calc(100% - 260px)' }} !important;
        border-radius: 0 !important;
        left: 0 !important;
        right: 0 !important;
    }
}

@media (max-width: 991.98px) {
    .navbar {
        margin: 0 !important;
        width: 100% !important;
        left: 0 !important;
        right: 0 !important;
        border-radius: 0 !important;
    }
}

.navbar .container-fluid {
    height: 70px !important;
    max-width: none !important;
    margin: 0 !important;
    padding: 0 15px !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Nav Links - Guests */
.navbar .nav-link {
    color: #64748b !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    padding: 8px 14px !important;
    border-radius: 9px !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
}
.navbar .nav-link:hover {
    background: #f1f5f9 !important;
    color: #0d6efd !important;
}

/* Search Bar Redesign */
.search-wrapper {
    position: relative;
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    height: 44px;
    padding: 0 20px 0 45px;
    border-radius: 50rem;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #1e293b;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
}

.search-input:focus {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 15px;
    transition: color 0.3s ease;
    pointer-events: none;
}

.search-input:focus + .search-icon {
    color: #3b82f6;
}

/* Notification Bell */
.notif-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.2s ease;
    text-decoration: none;
}

.notif-btn:hover, .notif-btn:focus, .notif-btn[aria-expanded="true"] {
    background: #f1f5f9;
    color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.notif-badge {
    position: absolute;
    top: 8px;
    right: 10px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-sizing: content-box;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
}

/* Profile Dropdown */
.profile-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 6px 16px 6px 6px;
    border-radius: 50rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.profile-toggle:hover, .profile-toggle[aria-expanded="true"] {
    background: #f8fafc;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

.profile-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.profile-name {
    color: #1e293b;
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1.2;
}

.profile-role {
    color: #64748b;
    font-size: 11px;
    font-weight: 500;
    line-height: 1.2;
}

/* Dropdown Menu Styles */
.navbar .dropdown-menu {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15);
    padding: 8px;
    margin-top: 12px;
    min-width: 220px;
    animation: dropFade 0.2s ease-out;
}

@keyframes dropFade {
    0% { opacity: 0; transform: translateY(-10px) scale(0.95); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

.navbar .dropdown-item {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13.5px;
    font-weight: 500;
    color: #475569;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
}

/* Hilangkan icon ganda (dari mahasiswa.css) */
.navbar .dropdown-item::before,
.navbar button.dropdown-item::before {
    display: none !important;
    content: none !important;
}

.navbar .dropdown-item:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.navbar .dropdown-item i {
    width: 20px;
    text-align: center;
    margin-right: 12px;
    font-size: 14px;
    color: #94a3b8;
    transition: color 0.2s ease;
}

.navbar .dropdown-item:hover i {
    color: #3b82f6;
}

.navbar .dropdown-item.text-danger:hover {
    background: #fef2f2;
    color: #ef4444;
}

.navbar .dropdown-item.text-danger:hover i {
    color: #ef4444;
}

/* Hamburger button */
#sidebarToggleBtn {
    background: #ffffff;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    transition: all 0.2s ease;
}
#sidebarToggleBtn:hover {
    background: #f1f5f9;
    color: #3b82f6;
    border-color: #cbd5e1;
}

@media (min-width: 992px) {
    #sidebarToggleBtn { display: none !important; }
}

/* Container top margin matching new height */
#main-container {
    margin-top: 70px !important;
    min-height: calc(100vh - 70px) !important;
}

/* Sidebar top adjustment */
.sidebar {
    top: 70px !important;
    height: calc(100vh - 70px) !important;
}

/* Notification List in Dropdown */
.notif-list {
    max-height: 300px;
    overflow-y: auto;
}

.notif-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 12px;
    transition: background 0.2s;
    text-decoration: none;
}

.notif-item:hover {
    background: #f8fafc;
}

.notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #eff6ff;
    color: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notif-item .notif-text {
    font-size: 13px;
    color: #334155;
    margin: 0 0 4px;
    line-height: 1.4;
}

.notif-item .notif-time {
    font-size: 11px;
    color: #94a3b8;
}
</style>

<nav class="navbar">
    <div class="container-fluid position-relative">
        {{-- ─── LEFT: TOGGLE & BRAND/BREADCRUMB ─── --}}
        <div class="d-flex align-items-center" style="{{ $isGuest ? '' : 'width: 250px;' }}">
            @if(!$isGuest)
            <button id="sidebarToggleBtn" class="btn btn-icon d-lg-none">
                <i class="fas fa-bars"></i>
            </button>
            @endif

            @auth
            <div class="d-none d-lg-flex align-items-center">
                <h6 class="mb-0 fw-bold d-flex align-items-center" style="color: #475569; font-size: 14.5px; letter-spacing: 0.3px;">
                    <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; margin-right: 12px;">
                        <i class="fas fa-graduation-cap"></i> 
                    </span>
                    {{ request()->routeIs('mahasiswa.dashboard*') ? 'Dasbor Pembelajaran' : (request()->routeIs('mahasiswa.materials.questions*') ? 'Latihan Soal Ujian' : (request()->routeIs('mahasiswa.materials*') ? 'Modul Interaktif PBO' : (request()->routeIs('mahasiswa.leaderboard*') ? 'Peringkat Kelas' : 'Area Mahasiswa'))) }}
                </h6>
            </div>
            @endauth

            @guest
            <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="{{ url('/') }}" style="text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 38px; object-fit: contain;">
                <span class="fw-bold fs-5 text-primary" style="letter-spacing: -0.5px;">OOPEDIA</span>
            </a>
            @endguest
        </div>

        {{-- ─── CENTER: SEARCH BAR ─── --}}
        @if($isGuest)
        <div class="d-none d-md-flex position-absolute top-50 start-50 translate-middle z-index-1">
            <ul class="nav-menu list-unstyled d-flex mb-0 align-items-center justify-content-center" style="gap: 10px;">
                <li>
                    <a href="{{ route('mahasiswa.materials.index') }}"
                       class="nav-link {{ request()->routeIs('mahasiswa.materials*') && !request()->routeIs('mahasiswa.materials.questions*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i> Materi
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.materials.questions.index') }}"
                       class="nav-link {{ request()->routeIs('mahasiswa.materials.questions*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i> Latihan Soal
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.leaderboard') }}"
                       class="nav-link {{ request()->routeIs('mahasiswa.leaderboard*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> Peringkat
                    </a>
                </li>
            </ul>
        </div>
        @else
        <div class="flex-grow-1 d-none d-md-flex justify-content-center px-4">
            <form action="{{ request()->routeIs('mahasiswa.materials.questions*') ? route('mahasiswa.materials.questions.index') : route('mahasiswa.materials.index') }}" method="GET" class="search-wrapper m-0" style="position: relative;">
                <input type="text" name="search" id="navbarSearchInput" class="search-input" placeholder="Cari materi atau latihan soal..." value="{{ request('search') }}" autocomplete="off">
                <i class="fas fa-search search-icon"></i>
                <button type="submit" class="d-none"></button>
                
                <!-- Suggestions Dropdown UI -->
                <div id="searchSuggestionsContainer" style="display: none; position: absolute; top: calc(100% + 5px); left: 0; right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; z-index: 1000;">
                    <ul id="searchSuggestionsList" class="list-unstyled mb-0" style="max-height: 300px; overflow-y: auto; text-align: left;">
                        <!-- populated by JS -->
                    </ul>
                </div>
            </form>
        </div>
        @endif

        {{-- ─── RIGHT: NOTIFICATIONS & PROFILE ─── --}}
        <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3" style="{{ $isGuest ? '' : 'width: 250px;' }}">
            @guest
            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4" style="font-weight: 500; font-size: 14px;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4" style="font-weight: 500; font-size: 14px; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);">Daftar</a>
            @endguest

            @auth
            <!-- Notification Bell -->
            <div class="dropdown">
                <a href="#" class="notif-btn" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="notif-badge"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notifDropdown" style="width: 320px; overflow: hidden;">
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-dark" style="font-size: 14px;">Notifikasi</h6>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-primary rounded-pill">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                        @endif
                    </div>
                    <div class="notif-list p-2">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <a href="#" class="notif-item">
                                <div class="notif-icon bg-primary text-white">
                                    <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }}"></i>
                                </div>
                                <div>
                                    <p class="notif-text">{!! $notification->data['message'] ?? 'Pesan notifikasi' !!}</p>
                                    <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 24px;"></i>
                                <p class="text-muted text-sm mb-0">Belum ada notifikasi baru</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-2 border-top text-center text-sm">
                        <a href="#" class="text-primary fw-medium text-decoration-none" style="font-size: 13px;">Tandai semua dibaca</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="profile-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=random' }}" alt="Profile" class="profile-avatar">
                    <div class="profile-info d-none d-sm-flex">
                        <span class="profile-name">{{ Str::limit($userName, 15) }}</span>
                        <span class="profile-role">Mahasiswa</span>
                    </div>
                    <i class="fas fa-chevron-down text-muted ms-1 d-none d-sm-block" style="font-size: 11px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('mahasiswa.profile') }}">
                            <i class="fas fa-user-circle"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('mahasiswa.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Progress Belajar
                        </a>
                    </li>
                    <li><hr class="dropdown-divider border-light"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>
</nav>

@if(request()->routeIs('mahasiswa.dashboard*'))
<div class="container-fluid px-4 pt-3">
    @guest
    <div class="alert alert-info d-flex align-items-center gap-2 border-0"
         style="border-radius:12px;background:rgba(13, 110, 253, 0.1);color:#084298;border:1px solid rgba(13, 110, 253, 0.2)!important">
        <i class="fas fa-info-circle"></i>
        Silakan login untuk mengakses semua fitur pembelajaran
    </div>
    @endguest
</div>
@endif

@push('scripts')
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
<script>
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    let sidebarClicked = false;

    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar toggle (mobile)
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebar = document.querySelector('.sidebar');
        let sidebarBackdrop = document.querySelector('.sidebar-backdrop');
        
        if (!sidebarBackdrop) {
            sidebarBackdrop = document.createElement('div');
            sidebarBackdrop.className = 'sidebar-backdrop';
            document.body.appendChild(sidebarBackdrop);
        }

        function toggleSidebar() {
            sidebar?.classList.toggle('show');
            sidebarBackdrop?.classList.toggle('show');
        }

        sidebarToggleBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        sidebarBackdrop.addEventListener('click', function() {
            if (sidebar?.classList.contains('show')) toggleSidebar();
        });

        // Intro.js tutorial (dashboard only, logged in)
        const isMainTutorialCompleted = sessionStorage.getItem('main_tutorial_complete');
        const isDashboardPage = {{ request()->routeIs('mahasiswa.dashboard*') ? 'true' : 'false' }};
        if (isLoggedIn && !isMainTutorialCompleted && isDashboardPage && !sessionStorage.getItem('skip_tour')) {
            startTutorial();
        }

        // Search Autocomplete Logic
        const searchInput = document.getElementById('navbarSearchInput');
        const suggestionsContainer = document.getElementById('searchSuggestionsContainer');
        const suggestionsList = document.getElementById('searchSuggestionsList');
        let debounceTimer;

        if (searchInput && suggestionsContainer) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                
                debounceTimer = setTimeout(function() {
                    fetch(`{{ route('mahasiswa.materials.search-suggestions') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsList.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.innerHTML = `<a href="${item.url}" style="display: block; padding: 12px 16px; color: #334155; text-decoration: none; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-search text-muted me-3" style="font-size: 14px;"></i>
                                            <span style="font-size: 14px; font-weight: 500;">${item.title}</span>
                                        </div>
                                    </a>`;
                                    suggestionsList.appendChild(li);
                                });
                                suggestionsContainer.style.display = 'block';
                            } else {
                                suggestionsList.innerHTML = `<li style="padding: 12px 16px; color: #94a3b8; font-size: 14px; text-align: center;">Tidak ada hasil ditemukan</li>`;
                                suggestionsContainer.style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Error fetching suggestions:', error));
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });
            
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && suggestionsList.children.length > 0) {
                    suggestionsContainer.style.display = 'block';
                }
            });
        }
    });

    function startTutorial() {
        introJs().setOptions({
            steps: [
                { intro: "Halo! Mari kita mulai dengan mengenal tampilan website ini." },
                { element: document.querySelector('.profile-toggle'), intro: "Kamu bisa melihat profilmu atau log out dari menu ini." }
            ],
            showProgress: true,
            showBullets: false,
            nextLabel: 'Lanjut',
            prevLabel: 'Kembali',
            doneLabel: 'Selesai'
        }).oncomplete(() => sessionStorage.setItem('main_tutorial_complete', 'true')).start();
    }
</script>
@endpush