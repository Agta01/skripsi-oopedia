@extends('mahasiswa.layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')
@if(auth()->check() && auth()->user() === null)


<!-- Hidden forms for guest logout and redirect -->

@endif

<!-- ══════════════════════════════════════════ -->
<!-- HERO: Platform Introduction & OOP Purpose -->
<!-- ══════════════════════════════════════════ -->
<div class="oopedia-hero">
    <div class="oopedia-hero__bg"></div>
    <div class="oopedia-hero__particles">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </div>
    <div class="oopedia-hero__content">
        <div class="oopedia-hero__eyebrow">
            <i class="fas fa-graduation-cap"></i>
            Platform Pembelajaran Interaktif
        </div>
        <h1 class="oopedia-hero__title">
            Kuasai <span class="oopedia-hero__title--accent">Pemrograman<br>Berorientasi Objek</span>
            <br>dengan Cara yang Tepat
        </h1>
        <p class="oopedia-hero__desc">
            <strong>OOPedia</strong> adalah platform e-learning khusus untuk mempelajari 
            <strong>OOP (Object-Oriented Programming)</strong> — paradigma pemrograman yang mengorganisasi 
            kode menjadi <em>objek</em> berisi data dan fungsi. OOP adalah fondasi dari hampir semua 
            bahasa pemrograman modern seperti Java, Python, C++, dan PHP.
        </p>
        <div class="oopedia-hero__cta-wrap">
            <a href="#materi-list" class="oopedia-hero__cta oopedia-hero__cta--primary">
                <i class="fas fa-play-circle"></i> Mulai Belajar Sekarang
            </a>
            @guest
            <a href="{{ route('register') }}" class="oopedia-hero__cta oopedia-hero__cta--ghost">
                <i class="fas fa-user-plus"></i> Daftar Gratis
            </a>
            @endguest
        </div>
        <div class="oopedia-hero__stats">
            <div class="oopedia-hero__stat">
                <span class="oopedia-hero__stat-num">{{ $materials->count() }}</span>
                <span class="oopedia-hero__stat-label">Modul Materi</span>
            </div>
            <div class="oopedia-hero__stat-divider"></div>
            <div class="oopedia-hero__stat">
                <span class="oopedia-hero__stat-num">3</span>
                <span class="oopedia-hero__stat-label">Tingkat Kesulitan</span>
            </div>
            <div class="oopedia-hero__stat-divider"></div>
            <div class="oopedia-hero__stat">
                <span class="oopedia-hero__stat-num">100%</span>
                <span class="oopedia-hero__stat-label">Gratis & Terstruktur</span>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════ -->
<!-- SECTION: Why OOP?                         -->
<!-- ══════════════════════════════════════════ -->
<div class="oopedia-why mb-5">
    <div class="oopedia-section-head">
        <div class="oopedia-section-tag">❓ Mengapa OOP Penting?</div>
        <h2 class="oopedia-section-title">Apa itu Pemrograman Berorientasi Objek?</h2>
        <p class="oopedia-section-sub">
            OOP adalah pendekatan pemrograman yang memungkinkan kamu membangun perangkat lunak yang 
            terstruktur, mudah dipelihara, dan dapat digunakan kembali — kunci menjadi software engineer profesional.
        </p>
    </div>
    <div class="oopedia-why__grid">
        <div class="oopedia-why__card">
            <div class="oopedia-why__icon" style="background:#EFF6FF;color:#2563EB;">
                <i class="fas fa-cube"></i>
            </div>
            <h3 class="oopedia-why__card-title">Enkapsulasi</h3>
            <p class="oopedia-why__card-desc">
                Menyembunyikan detail implementasi dan hanya mengekspos antarmuka yang diperlukan. 
                Membuat kode lebih aman dan mudah dikelola.
            </p>
        </div>
        <div class="oopedia-why__card">
            <div class="oopedia-why__icon" style="background:#F0FDF4;color:#16A34A;">
                <i class="fas fa-sitemap"></i>
            </div>
            <h3 class="oopedia-why__card-title">Pewarisan</h3>
            <p class="oopedia-why__card-desc">
                Mewarisi properti dan metode dari kelas induk. Mengurangi duplikasi kode dan 
                mendukung penggunaan kembali (reusability).
            </p>
        </div>
        <div class="oopedia-why__card">
            <div class="oopedia-why__icon" style="background:#FFF7ED;color:#EA580C;">
                <i class="fas fa-shapes"></i>
            </div>
            <h3 class="oopedia-why__card-title">Polimorfisme</h3>
            <p class="oopedia-why__card-desc">
                Satu antarmuka, banyak implementasi. Memungkinkan objek yang berbeda untuk 
                merespons metode yang sama dengan cara yang berbeda.
            </p>
        </div>
        <div class="oopedia-why__card">
            <div class="oopedia-why__icon" style="background:#FDF4FF;color:#9333EA;">
                <i class="fas fa-layer-group"></i>
            </div>
            <h3 class="oopedia-why__card-title">Abstraksi</h3>
            <p class="oopedia-why__card-desc">
                Menyederhanakan kompleksitas dengan hanya menampilkan fitur esensial. 
                Membuat kode lebih mudah dipahami dan digunakan.
            </p>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════ -->
<!-- SECTION: Platform Features                -->
<!-- ══════════════════════════════════════════ -->
<div class="oopedia-features mb-5">
    <div class="oopedia-features__inner">
        <div class="oopedia-feat">
            <i class="fas fa-book-reader oopedia-feat__icon"></i>
            <div>
                <div class="oopedia-feat__title">Materi Terstruktur</div>
                <div class="oopedia-feat__desc">Dari konsep dasar class hingga design pattern lanjutan</div>
            </div>
        </div>
        <div class="oopedia-feat">
            <i class="fas fa-tasks oopedia-feat__icon"></i>
            <div>
                <div class="oopedia-feat__title">Latihan Soal Bertingkat</div>
                <div class="oopedia-feat__desc">Beginner, Medium, dan Hard untuk uji pemahaman</div>
            </div>
        </div>
        <div class="oopedia-feat">
            <i class="fas fa-code oopedia-feat__icon"></i>
            <div>
                <div class="oopedia-feat__title">Virtual Lab</div>
                <div class="oopedia-feat__desc">Praktikkan langsung penulisan kode OOP</div>
            </div>
        </div>
        <div class="oopedia-feat">
            <i class="fas fa-trophy oopedia-feat__icon"></i>
            <div>
                <div class="oopedia-feat__title">Leaderboard</div>
                <div class="oopedia-feat__desc">Pantau perkembanganmu dan bersaing secara sehat</div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════ -->
<!-- SECTION: Materials List                   -->
<!-- ══════════════════════════════════════════ -->
<div class="oopedia-section-head mt-4" id="materi-list">
    <div class="oopedia-section-tag">📚 Daftar Materi</div>
    <h2 class="oopedia-section-title">Pilih Materi yang Ingin Dipelajari</h2>
    <p class="oopedia-section-sub">Setiap modul dilengkapi penjelasan mendalam, contoh kode, dan soal latihan.</p>
</div>

<div class="row mt-3">
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
                    @php
                        $mediaUrl = $material->media->first()->media_url;
                        $imgSrc = str_starts_with($mediaUrl, 'storage/') ? asset($mediaUrl) : asset(ltrim($mediaUrl, '/'));
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $material->title }}" class="img-fluid">
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
                            
                            if ($isGuest) {
                                $beginnerCount = min(3, $material->questions->where('difficulty', 'beginner')->count());
                                $mediumCount = min(3, $material->questions->where('difficulty', 'medium')->count());
                                $hardCount = min(3, $material->questions->where('difficulty', 'hard')->count());
                                $configuredTotalQuestions = $beginnerCount + $mediumCount + $hardCount;
                            } else {
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
    /* ═══════════════════════════════════════════════
       OOPEDIA HERO
    ═══════════════════════════════════════════════ */
    .oopedia-hero {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        margin-top: 10px;
        margin-bottom: 40px;
        background: linear-gradient(135deg, #0d1b4b 0%, #1e3a8a 40%, #2563eb 100%);
        padding: 64px 48px;
        min-height: 440px;
        display: flex;
        align-items: center;
        box-shadow: 0 16px 48px rgba(37, 99, 235, .30);
    }
    .oopedia-hero__bg {
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 50%, rgba(99,179,237,.15) 0%, transparent 50%),
            radial-gradient(circle at 85% 20%, rgba(147,197,253,.12) 0%, transparent 45%),
            radial-gradient(circle at 2px 2px, rgba(255,255,255,.08) 1px, transparent 0);
        background-size: auto, auto, 28px 28px;
        pointer-events: none;
    }
    .oopedia-hero__particles span {
        position: absolute;
        display: block;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        animation: floatParticle 8s infinite ease-in-out;
        pointer-events: none;
    }
    .oopedia-hero__particles span:nth-child(1) { width:160px; height:160px; top:-40px; left:-60px; animation-delay:0s; }
    .oopedia-hero__particles span:nth-child(2) { width:90px;  height:90px;  bottom:20px; right:60px; animation-delay:-2s; }
    .oopedia-hero__particles span:nth-child(3) { width:60px;  height:60px;  top:30px; right:200px; animation-delay:-4s; }
    .oopedia-hero__particles span:nth-child(4) { width:120px; height:120px; bottom:-30px; left:100px; animation-delay:-1s; }
    .oopedia-hero__particles span:nth-child(5) { width:40px;  height:40px;  top:60%; right:30%; animation-delay:-3s; }
    .oopedia-hero__particles span:nth-child(6) { width:200px; height:200px; top:20px; right:-80px; animation-delay:-5s; opacity:.04; }
    .oopedia-hero__particles span:nth-child(7) { width:50px;  height:50px;  bottom:30%; left:40%; animation-delay:-6s; }
    .oopedia-hero__particles span:nth-child(8) { width:80px;  height:80px;  top:10%; left:30%; animation-delay:-7s; }
    @keyframes floatParticle {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.04); }
    }
    .oopedia-hero__content {
        position: relative;
        z-index: 2;
        max-width: 760px;
    }
    .oopedia-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(8px);
        color: rgba(255,255,255,.9);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 20px;
        margin-bottom: 22px;
        border: 1px solid rgba(255,255,255,.15);
    }
    .oopedia-hero__title {
        font-size: clamp(1.9rem, 4vw, 3rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 16px rgba(0,0,0,.18);
    }
    .oopedia-hero__title--accent {
        background: linear-gradient(90deg, #60A5FA, #93C5FD, #BFDBFE);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .oopedia-hero__desc {
        font-size: 1rem;
        color: rgba(255,255,255,.85);
        line-height: 1.75;
        margin-bottom: 30px;
        max-width: 660px;
    }
    .oopedia-hero__cta-wrap {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    .oopedia-hero__cta {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 13px 26px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        transition: all .2s;
    }
    .oopedia-hero__cta--primary {
        background: linear-gradient(135deg, #FBBF24, #F59E0B);
        color: #1a1a1a;
        box-shadow: 0 6px 20px rgba(251,191,36,.35);
    }
    .oopedia-hero__cta--primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(251,191,36,.5);
        color: #1a1a1a;
    }
    .oopedia-hero__cta--ghost {
        background: rgba(255,255,255,.12);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.25);
        backdrop-filter: blur(6px);
    }
    .oopedia-hero__cta--ghost:hover {
        background: rgba(255,255,255,.22);
        color: #fff;
        transform: translateY(-3px);
    }
    .oopedia-hero__stats {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    .oopedia-hero__stat {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .oopedia-hero__stat-num {
        font-size: 1.75rem;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }
    .oopedia-hero__stat-label {
        font-size: 11px;
        color: rgba(255,255,255,.65);
        font-weight: 600;
        letter-spacing: .5px;
        margin-top: 3px;
    }
    .oopedia-hero__stat-divider {
        width: 1px;
        height: 36px;
        background: rgba(255,255,255,.2);
    }

    /* ═══════════════════════════════════════════════
       SECTION HEADER
    ═══════════════════════════════════════════════ */
    .oopedia-section-head {
        text-align: center;
        margin-bottom: 36px;
    }
    .oopedia-section-tag {
        display: inline-block;
        background: #EFF6FF;
        color: #1D4ED8;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        padding: 6px 18px;
        border-radius: 20px;
        margin-bottom: 12px;
        text-transform: uppercase;
    }
    .oopedia-section-title {
        font-size: clamp(1.4rem, 3vw, 1.85rem);
        font-weight: 900;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 10px;
    }
    .oopedia-section-sub {
        font-size: 0.97rem;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* ═══════════════════════════════════════════════
       WHY OOP SECTION
    ═══════════════════════════════════════════════ */
    .oopedia-why__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    .oopedia-why__card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,.05);
        transition: transform .2s, box-shadow .2s;
    }
    .oopedia-why__card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,.10);
    }
    .oopedia-why__icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 16px;
    }
    .oopedia-why__card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .oopedia-why__card-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.65;
        margin: 0;
    }

    /* ═══════════════════════════════════════════════
       FEATURES BAR
    ═══════════════════════════════════════════════ */
    .oopedia-features {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
    }
    .oopedia-features__inner {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0;
    }
    .oopedia-feat {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 22px 24px;
        border-right: 1px solid rgba(255,255,255,.1);
        transition: background .2s;
    }
    .oopedia-feat:last-child { border-right: none; }
    .oopedia-feat:hover { background: rgba(255,255,255,.06); }
    .oopedia-feat__icon {
        font-size: 24px;
        color: #93C5FD;
        flex-shrink: 0;
    }
    .oopedia-feat__title {
        font-size: 13px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 2px;
    }
    .oopedia-feat__desc {
        font-size: 11.5px;
        color: rgba(255,255,255,.65);
        line-height: 1.4;
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