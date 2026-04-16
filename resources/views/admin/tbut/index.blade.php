<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Analisis TBUT" />

        <div class="container-fluid py-4">

            {{-- ═══ HERO BANNER ═══ --}}
            <div class="tbut-hero mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="tbut-hero-icon">
                                <i class="material-icons" style="font-size:20px;color:#fff;">assignment_turned_in</i>
                            </div>
                            <h5 class="text-white fw-bold mb-0" style="font-size:1.15rem;">
                                Analisis TBUT — Task-Based Usability Testing
                            </h5>
                        </div>
                        <p class="text-white mb-0" style="font-size:12.5px;opacity:.8;padding-left:44px;">
                            Efisiensi &amp; Efektivitas pengerjaan tugas Virtual Lab (ISO&nbsp;9241‑11) — Difficulty Score Framework
                        </p>
                    </div>
                    <form method="GET" class="d-flex align-items-center gap-2 flex-shrink-0">
                        <label class="text-white mb-0 fw-semibold" style="font-size:12px;opacity:.8;">Filter Materi</label>
                        <select name="material_id" class="tbut-filter-select" onchange="this.form.submit()">
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

            {{-- ═══ ISO 9241-11 FORMULA BAR ═══ --}}
            <div class="tbut-formula-bar mb-4">
                <div class="tbut-formula-title">
                    <i class="material-icons" style="font-size:16px;color:#4f46e5;">functions</i>
                    Framework Formula TBUT (ISO 9241-11)
                </div>
                <div class="tbut-formula-steps">
                    <div class="tbut-formula-step">
                        <span class="step-num">1</span>
                        <span class="step-body"><strong>T_norm</strong> = t_aktual / t_ideal</span>
                    </div>
                    <i class="material-icons tbut-formula-arrow">arrow_forward</i>
                    <div class="tbut-formula-step">
                        <span class="step-num">2</span>
                        <span class="step-body"><strong>R_norm</strong> = run_aktual / run_ideal</span>
                    </div>
                    <i class="material-icons tbut-formula-arrow">arrow_forward</i>
                    <div class="tbut-formula-step">
                        <span class="step-num">3</span>
                        <span class="step-body"><strong>E</strong> = 0.5×T_norm + 0.5×R_norm</span>
                    </div>
                    <i class="material-icons tbut-formula-arrow">arrow_forward</i>
                    <div class="tbut-formula-step">
                        <span class="step-num">4</span>
                        <span class="step-body"><strong>D</strong> = rata-rata E semua responden</span>
                    </div>
                    <i class="material-icons tbut-formula-arrow">arrow_forward</i>
                    <div class="tbut-formula-step">
                        <span class="step-num">5</span>
                        <span class="step-body"><strong>SR</strong> = (selesai / total) × 100%</span>
                    </div>
                </div>
            </div>

            {{-- ═══ SUMMARY STAT CARDS ═══ --}}
            <div class="row g-3 mb-4">

                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1)">
                            <i class="material-icons">people</i>
                        </div>
                        <p class="tbut-stat-label">Total Sesi</p>
                        <h3 class="tbut-stat-value">{{ $totalSessions }}</h3>
                        <p class="tbut-stat-sub">{{ $completedSess }} selesai</p>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#059669,#047857)">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <p class="tbut-stat-label">Completion Rate</p>
                        <h3 class="tbut-stat-value">{{ $completionRate }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $completionRate }}%;background:#059669"></div></div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
                            <i class="material-icons">verified</i>
                        </div>
                        <p class="tbut-stat-label">Success Rate</p>
                        <h3 class="tbut-stat-value">{{ $successRate }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $successRate }}%;background:#0ea5e9"></div></div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        @php
                            $dClass = $avgDScore !== null
                                ? ($avgDScore < 1.5 ? ['c'=>'#16a34a','l'=>'Mudah']
                                   : ($avgDScore < 2.5 ? ['c'=>'#b45309','l'=>'Sedang']
                                      : ($avgDScore <= 4.0 ? ['c'=>'#dc2626','l'=>'Sulit']
                                         : ['c'=>'#7c3aed','l'=>'Sangat Sulit'])))
                                : null;
                        @endphp
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,{{ $dClass ? $dClass['c'] : '#94a3b8' }},{{ $dClass ? $dClass['c'] : '#94a3b8' }}cc)">
                            <i class="material-icons">analytics</i>
                        </div>
                        <p class="tbut-stat-label">Avg Difficulty Score (D)</p>
                        <h3 class="tbut-stat-value">{{ $avgDScore !== null ? $avgDScore : '—' }}</h3>
                        @if($dClass)
                        <p class="tbut-stat-sub" style="color:{{ $dClass['c'] }};font-weight:700;">{{ $dClass['l'] }}</p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ═══ CHARTS ROW ═══ --}}
            @if($tasks->isNotEmpty())
            <div class="row g-3 mb-4">
                {{-- Completion Rate + Success Rate Bar --}}
                <div class="col-lg-5">
                    <div class="tbut-chart-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#4f46e5;font-size:18px;">bar_chart</i>
                            Completion &amp; Success Rate per Tugas
                        </div>
                        <div class="tbut-chart-body">
                            <canvas id="tbutBarChart" style="max-height:210px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Difficulty Score per Task --}}
                <div class="col-lg-4">
                    <div class="tbut-chart-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#f59e0b;font-size:18px;">speed</i>
                            Difficulty Score (D) per Tugas
                        </div>
                        <div class="tbut-chart-body">
                            <canvas id="tbutDChart" style="max-height:210px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Donut + Classification --}}
                <div class="col-lg-3">
                    <div class="tbut-chart-card h-100">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#059669;font-size:18px;">donut_large</i>
                            Status Sesi
                        </div>
                        <div class="tbut-chart-body d-flex flex-column align-items-center justify-content-center">
                            <div style="max-width:150px;width:100%;">
                                <canvas id="tbutDonutChart"></canvas>
                            </div>
                            <div class="d-flex gap-4 mt-3">
                                <div class="text-center">
                                    <span class="tbut-ldot" style="background:#059669;"></span>
                                    <p class="tbut-ldot-label">Selesai</p>
                                    <p class="tbut-ldot-val" style="color:#059669;">{{ $completedSess }}</p>
                                </div>
                                <div class="text-center">
                                    <span class="tbut-ldot" style="background:#e2e8f0;border:1px solid #cbd5e1;"></span>
                                    <p class="tbut-ldot-label">Belum</p>
                                    <p class="tbut-ldot-val" style="color:#94a3b8;">{{ $totalSessions - $completedSess }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══ CLASSIFICATION GUIDE ═══ --}}
            <div class="tbut-classify-guide mb-4">
                <div class="tbut-classify-guide-title">
                    <i class="material-icons" style="font-size:16px;">info</i>
                    Klasifikasi Gabungan D + Success Rate
                </div>
                <div class="row g-2 mt-2">
                    @foreach([
                        ['label'=>'Mudah',       'D'=>'< 1.5',      'SR'=>'≥ 80%',   'color'=>'#16a34a','bg'=>'#dcfce7','interp'=>'Materi dipahami dengan baik, perlu pengayaan'],
                        ['label'=>'Sedang',      'D'=>'1.5 – 2.5',  'SR'=>'60–79%',  'color'=>'#b45309','bg'=>'#fef9c3','interp'=>'Materi cukup menantang, perlu latihan tambahan'],
                        ['label'=>'Sulit',       'D'=>'2.5 – 4.0',  'SR'=>'40–59%',  'color'=>'#dc2626','bg'=>'#fee2e2','interp'=>'Materi perlu penjelasan ulang / scaffolding'],
                        ['label'=>'Sangat Sulit','D'=>'> 4.0',       'SR'=>'< 40%',   'color'=>'#7c3aed','bg'=>'#ede9fe','interp'=>'Materi terlalu kompleks, perlu redesign konten'],
                    ] as $cat)
                    <div class="col-6 col-lg-3">
                        <div class="tbut-cat-card" style="border-left:4px solid {{ $cat['color'] }};background:{{ $cat['bg'] }};">
                            <div class="tbut-cat-badge" style="background:{{ $cat['color'] }};color:#fff;">{{ $cat['label'] }}</div>
                            <div class="tbut-cat-metrics">
                                <span class="tbut-cat-metric-label">D</span>
                                <span class="tbut-cat-metric-val">{{ $cat['D'] }}</span>
                                <span class="tbut-cat-metric-label" style="margin-left:10px;">SR</span>
                                <span class="tbut-cat-metric-val">{{ $cat['SR'] }}</span>
                            </div>
                            <p class="tbut-cat-interp">{{ $cat['interp'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══ TASKS TABLE ═══ --}}
            <div class="card tbut-main-card">
                <div class="tbut-main-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="material-icons" style="font-size:20px;color:#4f46e5;">assignment</i>
                        <span>Rekap Per Tugas</span>
                    </div>
                    <span class="tbut-badge-count">{{ $tasks->count() }} Tugas</span>
                </div>
                <div class="card-body p-0">
                    @if($tasks->isEmpty())
                    <div class="text-center py-5">
                        <i class="material-icons text-muted" style="font-size:52px;">inbox</i>
                        <p class="text-muted mt-2">Belum ada tugas atau sesi TBUT.</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table tbut-table mb-0">
                            <thead>
                                <tr>
                                    <th>Tugas / Materi</th>
                                    <th class="text-center">Peserta</th>
                                    <th class="text-center" style="min-width:130px;">Completion Rate</th>
                                    <th class="text-center" style="min-width:130px;">Success Rate</th>
                                    <th class="text-center">Avg Waktu</th>
                                    <th class="text-center">Avg Run</th>
                                    <th class="text-center">t_ideal</th>
                                    <th class="text-center">r_ideal</th>
                                    <th class="text-center" style="min-width:80px;">D</th>
                                    <th class="text-center" style="min-width:110px;">Klasifikasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                <tr class="tbut-row">
                                    <td>
                                        <p class="tbut-task-title">{{ $task->title }}</p>
                                        <p class="tbut-task-material">{{ $task->material->title ?? '—' }}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-count-pill">{{ $task->total_attempts }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbut-prog-bar">
                                                <div class="tbut-prog-fill" style="width:{{ $task->completion_rate }}%;background:#059669;"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:700;color:#059669;min-width:36px;">{{ $task->completion_rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbut-prog-bar">
                                                <div class="tbut-prog-fill" style="width:{{ $task->success_rate }}%;background:#0ea5e9;"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:700;color:#0ea5e9;min-width:36px;">{{ $task->success_rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-time">
                                            <i class="material-icons" style="font-size:12px;">schedule</i>
                                            {{ $task->avg_duration ? gmdate('i:s', intval($task->avg_duration)) : '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-run">{{ $task->avg_run_count ? round($task->avg_run_count, 1) : '—' }}x</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-ideal">
                                            {{ $task->t_ideal !== null ? $task->t_ideal . ' mnt' : '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-ideal">
                                            {{ $task->r_ideal !== null ? $task->r_ideal . 'x' : '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($task->difficulty_score !== null)
                                        <span class="tbut-d-val" style="color:{{ $task->d_class['color'] }};background:{{ $task->d_class['bg'] }};">
                                            {{ $task->difficulty_score }}
                                        </span>
                                        @else
                                        <span class="text-muted" style="font-size:12px;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($task->combined_class)
                                        <span class="tbut-classify-badge"
                                              style="color:{{ $task->combined_class['color'] }};background:{{ $task->combined_class['bg'] }};">
                                            {{ $task->combined_class['label'] }}
                                        </span>
                                        @else
                                        <span class="text-muted" style="font-size:12px;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.tbut.show', $task->id) }}" class="tbut-detail-btn">
                                            <i class="material-icons" style="font-size:13px;">open_in_new</i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="11" class="text-center py-5 text-muted">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
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

        // ── Bar chart: Completion + Success Rate ─────────────────────────────
        const barCtx = document.getElementById('tbutBarChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tasks->map(fn($t) => \Illuminate\Support\Str::limit($t->title, 22))->values()) !!},
                    datasets: [
                        {
                            label: 'Completion Rate (%)',
                            data: {!! json_encode($tasks->pluck('completion_rate')->values()) !!},
                            backgroundColor: 'rgba(5,150,105,0.85)',
                            borderRadius: 5,
                        },
                        {
                            label: 'Success Rate (%)',
                            data: {!! json_encode($tasks->pluck('success_rate')->values()) !!},
                            backgroundColor: 'rgba(14,165,233,0.8)',
                            borderRadius: 5,
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    scales: {
                        y: { min: 0, max: 100, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 } } }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 10 }, boxWidth: 12 } },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.raw}%` } }
                    }
                }
            });
        }

        // ── Difficulty Score horizontal bar ──────────────────────────────────
        @php
            $dScoreData   = $tasks->map(fn($t) => $t->difficulty_score ?? 0)->values()->toArray();
            $taskLabels   = $tasks->map(fn($t) => \Illuminate\Support\Str::limit($t->title, 20))->values()->toArray();
            $dColors      = $tasks->map(function($t) {
                if ($t->difficulty_score === null) return 'rgba(148,163,184,0.6)';
                if ($t->difficulty_score < 1.5)   return 'rgba(22,163,74,0.85)';
                if ($t->difficulty_score < 2.5)   return 'rgba(180,83,9,0.85)';
                if ($t->difficulty_score <= 4.0)  return 'rgba(220,38,38,0.85)';
                return 'rgba(124,58,237,0.85)';
            })->values()->toArray();
        @endphp
        const dCtx = document.getElementById('tbutDChart');
        if (dCtx) {
            new Chart(dCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($taskLabels) !!},
                    datasets: [{
                        label: 'Difficulty Score (D)',
                        data: {!! json_encode($dScoreData) !!},
                        backgroundColor: {!! json_encode($dColors) !!},
                        borderRadius: 5,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: true,
                    scales: {
                        x: {
                            min: 0,
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#94a3b8', font: { size: 10 } },
                            afterDraw(chart) {
                                // threshold lines
                                const thresholds = [{v:1.5,l:'Mudah',c:'#16a34a'},{v:2.5,l:'Sedang',c:'#b45309'},{v:4.0,l:'Sulit',c:'#dc2626'}];
                                const ctx2 = chart.ctx;
                                const xAxis = chart.scales.x;
                                thresholds.forEach(t => {
                                    const x = xAxis.getPixelForValue(t.v);
                                    if (!x) return;
                                    ctx2.save();
                                    ctx2.beginPath();
                                    ctx2.moveTo(x, chart.chartArea.top);
                                    ctx2.lineTo(x, chart.chartArea.bottom);
                                    ctx2.strokeStyle = t.c;
                                    ctx2.lineWidth = 1.5;
                                    ctx2.setLineDash([4,3]);
                                    ctx2.stroke();
                                    ctx2.restore();
                                });
                            }
                        },
                        y: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 } } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` D = ${ctx.raw}` } }
                    }
                }
            });
        }

        // ── Donut chart ──────────────────────────────────────────────────────
        const donutCtx = document.getElementById('tbutDonutChart');
        if (donutCtx) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum'],
                    datasets: [{
                        data: [{{ $completedSess }}, {{ $totalSessions - $completedSess }}],
                        backgroundColor: ['#059669', '#e2e8f0'],
                        borderWidth: 0,
                        hoverOffset: 5,
                    }]
                },
                options: {
                    responsive: true, cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.raw} sesi` } }
                    }
                }
            });
        }

        @endif
    });
    </script>
    @endpush

</x-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    .main-content { font-family: 'Inter', sans-serif; }

    /* ── Hero ── */
    .tbut-hero {
        background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
        border-radius: 18px;
        padding: 1.4rem 2rem;
        box-shadow: 0 8px 30px rgba(67,56,202,0.35);
    }
    .tbut-hero-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .tbut-filter-select {
        border-radius: 10px; border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.15); color: #fff;
        font-size: 13px; padding: .4rem .85rem;
        backdrop-filter: blur(6px); min-width: 180px;
    }
    .tbut-filter-select option { color: #334155; background: #fff; }

    /* ── Formula Bar ── */
    .tbut-formula-bar {
        background: #fff;
        border: 1.5px solid #e0e7ff;
        border-radius: 14px;
        padding: 14px 20px;
        box-shadow: 0 2px 10px rgba(79,70,229,.06);
    }
    .tbut-formula-title {
        font-size: 12px; font-weight: 700; color: #4f46e5;
        text-transform: uppercase; letter-spacing: .5px;
        display: flex; align-items: center; gap: 6px;
        margin-bottom: 12px;
    }
    .tbut-formula-steps {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .tbut-formula-step {
        display: flex; align-items: center; gap: 7px;
        background: #f5f3ff; border: 1px solid #ddd6fe;
        border-radius: 8px; padding: 6px 12px;
    }
    .step-num {
        width: 20px; height: 20px; border-radius: 50%;
        background: #4f46e5; color: #fff; font-size: 11px;
        font-weight: 700; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .step-body { font-size: 12.5px; color: #3730a3; }
    .tbut-formula-arrow { font-size: 16px; color: #c7d2fe; }

    /* ── Stat Cards ── */
    .tbut-stat-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        padding: 1.2rem 1.2rem 1rem;
        border: 1px solid rgba(0,0,0,.04);
        transition: transform .2s, box-shadow .2s;
    }
    .tbut-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.1); }
    .tbut-stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .65rem; box-shadow: 0 4px 10px rgba(0,0,0,.15);
    }
    .tbut-stat-icon i { color: #fff; font-size: 21px; }
    .tbut-stat-label { font-size: 10.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 1px; }
    .tbut-stat-value { font-size: 1.85rem; font-weight: 800; color: #1e293b; margin: 0; line-height: 1.1; }
    .tbut-stat-sub   { font-size: 11px; color: #94a3b8; margin: 4px 0 0; }
    .tbut-mini-bar   { height: 5px; background: #f1f5f9; border-radius: 99px; overflow: hidden; margin-top: 8px; }
    .tbut-mini-fill  { height: 100%; border-radius: 99px; transition: width .6s ease; }

    /* ── Chart Cards ── */
    .tbut-chart-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.04);
        overflow: hidden;
    }
    .tbut-chart-header {
        padding: 13px 18px;
        font-size: 13px; font-weight: 700; color: #334155;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 8px;
        background: #fafbff;
    }
    .tbut-chart-body { padding: 14px 16px 12px; }
    .tbut-ldot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-bottom: 3px; }
    .tbut-ldot-label { font-size: 10px; color: #94a3b8; margin: 0; }
    .tbut-ldot-val   { font-size: 13px; font-weight: 700; margin: 0; }

    /* ── Classification Guide ── */
    .tbut-classify-guide {
        background: #fff; border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }
    .tbut-classify-guide-title {
        font-size: 12px; font-weight: 700; color: #475569;
        text-transform: uppercase; letter-spacing: .5px;
        display: flex; align-items: center; gap: 6px;
    }
    .tbut-cat-card {
        border-radius: 10px; padding: 12px 14px;
        border: 1px solid transparent;
    }
    .tbut-cat-badge {
        display: inline-block; font-size: 11px; font-weight: 700;
        padding: 2px 10px; border-radius: 6px; margin-bottom: 7px;
    }
    .tbut-cat-metrics { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; margin-bottom: 6px; }
    .tbut-cat-metric-label { font-size: 11px; font-weight: 700; color: #64748b; }
    .tbut-cat-metric-val   { font-size: 12px; font-weight: 600; color: #334155; }
    .tbut-cat-interp       { font-size: 11.5px; color: #475569; margin: 0; line-height: 1.5; }

    /* ── Main Table ── */
    .tbut-main-card {
        border: none; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        overflow: hidden;
        margin-top: 0 !important;
    }
    .tbut-main-card-header {
        background: linear-gradient(135deg, #4338ca, #3730a3);
        padding: 14px 22px;
        display: flex; align-items: center; justify-content: space-between;
        font-size: 14px; font-weight: 700; color: #fff;
    }
    .tbut-badge-count {
        background: rgba(255,255,255,.2); color: #fff;
        font-size: 12px; font-weight: 700;
        padding: 3px 12px; border-radius: 20px;
    }
    .tbut-table thead th {
        font-size: .63rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #94a3b8;
        border-bottom: 2px solid #f1f5f9;
        padding: 14px 12px; white-space: nowrap;
        background: #fafbff;
    }
    .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8fafc; padding: 11px 12px; }
    .tbut-row { transition: background .15s; }
    .tbut-row:hover { background: #f8faff; }
    .tbut-task-title    { font-size: 13px; font-weight: 600; color: #334155; margin: 0; max-width: 200px; }
    .tbut-task-material { font-size: 11px; color: #94a3b8; margin: 2px 0 0; }
    .tbut-count-pill {
        display: inline-block; background: #f1f5f9; border-radius: 6px;
        font-size: 12px; font-weight: 700; color: #475569; padding: 2px 10px;
    }
    .tbut-prog-bar  { height: 7px; background: #f1f5f9; border-radius: 99px; overflow: hidden; min-width: 55px; flex: 1; }
    .tbut-prog-fill { height: 100%; border-radius: 99px; }
    .tbut-chip-time {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 12px; font-weight: 600; color: #f59e0b;
        background: #fef3c7; border-radius: 7px; padding: 3px 8px;
    }
    .tbut-chip-run {
        display: inline-block; font-size: 12px; font-weight: 700;
        color: #7c3aed; background: #ede9fe; border-radius: 7px; padding: 3px 8px;
    }
    .tbut-chip-ideal {
        display: inline-block; font-size: 11.5px; font-weight: 600;
        color: #0ea5e9; background: #e0f2fe; border-radius: 7px; padding: 3px 8px;
    }
    .tbut-d-val {
        display: inline-block; font-size: 14px; font-weight: 800;
        border-radius: 8px; padding: 3px 10px;
    }
    .tbut-classify-badge {
        display: inline-block; font-size: 11.5px; font-weight: 700;
        border-radius: 7px; padding: 4px 10px;
    }
    .tbut-detail-btn {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; font-weight: 600; color: #fff;
        background: linear-gradient(135deg, #4338ca, #3730a3);
        border: none; border-radius: 8px; padding: .3rem .75rem;
        text-decoration: none; transition: box-shadow .2s, transform .2s;
    }
    .tbut-detail-btn:hover { box-shadow: 0 4px 12px rgba(67,56,202,.4); transform: translateY(-1px); color: #fff; }
</style>
