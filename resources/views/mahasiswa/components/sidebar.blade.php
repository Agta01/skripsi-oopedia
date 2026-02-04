<div class="sidebar">
    <!-- Add a close button that's only visible on mobile -->
    <button class="sidebar-close d-block d-lg-none" id="sidebarCloseBtn">
        <i class="fas fa-times"></i>
    </button>

    <!-- Logo Section -->
    <div class="text-center py-3">
        <a href="{{ route('mahasiswa.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="OOPEDIA" class="img-fluid" style="max-height: 120px; width: auto;">
        </a>
    </div>

    <!-- MAIN MENU SECTION (ALWAYS VISIBLE) -->
    <div class="sidebar-header mt-2">
        <h5 class="sidebar-title">MENU UTAMA</h5>
    </div>

    <ul class="nav-menu">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('mahasiswa.dashboard') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}"
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Lihat statistik dan progres pembelajaran Anda">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Materi -->
        <li>
            <a href="{{ route('mahasiswa.materials.index') }}" 
               class="menu-item {{ request()->routeIs('mahasiswa.materials.index') || request()->segment(2) == 'materials' && !request()->segment(3) ? 'active' : '' }}"
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Akses materi pembelajaran PBO">
                <i class="fas fa-book"></i>
                <span>Materi</span>
            </a>
        </li>

        <!-- Latihan Soal -->
        <li>
            <a href="{{ route('mahasiswa.materials.questions.index') }}" 
               class="menu-item {{ request()->routeIs('mahasiswa.materials.questions.index') ? 'active' : '' }}"
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Uji pemahaman Anda dengan latihan soal">
                <i class="fas fa-question-circle"></i>
                <span>Latihan Soal</span>
            </a>
        </li>

        <!-- Virtual Lab (Only for Logged In Users) -->
        @auth
        <li>
            <a href="{{ route('virtual-lab.index') }}" 
               class="menu-item {{ request()->routeIs('virtual-lab.index') ? 'active' : '' }}"
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Praktik coding Java langsung di browser">
                <i class="fas fa-code"></i>
                <span>Virtual Lab</span>
            </a>
        </li>
        @endauth

        <!-- Profil (Only for Logged In Users) -->
        @auth
        <li>
            <a href="{{ route('mahasiswa.profile') }}" 
               class="menu-item {{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}"
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Lihat dan ubah profil Anda">
                <i class="fas fa-user"></i>
                <span>Profil Saya</span>
            </a>
        </li>
        @endauth
    </ul>

    <!-- DYNAMIC CONTEXT SECTION -->

    {{-- Context: Dashboard Sub-menus --}}
    @if(request()->routeIs('mahasiswa.dashboard*') && auth()->check())
        <div class="sidebar-header mt-3">
            <h5 class="sidebar-title">AKTIVITAS</h5>
        </div>
        <ul class="nav-menu">
            <li>
                <a href="{{ route('mahasiswa.dashboard.in-progress') }}" 
                   class="menu-item {{ request()->routeIs('mahasiswa.dashboard.in-progress') ? 'active' : '' }}">
                    <i class="fas fa-spinner"></i>
                    <span>Sedang Dipelajari</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.dashboard.completed') }}" 
                   class="menu-item {{ request()->routeIs('mahasiswa.dashboard.completed') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i>
                    <span>Selesai</span>
                </a>
            </li>
        </ul>
    @endif

    {{-- Context: Materi List (When Viewing Specific Material) --}}
    @if(request()->routeIs('mahasiswa.materials*') && !request()->routeIs('mahasiswa.materials.questions*') && !request()->routeIs('mahasiswa.materials.index'))
        <div class="sidebar-header mt-3">
            <h5 class="sidebar-title">DAFTAR MATERI</h5>
        </div>
        <ul class="nav-menu">
            @if(isset($materials))
                @foreach($materials as $m)
                    <li class="materi-item {{ request()->segment(3) == (is_array($m) ? $m['material']->id : $m->id) ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.materials.show', is_array($m) ? $m['material']->id : $m->id) }}"
                           class="menu-item {{ request()->segment(3) == (is_array($m) ? $m['material']->id : $m->id) ? 'active' : '' }}"
                           data-bs-toggle="tooltip" title="{{ is_array($m) ? $m['material']->title : $m->title }}">
                            <i class="fas fa-book-open"></i>
                            <span class="text-truncate" style="max-width: 170px;">{{ is_array($m) ? $m['material']->title : $m->title }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    @endif

    {{-- Context: Questions List (When Viewing Questions) --}}
    @if(request()->routeIs('mahasiswa.materials.questions*') && !request()->routeIs('mahasiswa.materials.questions.index'))
        <div class="sidebar-header mt-3">
            <h5 class="sidebar-title">LEVEL SOAL</h5>
        </div>
        <ul class="nav-menu">
            @php
                // Logic untuk mendapatkan ID materi saat ini dari URL
                $currentMaterialId = request()->segment(3); 
                $material = App\Models\Material::find($currentMaterialId);
            @endphp

            @if($material)
                <div class="px-3 mb-2 text-xs text-muted font-weight-bold uppercase">
                    {{ $material->title }}
                </div>

                <li>
                    <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'beginner']) }}"
                       class="menu-item {{ request()->query('difficulty') == 'beginner' ? 'active' : '' }}">
                        <i class="fas fa-star text-success"></i>
                        <span>Beginner</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'medium']) }}"
                       class="menu-item {{ request()->query('difficulty') == 'medium' ? 'active' : '' }}">
                        <i class="fas fa-star text-warning"></i>
                        <span>Medium</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.materials.questions.levels', ['material' => $material->id, 'difficulty' => 'hard']) }}"
                       class="menu-item {{ request()->query('difficulty') == 'hard' ? 'active' : '' }}">
                        <i class="fas fa-star text-danger"></i>
                        <span>Hard</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif

    <!-- ADDITIONAL MENUS -->
    <div class="sidebar-header mt-4">
        <h5 class="sidebar-title">LAINNYA</h5>
    </div>

    <ul class="nav-menu">
        <!-- Leaderboard (Always Visible) -->
        <li>
            @auth
                <a href="{{ route('mahasiswa.leaderboard') }}" 
                   class="menu-item {{ request()->routeIs('mahasiswa.leaderboard') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Peringkat</span>
                </a>
            @else
                <a href="#" class="menu-item" data-bs-toggle="tooltip" title="Login untuk melihat peringkat">
                    <i class="fas fa-trophy"></i>
                    <span>Peringkat</span>
                    <span class="badge bg-danger text-white ms-1" style="font-size: 0.6rem;">Login</span>
                </a>
            @endauth
        </li>

        <!-- UEQ Survey (Only for Mahasiswa Role) -->
        @if(auth()->check() && auth()->user()->role_id == 3)
        <li>
            <a href="{{ route('mahasiswa.ueq.create') }}" 
               class="menu-item {{ request()->routeIs('mahasiswa.ueq.create') ? 'active' : '' }}">
                <i class="fas fa-poll"></i>
                <span>UEQ Survey</span>
            </a>
        </li>
        @endif
    </ul>
</div>

@push('css')
<style>
    /* Styling Updates for Consistency */
    .sidebar-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #6c757d;
        padding: 0 1.5rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }
    
    .menu-item {
        color: #344767;
        font-weight: 500;
    }
    
    .menu-item.active {
        background-color: #e3f2fd; /* Light Blue for active state */
        color: #0b5ed7;
        font-weight: 600;
        border-radius: 0.5rem;
    }

    /* Additional mobile sidebar styles */
    .sidebar-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: #777;
        font-size: 1.2rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: none;
        z-index: 10;
    }
    
    @media (max-width: 991.98px) {
        .sidebar-close {
            display: block;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Close sidebar button functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.querySelector('.sidebar');
        const sidebarBackdrop = document.querySelector('.sidebar-backdrop');
        
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.remove('show');
                }
                localStorage.setItem('sidebarOpen', false);
            });
        }
    });
</script>
@endpush