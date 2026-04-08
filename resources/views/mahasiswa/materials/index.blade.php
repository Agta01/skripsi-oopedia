@extends('mahasiswa.layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')
@if(auth()->check() && auth()->user() === null)


<!-- Hidden forms for guest logout and redirect -->

@endif

<!-- Modern Colorful Hero Banner -->
<div class="hero-section mb-5 position-relative overflow-hidden" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);">
    <!-- Gradient Background -->
    <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); z-index: 1;"></div>
    
    <!-- Decorative Elements -->
    <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(255,255,255,0.05); top: -50px; left: -100px; filter: blur(2px); z-index: 2;"></div>
    <div class="position-absolute rounded-circle" style="width: 250px; height: 250px; background: rgba(255,255,255,0.08); bottom: -80px; right: -50px; filter: blur(10px); z-index: 2;"></div>
    <div class="position-absolute w-100 h-100" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 24px 24px; opacity: 0.5; z-index: 2;"></div>
    
    <!-- Content -->
    <div class="hero-content position-relative text-center text-white py-5 px-4" style="z-index: 3;">
        <div class="icon-wrapper mb-3 d-inline-block" style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 50%; backdrop-filter: blur(5px);">
            <i class="fas fa-graduation-cap fa-2x"></i>
        </div>
        <h1 class="fw-bold mb-3 text-white" style="letter-spacing: -0.5px; font-size: 2.8rem; text-shadow: 0 2px 10px rgba(0,0,0,0.15);">Materi Pembelajaran PBO</h1>
        <p class="lead opacity-9 mb-0 mx-auto" style="font-weight: 300; max-width: 700px; line-height: 1.6; font-size: 1.15rem;">
            Jelajahi, pahami, dan kuasai konsep dasar hingga lanjutan pemrograman berorientasi objek dengan modul dan latihan terstruktur kami.
        </p>
    </div>
</div>

<div class="row mt-5">
    @foreach($materials as $material)
    <div class="col-md-4 mb-4">
        <div class="material-card">
            <!-- Badge status di pojok kiri atas -->
            <div class="material-badge">
                <span class="badge-text">Tersedia</span>
            </div>
            
            <!-- Menampilkan gambar jika ada -->
            @if($material->media && $material->media->isNotEmpty())
                <div class="material-image">
                    <img src="{{ $material->media->first()->media_url }}" alt="{{ $material->title }}" class="img-fluid">
                </div>
            @else
                <div class="material-image default-image">
                    <div class="no-image-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>

            @endif
            
            <div class="material-icon">
                <i class="fas fa-book"></i>
            </div>
            
            <div class="material-content">
                <div class="material-title">
                    {{ $material->title }}
                </div>
                
                <div class="material-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i> {{ $material->creator ? $material->creator->name : 'Admin' }}

                    </div>
                    <div class="meta-item">
                        <i class="far fa-calendar-alt"></i> {{ $material->updated_at->format('d M Y') }}
                    </div>
                </div>
                
                <div class="content-divider"></div>
                
                <div class="material-stats">
                    <div class="stats-pill">
                        <i class="fas fa-question-circle"></i> 
                        @php
                            $isGuest = !auth()->check() || (auth()->check() && auth()->user()->role_id === 4);
                            
                            // Calculate configured question count
                            if ($isGuest) {
                                // For guests, limit to 3 questions per difficulty level
                                $beginnerCount = min(3, $material->questions->where('difficulty', 'beginner')->count());
                                $mediumCount = min(3, $material->questions->where('difficulty', 'medium')->count());
                                $hardCount = min(3, $material->questions->where('difficulty', 'hard')->count());
                                $configuredTotalQuestions = $beginnerCount + $mediumCount + $hardCount;
                            } else {
                                // For registered users, use admin configuration
                                $config = App\Models\QuestionBankConfig::where('material_id', $material->id)
                                    ->where('is_active', true)
                                    ->first();
                                
                                if ($config) {
                                    $configuredTotalQuestions = $config->beginner_count + $config->medium_count + $config->hard_count;
                                } else {
                                    $configuredTotalQuestions = $material->questions->count();
                                }
                            }
                        @endphp
                        
                        {{ $configuredTotalQuestions }} Soal
                        @if($isGuest)
                            <span class="guest-mode-badge ms-2">
                                <i class="fas fa-lock-open text-warning"></i>
                                Mode Tamu
                            </span>
                        @endif
                    </div>
                    
                </div>
                
                <a href="{{ route('mahasiswa.materials.show', $material->id) }}" class="material-link">
                    Baca Materi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('css')
<style>
    .hero-section {
        margin-top: 10px;
        transition: transform 0.3s ease;
    }
    
    .hero-section:hover {
        transform: translateY(-2px);
    }
    
    .material-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 87, 184, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        border: none;
        margin-bottom: 25px;
    }
    
    .material-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 87, 184, 0.25);
    }
    
    .material-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.4);
    }
    
    .material-image {
        height: 200px;
        position: relative;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        overflow: hidden;
    }
    
    .material-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .material-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 30px;
        background: linear-gradient(to top, rgba(255,255,255,0.9), transparent);
        z-index: 2;
    }
    
    .material-card:hover .material-image img {
        transform: scale(1.1);
    }
    
    .material-icon {
        position: absolute;
        top: 180px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #0057B8;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        z-index: 3;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 87, 184, 0.3);
        transition: transform 0.3s ease, background-color 0.3s ease;
    }
    
    .material-card:hover .material-icon {
        transform: rotate(15deg);
        background-color: #004095;
    }
    
    .material-content {
        padding: 25px 20px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .material-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0057B8;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    
    .material-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        color: #555;
    }
    
    .meta-item i {
        color: #0057B8;
        margin-right: 5px;
    }
    
    .content-divider {
        height: 1px;
        background-color: #e0e6ed;
        margin: 10px 0 15px;
    }
    
    .material-stats {
        margin-bottom: 15px;
    }
    
    .stats-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background-color: #f0f7ff;
        border-radius: 20px;
        font-size: 0.85rem;
        color: #0057B8;
        font-weight: 500;
    }
    
    .stats-pill i {
        margin-right: 5px;
    }
    
    .guest-mode-badge {
        background-color: #fff8e6;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        color: #d68c00;
        font-weight: 600;
    }
    
    .material-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: auto;
        padding: 10px 20px;
        background: linear-gradient(135deg, #0057B8, #0074D9);
        color: white;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 87, 184, 0.2);
    }
    
    .material-link:hover {
        background: linear-gradient(135deg, #004095, #0065c0);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 87, 184, 0.3);
        color: white;
    }
    
    .material-link i {
        margin-left: 8px;
        transition: transform 0.2s ease;
    }
    
    .material-link:hover i {
        transform: translateX(3px);
    }

    /* Tour CSS → sudah di mahasiswa.css (global) */
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<script>
    const MAT_LOGO = "{{ asset('images/logo.png') }}";
    function matMascot(n, t) {
        return `<div class="tour-mascot"><img src="${MAT_LOGO}" alt="OOP"></div><span class="tour-step-badge">${n}/${t}</span>`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        @auth
        @if(auth()->check() && !auth()->user()->has_seen_materials_tour)
            setTimeout(startMaterialsIndexTour, 800);
        @endif
        @endauth
    });

    function startMaterialsIndexTour() {
        const total = 4;
        introJs().setOptions({
            steps: [
                {
                    title: matMascot(1, total) + ' Halaman Materi',
                    intro: `<div class="tour-body">
                        <h4>📚 Selamat Datang di Materi!</h4>
                        <p>Di sini kamu bisa temukan semua materi <strong>Pemrograman Berorientasi Objek</strong> yang tersedia.</p>
                        <span class="tour-badge tour-badge--blue"><i class="fas fa-book"></i> ${total} langkah panduan</span>
                    </div>`
                },
                {
                    title: matMascot(2, total) + ' Kartu Materi',
                    element: document.querySelector('.material-card'),
                    intro: `<div class="tour-body">
                        <h4>🗂️ Kartu Materi</h4>
                        <p>Setiap kartu menampilkan judul materi, jumlah soal latihan, dan tombol untuk membaca selengkapnya.</p>
                        <span class="tour-badge tour-badge--green"><i class="fas fa-check"></i> Klik untuk membaca</span>
                    </div>`,
                    position: 'auto'
                },
                {
                    title: matMascot(3, total) + ' Tombol Baca',
                    element: document.querySelector('.material-link'),
                    intro: `<div class="tour-body">
                        <h4>👆 Mulai Belajar</h4>
                        <p>Klik tombol <strong>"Baca Materi"</strong> untuk masuk ke halaman detail materi lengkap beserta video dan penjelasannya.</p>
                        <span class="tour-badge tour-badge--orange"><i class="fas fa-arrow-right"></i> Mulai dari sini!</span>
                    </div>`,
                    position: 'top'
                },
                {
                    title: matMascot(4, total) + ' Selesai!',
                    intro: `<div class="tour-body" style="text-align:center">
                        <h4>🎉 Selamat Belajar!</h4>
                        <p>Pelajari semua materi secara urutan, lalu uji kemampuanmu di <strong>Latihan Soal</strong>!</p>
                        <span class="tour-badge tour-badge--blue"><i class="fas fa-rocket"></i> OOP Master!</span>
                    </div>`
                }
            ],
            showProgress: true,
            showBullets: true,
            exitOnOverlayClick: true,
            scrollToElement: true,
            nextLabel: 'Lanjut →',
            prevLabel: '← Kembali',
            skipLabel: '✕',
            doneLabel: '🎯 Siap!',
            exitOnEsc: true
        }).oncomplete(markMaterialsTourComplete)
          .onexit(markMaterialsTourComplete)
          .start();
    }

    function markMaterialsTourComplete() {
        fetch("{{ route('mahasiswa.materials.tour.complete') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).catch(e => console.warn('Tour mark error:', e));
    }
</script>
@endpush