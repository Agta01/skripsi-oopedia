@extends('mahasiswa.layouts.app')

@php
use Illuminate\Support\Str;
$userName = auth()->user()->name ?? 'Mahasiswa';
$today = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
@endphp

@section('title', 'Dashboard')

@section('content')

{{-- ═══════════════════════════════════════════════
     DASHBOARD WRAPPER
═══════════════════════════════════════════════ --}}
<div class="db-wrap">

    {{-- ── HERO BANNER ── --}}
    <div class="db-hero">
        <div class="db-hero__left">
            <p class="db-hero__date">{{ $today }}</p>
            <h1 class="db-hero__greeting">Halo, <span>{{ Str::words($userName, 2, '') }}</span> 👋</h1>
            <p class="db-hero__sub">Lanjutkan perjalanan belajar OOP-mu hari ini!</p>
            <a href="{{ route('mahasiswa.materials.index') }}" class="db-hero__cta">
                <i class="fas fa-play-circle"></i> Mulai Belajar
            </a>
        </div>
        <div class="db-hero__right">
            {{-- Inline SVG illustration --}}
            <svg viewBox="0 0 220 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="db-hero__svg">
                <!-- Monitor -->
                <rect x="30" y="40" width="160" height="110" rx="12" fill="#E8F0FE" stroke="#4A90D9" stroke-width="2.5"/>
                <rect x="44" y="54" width="132" height="82" rx="6" fill="#fff"/>
                <!-- Code lines -->
                <rect x="56" y="68" width="50" height="6" rx="3" fill="#4A90D9" opacity=".7"/>
                <rect x="56" y="80" width="80" height="6" rx="3" fill="#93C5FD" opacity=".8"/>
                <rect x="56" y="92" width="65" height="6" rx="3" fill="#4A90D9" opacity=".5"/>
                <rect x="68" y="104" width="55" height="6" rx="3" fill="#93C5FD" opacity=".8"/>
                <rect x="56" y="116" width="90" height="6" rx="3" fill="#4A90D9" opacity=".4"/>
                <!-- Stand -->
                <rect x="95" y="150" width="30" height="14" rx="3" fill="#CBD5E1"/>
                <rect x="75" y="163" width="70" height="8" rx="4" fill="#94A3B8"/>
                <!-- Diploma hat -->
                <ellipse cx="167" cy="62" rx="20" ry="6" fill="#004E98"/>
                <rect x="157" y="42" width="20" height="20" rx="2" fill="#0074D9"/>
                <polygon points="167,36 157,42 177,42" fill="#004E98"/>
                <line x1="177" y1="42" x2="182" y2="55" stroke="#FFD700" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="182" cy="57" r="3" fill="#FFD700"/>
                <!-- Stars -->
                <circle cx="22" cy="80" r="3" fill="#FCD34D" opacity=".8"/>
                <circle cx="14" cy="60" r="2" fill="#93C5FD" opacity=".7"/>
                <circle cx="200" cy="50" r="2.5" fill="#FCD34D" opacity=".8"/>
                <circle cx="208" cy="110" r="2" fill="#93C5FD" opacity=".6"/>
            </svg>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="db-stats">
        <div class="db-stat-card db-stat-card--blue">
            <div class="db-stat-card__icon"><i class="fas fa-book-open"></i></div>
            <div class="db-stat-card__body">
                <div class="db-stat-card__num" data-count="{{ $totalMaterials }}">0</div>
                <div class="db-stat-card__label">Total Materi</div>
            </div>
        </div>
        <div class="db-stat-card db-stat-card--indigo">
            <div class="db-stat-card__icon"><i class="fas fa-question-circle"></i></div>
            <div class="db-stat-card__body">
                <div class="db-stat-card__num" data-count="{{ $totalQuestions }}">0</div>
                <div class="db-stat-card__label">Total Soal</div>
            </div>
        </div>
        <div class="db-stat-card db-stat-card--green">
            <div class="db-stat-card__icon"><i class="fas fa-check-circle"></i></div>
            <div class="db-stat-card__body">
                <div class="db-stat-card__num" data-count="{{ $totalCorrectQuestions }}">0</div>
                <div class="db-stat-card__label">Soal Benar</div>
            </div>
        </div>
        <div class="db-stat-card db-stat-card--orange">
            <div class="db-stat-card__icon"><i class="fas fa-chart-pie"></i></div>
            <div class="db-stat-card__body">
                <div class="db-stat-card__num" data-count="{{ $questionProgressPercentage }}" data-suffix="%">0%</div>
                <div class="db-stat-card__label">Progress Soal</div>
            </div>
        </div>
    </div>

    {{-- ── CHART ROW ── --}}
    <div class="db-charts">
        {{-- Donut chart --}}
        <div class="db-card">
            <div class="db-card__header">
                <i class="fas fa-chart-pie db-card__icon-title" style="color:#004E98"></i>
                <h2 class="db-card__title">Distribusi Soal</h2>
            </div>
            <div class="db-donut-wrap">
                <div class="db-donut-canvas-container" style="position: relative; width: 180px; height: 180px; flex-shrink: 0;">
                    <canvas id="donutChart"></canvas>
                </div>
                <div class="db-donut-legend">
                    <div class="db-legend-item">
                        <span class="db-legend-dot" style="background:#10B981"></span>
                        <span class="db-legend-label">Beginner</span>
                        <span class="db-legend-val">{{ $easyQuestions }}</span>
                    </div>
                    <div class="db-legend-item">
                        <span class="db-legend-dot" style="background:#F59E0B"></span>
                        <span class="db-legend-label">Medium</span>
                        <span class="db-legend-val">{{ $mediumQuestions }}</span>
                    </div>
                    <div class="db-legend-item">
                        <span class="db-legend-dot" style="background:#EF4444"></span>
                        <span class="db-legend-label">Hard</span>
                        <span class="db-legend-val">{{ $hardQuestions }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bar chart progress per materi --}}
        <div class="db-card db-card--grow">
            <div class="db-card__header">
                <i class="fas fa-chart-bar db-card__icon-title" style="color:#0074D9"></i>
                <h2 class="db-card__title">Progress Per Materi</h2>
            </div>
            <div class="db-bar-wrap" style="position: relative; height: 230px; width: 100%;">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── MATERI PROGRESS LIST ── --}}
    <div class="db-card">
        <div class="db-card__header">
            <i class="fas fa-tasks db-card__icon-title" style="color:#004E98"></i>
            <h2 class="db-card__title">Progress Materi</h2>
            <a href="{{ route('mahasiswa.materials.index') }}" class="db-card__link">Lihat Semua →</a>
        </div>
        <div class="db-materi-list">
            @forelse($allMaterials as $mat)
            <div class="db-materi-item">
                <div class="db-materi-item__head">
                    <span class="db-materi-item__name">{{ $mat->title }}</span>
                    <span class="db-materi-item__pct">{{ $mat->progress_percentage }}%</span>
                </div>
                <div class="db-progress-track">
                    <div class="db-progress-fill
                        @if($mat->progress_percentage >= 100) db-progress-fill--done
                        @elseif($mat->progress_percentage > 0) db-progress-fill--mid
                        @else db-progress-fill--zero @endif"
                        style="width:0%" data-width="{{ $mat->progress_percentage }}%">
                    </div>
                </div>
                <div class="db-materi-item__meta">
                    <span>{{ $mat->completed_questions }} / {{ $mat->total_questions }} soal benar</span>
                    @if($mat->progress_percentage >= 100)
                        <span class="db-badge db-badge--done"><i class="fas fa-trophy"></i> Selesai</span>
                    @elseif($mat->progress_percentage > 0)
                        <span class="db-badge db-badge--mid">Berlangsung</span>
                    @else
                        <span class="db-badge db-badge--new">Belum Dimulai</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="db-empty">Belum ada materi tersedia.</div>
            @endforelse
        </div>
    </div>

    {{-- ── AKTIVITAS TERBARU ── --}}
    <div class="db-card">
        <div class="db-card__header">
            <i class="fas fa-history db-card__icon-title" style="color:#004E98"></i>
            <h2 class="db-card__title">Aktivitas Terbaru</h2>
        </div>
        <div class="db-timeline">
            @forelse($recentActivities as $activity)
            <div class="db-timeline-item">
                <div class="db-timeline-dot
                    @if($activity->type === 'achievement') db-timeline-dot--gold
                    @elseif($activity->type === 'milestone') db-timeline-dot--blue
                    @else db-timeline-dot--gray @endif">
                    @if($activity->type === 'achievement')
                        <i class="fas fa-trophy"></i>
                    @elseif($activity->type === 'milestone')
                        <i class="fas fa-star"></i>
                    @else
                        <i class="fas fa-book-reader"></i>
                    @endif
                </div>
                <div class="db-timeline-body">
                    <p class="db-timeline-title">
                        @if($activity->type === 'achievement') 🏆 Pencapaian Baru!
                        @elseif($activity->type === 'milestone') ⭐ Milestone Tercapai!
                        @else 📖 Progress Pembelajaran
                        @endif
                    </p>
                    <p class="db-timeline-desc">
                        @if($activity->type === 'achievement')
                            Menjawab benar {{ $activity->total_correct }} soal di
                        @elseif($activity->type === 'milestone')
                            Menyelesaikan level Hard di
                        @else
                            Mengerjakan soal <strong>{{ ucfirst($activity->difficulty) }}</strong> di
                        @endif
                        <strong>{{ $activity->material_title }}</strong>
                    </p>
                    <span class="db-timeline-time">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="db-empty">
                <i class="fas fa-inbox" style="font-size:2rem;color:#CBD5E1;display:block;margin-bottom:.5rem;"></i>
                Belum ada aktivitas. Mulai kerjakan soal pertamamu!
            </div>
            @endforelse
        </div>
    </div>

</div>{{-- /db-wrap --}}

@push('css')
<style>
/* ═══════════════════════════════════════════════
   DASHBOARD STYLES
   Background: mayoritas putih (#fff / #F8FAFC)
   Accent: biru (#004E98 / #0074D9)
═══════════════════════════════════════════════ */

/* Wrap */
.db-wrap {
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: 0 28px 40px;
    background: #F8FAFC;
    min-height: calc(100vh - 70px);
}

/* ─── Hero ─── */
.db-hero {
    background: linear-gradient(135deg, #004E98 0%, #0074D9 100%);
    border-radius: 18px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
    box-shadow: 0 8px 30px rgba(0,78,152,.22);
}
.db-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.db-hero__left { flex: 1; z-index: 1; }
.db-hero__date  { color: rgba(255,255,255,.65); font-size: 13px; margin-bottom: 8px; }
.db-hero__greeting {
    color: #fff;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.2;
}
.db-hero__greeting span { color: #FFD166; }
.db-hero__sub   { color: rgba(255,255,255,.8); font-size: 14px; margin-bottom: 20px; }
.db-hero__cta {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    padding: 10px 22px;
    border-radius: 30px;
    font-weight: 600; font-size: 14px;
    text-decoration: none;
    transition: all .2s;
}
.db-hero__cta:hover { background: rgba(255,255,255,.28); color: #fff; transform: translateY(-2px); }
.db-hero__right { flex-shrink: 0; margin-left: 24px; }
.db-hero__svg   { width: 200px; height: auto; drop-shadow: 0 8px 24px rgba(0,0,0,.1); }

/* ─── Stat Cards ─── */
.db-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.db-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border-left: 4px solid transparent;
    transition: transform .2s, box-shadow .2s;
}
.db-stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
.db-stat-card--blue   { border-left-color: #004E98; }
.db-stat-card--indigo { border-left-color: #6366F1; }
.db-stat-card--green  { border-left-color: #10B981; }
.db-stat-card--orange { border-left-color: #F59E0B; }

.db-stat-card__icon {
    width: 50px; height: 50px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.db-stat-card--blue   .db-stat-card__icon { background: rgba(0,78,152,.1);  color: #004E98; }
.db-stat-card--indigo .db-stat-card__icon { background: rgba(99,102,241,.1); color: #6366F1; }
.db-stat-card--green  .db-stat-card__icon { background: rgba(16,185,129,.1); color: #10B981; }
.db-stat-card--orange .db-stat-card__icon { background: rgba(245,158,11,.1); color: #F59E0B; }

.db-stat-card__num {
    font-size: 28px;
    font-weight: 800;
    color: #1E293B;
    line-height: 1;
    margin-bottom: 4px;
}
.db-stat-card__label { font-size: 12px; color: #64748B; font-weight: 500; }

/* ─── Generic Card ─── */
.db-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.db-card--grow { flex: 1; }
.db-card__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}
.db-card__icon-title { font-size: 18px; }
.db-card__title {
    font-size: 16px;
    font-weight: 700;
    color: #1E293B;
    flex: 1;
    margin: 0;
}
.db-card__link {
    font-size: 13px;
    color: #0074D9;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
}
.db-card__link:hover { text-decoration: underline; }

/* ─── Charts Row ─── */
.db-charts {
    display: flex;
    gap: 20px;
}
.db-donut-wrap {
    display: flex;
    align-items: center;
    gap: 28px;
    justify-content: center;
    padding: 10px 0;
}
.db-donut-legend { display: flex; flex-direction: column; gap: 14px; }
.db-legend-item  { display: flex; align-items: center; gap: 10px; }
.db-legend-dot   { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
.db-legend-label { font-size: 13px; color: #475569; flex: 1; }
.db-legend-val   { font-size: 14px; font-weight: 700; color: #1E293B; }
.db-bar-wrap     { position: relative; }

/* ─── Materi Progress ─── */
.db-materi-list  { display: flex; flex-direction: column; gap: 16px; }
.db-materi-item  {}
.db-materi-item__head {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}
.db-materi-item__name { font-size: 14px; font-weight: 600; color: #1E293B; }
.db-materi-item__pct  { font-size: 13px; font-weight: 700; color: #004E98; }
.db-progress-track {
    height: 8px;
    background: #F1F5F9;
    border-radius: 99px;
    overflow: hidden;
}
.db-progress-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.db-progress-fill--done { background: linear-gradient(90deg, #10B981, #34D399); }
.db-progress-fill--mid  { background: linear-gradient(90deg, #0074D9, #38BDF8); }
.db-progress-fill--zero { background: #E2E8F0; }
.db-materi-item__meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 6px;
    font-size: 12px;
    color: #94A3B8;
}
.db-badge {
    font-size: 11px; font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex; align-items: center; gap: 4px;
}
.db-badge--done { background: rgba(16,185,129,.1); color: #059669; }
.db-badge--mid  { background: rgba(0,116,217,.1);  color: #0074D9; }
.db-badge--new  { background: #F1F5F9; color: #94A3B8; }

/* ─── Timeline ─── */
.db-timeline { display: flex; flex-direction: column; gap: 0; }
.db-timeline-item {
    display: flex;
    gap: 16px;
    padding: 0 0 20px;
    position: relative;
}
.db-timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 19px; top: 40px;
    width: 2px;
    bottom: 0;
    background: #F1F5F9;
}
.db-timeline-dot {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    z-index: 1;
}
.db-timeline-dot--gold  { background: rgba(245,158,11,.12); color: #D97706; }
.db-timeline-dot--blue  { background: rgba(0,116,217,.1);   color: #0074D9; }
.db-timeline-dot--gray  { background: #F1F5F9; color: #64748B; }
.db-timeline-body { flex: 1; padding-top: 4px; }
.db-timeline-title { font-size: 14px; font-weight: 600; color: #1E293B; margin: 0 0 3px; }
.db-timeline-desc  { font-size: 13px; color: #64748B; margin: 0 0 5px; }
.db-timeline-time  { font-size: 12px; color: #94A3B8; }

/* ─── Empty state ─── */
.db-empty { text-align: center; padding: 32px 0; color: #94A3B8; font-size: 13px; }

/* ─── Responsive ─── */
@media (max-width: 960px) {
    .db-stats { grid-template-columns: repeat(2, 1fr); }
    .db-charts { flex-direction: column; }
    .db-hero__right { display: none; }
}
@media (max-width: 600px) {
    .db-wrap { padding: 16px; gap: 16px; }
    .db-stats { grid-template-columns: repeat(2, 1fr); }
    .db-hero  { padding: 24px 20px; }
    .db-hero__greeting { font-size: 22px; }
    .db-donut-wrap { flex-direction: column; gap: 16px; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── 1. COUNTING ANIMATION ── */
    document.querySelectorAll('.db-stat-card__num[data-count]').forEach(function (el) {
        const target  = parseInt(el.dataset.count, 10);
        const suffix  = el.dataset.suffix || '';
        const duration = 1200;
        const step    = target / (duration / 16);
        let current   = 0;
        const timer   = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = Math.round(current) + suffix;
            if (current >= target) clearInterval(timer);
        }, 16);
    });

    /* ── 2. PROGRESS BAR ANIMATION ── */
    setTimeout(function () {
        document.querySelectorAll('.db-progress-fill[data-width]').forEach(function (el) {
            el.style.width = el.dataset.width;
        });
    }, 200);

    /* ── 3. DONUT CHART ── */
    const donutCtx = document.getElementById('donutChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Beginner', 'Medium', 'Hard'],
                datasets: [{
                    data: [{{ $easyQuestions }}, {{ $mediumQuestions }}, {{ $hardQuestions }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                cutout: '68%',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.parsed + ' soal';
                            }
                        }
                    }
                }
            }
        });
    }

    /* ── 4. BAR CHART  ── */
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        const matLabels  = @json($allMaterials->pluck('title')->map(fn($t) => Str::limit($t, 18)));
        const matTotal   = @json($allMaterials->pluck('total_questions'));
        const matCorrect = @json($allMaterials->pluck('completed_questions'));

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: matLabels,
                datasets: [
                    {
                        label: 'Total Soal',
                        data: matTotal,
                        backgroundColor: 'rgba(0,116,217,.12)',
                        borderColor: 'rgba(0,116,217,.35)',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Soal Benar',
                        data: matCorrect,
                        backgroundColor: 'rgba(16,185,129,.75)',
                        borderColor: 'rgba(16,185,129,1)',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { stepSize: 1, font: { size: 11 } }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 12 }, boxWidth: 12, padding: 16 }
                    }
                }
            }
        });
    }

    /* ── 5. INTRO.JS TOUR ── */
    @if(!auth()->user()->has_seen_tour)
    setTimeout(startDashboardTour, 900);
    @endif
});

const LOGO_URL = "{{ asset('images/logo.png') }}";

function tourMascot(stepNum, total) {
    return `<div class="tour-mascot"><img src="${LOGO_URL}" alt="OOPedia"></div>
            <span class="tour-step-badge">${stepNum}/${total}</span>`;
}



function startDashboardTour() {
    const total = 5;
    introJs().setOptions({
        steps: [
            {
                intro: `<div class="tc">
                    <div class="tc__head">
                        <div class="tc__icon">👋</div>
                        <div class="tc__meta">
                            <div class="tc__step">Langkah 1 / ${total}</div>
                            <p class="tc__title">Selamat Datang!</p>
                        </div>
                    </div>
                    <p class="tc__body">Hai, <strong>{{ Str::words(auth()->user()->name ?? 'Kamu', 1, '') }}</strong>! Ini adalah <strong>Dashboard OOPedia</strong>. Mari saya tunjukkan fitur-fitur utamanya.</p>
                    <span class="tc__tag tc__tag--blue"><i class="fas fa-map"></i> 5 langkah panduan</span>
                </div>`
            },
            {
                element: document.querySelector('.db-stats'),
                intro: `<div class="tc">
                    <div class="tc__head">
                        <div class="tc__icon">📊</div>
                        <div class="tc__meta">
                            <div class="tc__step">Langkah 2 / ${total}</div>
                            <p class="tc__title">Statistik Belajar</p>
                        </div>
                    </div>
                    <p class="tc__body">Lihat total materi, jumlah soal, soal yang sudah benar, dan tingkat progress-mu sekilas di sini.</p>
                    <span class="tc__tag tc__tag--blue"><i class="fas fa-lightbulb"></i> Update realtime</span>
                </div>`,
                position: 'bottom'
            },
            {
                element: document.querySelector('.db-charts'),
                intro: `<div class="tc">
                    <div class="tc__head">
                        <div class="tc__icon">📈</div>
                        <div class="tc__meta">
                            <div class="tc__step">Langkah 3 / ${total}</div>
                            <p class="tc__title">Chart Interaktif</p>
                        </div>
                    </div>
                    <p class="tc__body">Donut chart menampilkan distribusi soal per tingkat kesulitan. Bar chart menunjukkan progres per materi.</p>
                    <span class="tc__tag tc__tag--green"><i class="fas fa-chart-bar"></i> Powered by Chart.js</span>
                </div>`,
                position: 'top'
            },
            {
                element: document.querySelector('.db-materi-list'),
                intro: `<div class="tc">
                    <div class="tc__head">
                        <div class="tc__icon">📚</div>
                        <div class="tc__meta">
                            <div class="tc__step">Langkah 4 / ${total}</div>
                            <p class="tc__title">Progress Per Materi</p>
                        </div>
                    </div>
                    <p class="tc__body">Progress bar hijau = selesai. Biru = sedang berlangsung. Abu = belum dimulai.</p>
                    <span class="tc__tag tc__tag--orange"><i class="fas fa-fire"></i> Selesaikan semua!</span>
                </div>`,
                position: 'top'
            },
            {
                intro: `<div class="tc" style="text-align:center">
                    <div class="tc__head" style="justify-content:center">
                        <div class="tc__icon">🚀</div>
                        <div class="tc__meta">
                            <div class="tc__step">Langkah 5 / ${total}</div>
                            <p class="tc__title">Ayo Mulai Belajar!</p>
                        </div>
                    </div>
                    <p class="tc__body">Kunjungi <strong>Materi</strong> untuk membaca, lalu coba <strong>Latihan Soal</strong> untuk uji kemampuanmu!</p>
                    <span class="tc__tag tc__tag--blue"><i class="fas fa-star"></i> Semangat!</span>
                </div>`
            }
        ],
        showBullets: true,
        exitOnOverlayClick: true,
        scrollToElement: true,
        nextLabel: 'Lanjut →',
        prevLabel: '← Kembali',
        skipLabel: '✕',
        doneLabel: '🎯 Mulai!'
    })
    .oncomplete(markTourComplete)
    .onexit(markTourComplete)
    .start();
}

function markTourComplete() {
    fetch("{{ route('mahasiswa.tour.complete') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).catch(e => console.warn('Tour error:', e));
}
</script>
@endpush
@endsection