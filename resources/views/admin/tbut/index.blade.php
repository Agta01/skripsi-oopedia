<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Analisis TBUT" />

        <div class="container-fluid py-4">

            {{-- ===== PAGE HERO ===== --}}
            <div class="tbut-hero animate-fade-in-down mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="text-white fw-bold mb-1" style="font-size:1.25rem">
                            <i class="material-icons align-middle me-2" style="font-size:22px">assignment_turned_in</i>
                            Analisis TBUT — Task-Based Usability Testing
                        </h5>
                        <p class="text-white opacity-8 mb-0 text-sm">Efisiensi &amp; Efektivitas pengerjaan tugas Virtual Lab (ISO&nbsp;9241‑11)</p>
                    </div>
                    <form method="GET" class="d-flex align-items-center gap-2 flex-shrink-0">
                        <label class="text-white text-xs opacity-8 mb-0 fw-semibold">Filter Materi</label>
                        <select name="material_id" class="form-select tbut-material-select" onchange="this.form.submit()">
                            <option value="">Semua Materi</option>
                            @foreach($materials as $mat)
                                <option value="{{ $mat->id }}" {{ $materialId == $mat->id ? 'selected' : '' }}>
                                    {{ $mat->title }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- ===== SUMMARY STATS ===== --}}
            @php
                $totalSessions  = \App\Models\TbutSession::count();
                $completedSess  = \App\Models\TbutSession::where('is_completed', true)->count();
                $avgDuration    = \App\Models\TbutSession::avg('duration_seconds');
                $avgRunCount    = \App\Models\TbutSession::avg('run_count');
                $completionRate = $totalSessions > 0 ? round(($completedSess / $totalSessions) * 100, 1) : 0;
                $successSess    = \App\Models\TbutSession::where('is_success', true)->count();
                $successRate    = $totalSessions > 0 ? round(($successSess / $totalSessions) * 100, 1) : 0;
            @endphp

            <div class="row g-3 mb-4 animate-fade-in-up">
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#0057B8,#003b7d)">
                            <i class="material-icons">people</i>
                        </div>
                        <p class="tbut-stat-label">Total Sesi</p>
                        <h3 class="tbut-stat-value">{{ $totalSessions }}</h3>
                        <p class="tbut-stat-sub">{{ $completedSess }} selesai</p>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#2dce89,#1a9e63)">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <p class="tbut-stat-label">Completion Rate</p>
                        <h3 class="tbut-stat-value">{{ $completionRate }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $completionRate }}%;background:#2dce89"></div></div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#fb6340,#d94a28)">
                            <i class="material-icons">timer</i>
                        </div>
                        <p class="tbut-stat-label">Rata-rata Durasi</p>
                        <h3 class="tbut-stat-value">{{ $avgDuration ? gmdate('i:s', intval($avgDuration)) : '--:--' }}</h3>
                        <p class="tbut-stat-sub">menit : detik</p>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#8655fc,#5e2fc7)">
                            <i class="material-icons">play_circle</i>
                        </div>
                        <p class="tbut-stat-label">Rata-rata Run Code</p>
                        <h3 class="tbut-stat-value">{{ $avgRunCount ? round($avgRunCount, 1) : '-' }}x</h3>
                        <p class="tbut-stat-sub">eksekusi per sesi</p>
                    </div>
                </div>
            </div>

            {{-- ===== CHART + SUCCESS RATE ===== --}}
            @if($tasks->isNotEmpty())
            <div class="row g-3 mb-4 animate-fade-in-up delay-2">
                <div class="col-lg-8">
                    <div class="card modern-card h-100">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex align-items-center modern-header">
                                <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px">
                                    <i class="material-icons" style="font-size:22px;color:#0057B8">bar_chart</i>
                                </div>
                                <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Completion Rate per Tugas</h6>
                            </div>
                        </div>
                        <div class="card-body pt-3 pb-3 px-4">
                            <canvas id="tbutBarChart" style="max-height:240px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card modern-card h-100">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex align-items-center modern-header">
                                <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px">
                                    <i class="material-icons" style="font-size:22px;color:#0057B8">donut_large</i>
                                </div>
                                <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Status Sesi</h6>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center pt-3">
                            <div style="max-width:180px;width:100%;position:relative">
                                <canvas id="tbutDonutChart"></canvas>
                            </div>
                            <div class="d-flex gap-4 mt-3">
                                <div class="text-center">
                                    <span class="tbut-legend-dot" style="background:#2dce89"></span>
                                    <p class="text-xs text-muted mb-0">Selesai</p>
                                    <p class="text-sm fw-bold mb-0" style="color:#2dce89">{{ $completedSess }}</p>
                                </div>
                                <div class="text-center">
                                    <span class="tbut-legend-dot" style="background:#f0f2f5;border:1px solid #dee2e6"></span>
                                    <p class="text-xs text-muted mb-0">Belum</p>
                                    <p class="text-sm fw-bold mb-0" style="color:#8392ab">{{ $totalSessions - $completedSess }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ===== TASKS TABLE ===== --}}
            <div class="row animate-fade-in-up delay-3">
                <div class="col-12">
                    <div class="card modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">assignment</i>
                                    </div>
                                    <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Rekap Per Tugas</h6>
                                </div>
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 me-2" style="border-radius:20px;font-size:12px">
                                    {{ $tasks->count() }} Tugas
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            @if($tasks->isEmpty())
                            <div class="text-center py-5">
                                <i class="material-icons text-muted" style="font-size:52px">inbox</i>
                                <p class="text-muted mt-2 mb-0">Belum ada tugas atau sesi TBUT.</p>
                            </div>
                            @else
                            <div class="table-responsive px-3">
                                <table class="table align-items-center mb-0 tbut-table">
                                    <thead>
                                        <tr>
                                            <th>Tugas</th>
                                            <th>Materi</th>
                                            <th>Kesulitan</th>
                                            <th class="text-center">Peserta</th>
                                            <th class="text-center">Selesai</th>
                                            <th class="text-center" style="min-width:140px">Completion Rate</th>
                                            <th class="text-center" style="min-width:140px">Success Rate</th>
                                            <th class="text-center">Avg Waktu</th>
                                            <th class="text-center">Avg Run</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                        @php
                                            $diffMap = [
                                                'beginner'     => ['color' => '#2dce89', 'bg' => 'rgba(45,206,137,.12)'],
                                                'intermediate' => ['color' => '#fb6340', 'bg' => 'rgba(251,99,64,.12)'],
                                                'advanced'     => ['color' => '#f5365c', 'bg' => 'rgba(245,54,92,.12)'],
                                            ];
                                            $diff = $diffMap[$task->difficulty] ?? ['color' => '#8392ab', 'bg' => '#f0f2f5'];
                                        @endphp
                                        <tr class="tbut-row">
                                            <td>
                                                <p class="mb-0 text-sm fw-semibold" style="color:#344767;max-width:200px">{{ $task->title }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs text-muted mb-0">{{ $task->material->title ?? '—' }}</p>
                                            </td>
                                            <td>
                                                <span class="tbut-diff-badge" style="color:{{ $diff['color'] }};background:{{ $diff['bg'] }};border:1px solid {{ $diff['color'] }}40">
                                                    {{ ucfirst($task->difficulty) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-sm fw-bold" style="color:#344767">{{ $task->total_attempts }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-sm fw-bold" style="color:#2dce89">{{ $task->completed_count }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="tbut-bar-wrap flex-grow-1">
                                                        <div class="tbut-bar-fill" style="width:{{ $task->completion_rate }}%;background:#2dce89"></div>
                                                    </div>
                                                    <span class="text-xs fw-bold" style="color:#2dce89;min-width:34px">{{ $task->completion_rate }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="tbut-bar-wrap flex-grow-1">
                                                        <div class="tbut-bar-fill" style="width:{{ $task->success_rate }}%;background:#0057B8"></div>
                                                    </div>
                                                    <span class="text-xs fw-bold" style="color:#0057B8;min-width:34px">{{ $task->success_rate }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="tbut-time-chip">
                                                    <i class="material-icons" style="font-size:13px">schedule</i>
                                                    {{ $task->avg_duration ? gmdate('i:s', intval($task->avg_duration)) : '—' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="tbut-run-chip">{{ $task->avg_run_count ? round($task->avg_run_count, 1) : '—' }}x</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.tbut.show', $task->id) }}"
                                                   class="tbut-detail-btn">
                                                    <i class="material-icons" style="font-size:14px">open_in_new</i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5 text-muted">
                                                <i class="material-icons d-block mb-2" style="font-size:40px">inbox</i>
                                                Belum ada tugas atau sesi TBUT.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <x-admin.tutorial />

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($tasks->isNotEmpty())
        // Bar chart
        const barCtx = document.getElementById('tbutBarChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tasks->map(fn($t) => \Illuminate\Support\Str::limit($t->title, 25))->values()) !!},
                    datasets: [
                        {
                            label: 'Completion Rate (%)',
                            data: {!! json_encode($tasks->pluck('completion_rate')->values()) !!},
                            backgroundColor: 'rgba(45,206,137,0.85)',
                            borderRadius: 6,
                        },
                        {
                            label: 'Success Rate (%)',
                            data: {!! json_encode($tasks->pluck('success_rate')->values()) !!},
                            backgroundColor: 'rgba(0,87,184,0.75)',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: { min: 0, max: 100, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#8392ab', font: { size: 11 } } },
                        x: { grid: { display: false }, ticks: { color: '#344767', font: { size: 11 } } }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 11 }, boxWidth: 14 } },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.raw}%` } }
                    }
                }
            });
        }
        @endif

        // Donut chart
        const donutCtx = document.getElementById('tbutDonutChart');
        if (donutCtx) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum Selesai'],
                    datasets: [{
                        data: [{{ $completedSess }}, {{ $totalSessions - $completedSess }}],
                        backgroundColor: ['#2dce89', '#f0f2f5'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '72%',
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw} sesi` } } }
                }
            });
        }
    });
    </script>
    @endpush

</x-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    .main-content { font-family: 'Inter', sans-serif; }

    /* ===== Hero Banner ===== */
    .tbut-hero {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        border-radius: 18px;
        padding: 1.5rem 2rem;
        box-shadow: 0 8px 30px rgba(0,87,184,0.3);
    }
    .tbut-material-select {
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-size: 13px;
        padding: .4rem .85rem;
        backdrop-filter: blur(6px);
        min-width: 180px;
    }
    .tbut-material-select option { color: #344767; background: #fff; }

    /* ===== Modern Card (same system as others) ===== */
    .modern-card {
        border: none;
        box-shadow: 0 10px 30px 0 rgba(0,0,0,0.05);
        border-radius: 16px;
        background: #fff;
        overflow: visible;
        margin-top: 2.5rem !important;
    }
    .modern-header {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        box-shadow: 0 8px 25px -8px rgba(0,87,184,0.45);
        border-radius: 16px;
        transform: translateY(-20px);
    }

    /* ===== Stat Cards ===== */
    .tbut-stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        padding: 1.25rem 1.25rem 1rem;
        border: 1px solid rgba(0,0,0,0.04);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .tbut-stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .tbut-stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .tbut-stat-icon i { color: #fff; font-size: 22px; }
    .tbut-stat-label { font-size: 11px; font-weight: 600; color: #8392ab; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .tbut-stat-value { font-size: 1.9rem; font-weight: 700; color: #344767; margin: 0; line-height: 1.1; }
    .tbut-stat-sub { font-size: 11px; color: #adb5bd; margin: 4px 0 0; }
    .tbut-mini-bar { height: 5px; background: #f0f2f5; border-radius: 99px; overflow: hidden; margin-top: 8px; }
    .tbut-mini-fill { height: 100%; border-radius: 99px; }

    /* ===== Table ===== */
    .tbut-table thead th {
        font-family: 'Inter', sans-serif;
        text-transform: uppercase; font-size: .63rem; font-weight: 700;
        letter-spacing: .5px; color: #8392ab;
        border-bottom: 2px solid #f0f2f5; padding: 1rem .75rem; white-space: nowrap;
    }
    .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; padding: .85rem .75rem; }
    .tbut-row { transition: background .15s ease; }
    .tbut-row:hover { background: #f8faff; }

    /* Difficulty badge */
    .tbut-diff-badge {
        font-size: 11px; font-weight: 700;
        padding: .28rem .7rem; border-radius: 8px; white-space: nowrap;
    }

    /* Progress bars in table */
    .tbut-bar-wrap { height: 7px; background: #f0f2f5; border-radius: 99px; overflow: hidden; min-width: 60px; }
    .tbut-bar-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }

    /* Time & run chips */
    .tbut-time-chip {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 12px; font-weight: 600; color: #fb6340;
        background: rgba(251,99,64,.1); border-radius: 8px;
        padding: .25rem .6rem;
    }
    .tbut-run-chip {
        display: inline-block; font-size: 12px; font-weight: 700;
        color: #8655fc; background: rgba(134,85,252,.1);
        border-radius: 8px; padding: .25rem .6rem;
    }

    /* Detail button */
    .tbut-detail-btn {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; font-weight: 600; color: #fff;
        background: linear-gradient(135deg,#0057B8,#003b7d);
        border: none; border-radius: 8px; padding: .3rem .75rem;
        text-decoration: none; transition: box-shadow .2s, transform .2s;
    }
    .tbut-detail-btn:hover { box-shadow: 0 4px 12px rgba(0,87,184,.35); transform: translateY(-1px); color: #fff; }

    /* Legend dot */
    .tbut-legend-dot {
        display: inline-block; width: 10px; height: 10px;
        border-radius: 50%; margin-bottom: 4px;
    }
</style>
