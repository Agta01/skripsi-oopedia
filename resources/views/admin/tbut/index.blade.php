<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Analisa Virtual Code" />

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
                                Task-Based Usability Testing (TBUT)
                            </h5>
                        </div>
                        <p class="text-white mb-0" style="font-size:12.5px;opacity:.8;padding-left:44px;">
                            Evaluasi berdasarkan ISO 9241-11: Effectiveness, Efficiency, Satisfaction (Saputra, 2025)
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0 flex-wrap">
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <label class="text-white mb-0 fw-semibold" style="font-size:12px;opacity:.8;">Filter</label>
                            <select name="material_id" class="tbut-filter-select" onchange="this.form.submit()">
                                <option value="">Semua Materi</option>
                                @foreach($materials as $mat)
                                    <option value="{{ $mat->id }}" {{ $materialId == $mat->id ? 'selected' : '' }}>
                                        {{ $mat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <a href="{{ route('admin.tbut.export', ['material_id' => $materialId]) }}" class="btn btn-sm btn-light fw-semibold d-flex align-items-center gap-1" style="border-radius:10px;color:#4338ca;">
                            <i class="material-icons" style="font-size:16px;">download</i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            {{-- ═══ SUMMARY STAT CARDS (ISO 9241-11) ═══ --}}
            <div class="row g-3 mb-4">

                {{-- Effectiveness: Avg Success Score --}}
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1)">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <p class="tbut-stat-label">Avg Success Score</p>
                        <h3 class="tbut-stat-value">{{ $avgSuccessScore ?? '—' }}<span style="font-size:14px;color:#94a3b8;font-weight:500;"> / 2</span></h3>
                        @if($avgSuccessScore !== null)
                            @php $globalClass = app(\App\Http\Controllers\Admin\TbutAnalysisController::class); @endphp
                            <p class="tbut-stat-sub" style="font-weight:600;">Effectiveness (ISO 9241-11)</p>
                        @endif
                    </div>
                </div>

                {{-- Effectiveness: Task Success Rate --}}
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#059669,#047857)">
                            <i class="material-icons">trending_up</i>
                        </div>
                        <p class="tbut-stat-label">Task Success Rate</p>
                        <h3 class="tbut-stat-value">{{ $successRate }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $successRate }}%;background:#059669"></div></div>
                    </div>
                </div>

                {{-- Efficiency: Avg Time-on-Task --}}
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
                            <i class="material-icons">timer</i>
                        </div>
                        <p class="tbut-stat-label">Avg Time-on-Task</p>
                        <h3 class="tbut-stat-value" style="font-size:1.5rem;">{{ $avgDuration ? gmdate('H:i:s', $avgDuration) : '—' }}</h3>
                        <p class="tbut-stat-sub">Efficiency (ISO 9241-11)</p>
                    </div>
                </div>

                {{-- Total Sesi & Breakdown --}}
                <div class="col-6 col-xl-3">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                            <i class="material-icons">people</i>
                        </div>
                        <p class="tbut-stat-label">Total Sesi</p>
                        <h3 class="tbut-stat-value">{{ $totalSessions }}</h3>
                        <p class="tbut-stat-sub">
                            <span style="color:#16a34a;">{{ $countScore2 }} tanpa kesulitan</span> ·
                            <span style="color:#b45309;">{{ $countScore1 }} dgn kesulitan</span> ·
                            <span style="color:#dc2626;">{{ $countScore0 }} gagal</span>
                        </p>
                    </div>
                </div>

            </div>

            {{-- ═══ SKALA PENILAIAN GUIDE ═══ --}}
            <div class="tbut-classify-guide mb-4">
                <div class="tbut-classify-guide-title">
                    <i class="material-icons" style="font-size:16px;">info</i>
                    Skala Task Success Score (Saputra, 2025)
                </div>
                <div class="row g-2 mt-2">
                    @foreach([
                        ['skor'=>'2', 'label'=>'Berhasil Tanpa Kesulitan', 'color'=>'#16a34a','bg'=>'#dcfce7','desc'=>'Mahasiswa menyelesaikan tugas dengan output yang tepat'],
                        ['skor'=>'1', 'label'=>'Berhasil Dengan Kesulitan', 'color'=>'#b45309','bg'=>'#fef9c3','desc'=>'Mahasiswa menyelesaikan tugas tapi output belum tepat'],
                        ['skor'=>'0', 'label'=>'Gagal', 'color'=>'#dc2626','bg'=>'#fee2e2','desc'=>'Mahasiswa tidak berhasil menyelesaikan tugas (belum submit)'],
                    ] as $cat)
                    <div class="col-12 col-lg-4">
                        <div class="tbut-cat-card" style="border-left:4px solid {{ $cat['color'] }};background:{{ $cat['bg'] }};">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="tbut-cat-badge" style="background:{{ $cat['color'] }};color:#fff;">Skor {{ $cat['skor'] }}</div>
                                <span style="font-size:13px;font-weight:700;color:{{ $cat['color'] }};">{{ $cat['label'] }}</span>
                            </div>
                            <p class="tbut-cat-interp">{{ $cat['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══ CHARTS ROW ═══ --}}
            @if($tasks->isNotEmpty())
            <div class="row g-3 mb-4">
                {{-- Success Score per Task --}}
                <div class="col-lg-6">
                    <div class="tbut-chart-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#4f46e5;font-size:18px;">bar_chart</i>
                            Avg Task Success Score per Tugas (Effectiveness)
                        </div>
                        <div class="tbut-chart-body">
                            <canvas id="tbutSuccessChart" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Time-on-Task per Task --}}
                <div class="col-lg-6">
                    <div class="tbut-chart-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#0ea5e9;font-size:18px;">schedule</i>
                            Avg Time-on-Task per Tugas (Efficiency)
                        </div>
                        <div class="tbut-chart-body">
                            <canvas id="tbutTimeChart" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══ TASKS TABLE ═══ --}}
            <div class="card tbut-main-card">
                <div class="tbut-main-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="material-icons" style="font-size:20px;color:#fff;">assignment</i>
                        <span>Rekap Per Tugas — ISO 9241-11</span>
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
                                    <th class="text-center" style="min-width:140px;">Success Score (0-2)</th>
                                    <th class="text-center" style="min-width:120px;">Success Rate</th>
                                    <th class="text-center" style="min-width:110px;">Avg Time-on-Task</th>
                                    <th class="text-center">Avg Run</th>
                                    <th class="text-center" style="min-width:120px;">Klasifikasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr class="tbut-row">
                                    <td>
                                        <p class="tbut-task-title">{{ $task->title }}</p>
                                        <p class="tbut-task-material">{{ $task->material->title ?? '—' }}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-count-pill">{{ $task->total_attempts }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($task->avg_success_score !== null)
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="fw-bold" style="font-size:16px;color:{{ $task->success_class['color'] ?? '#334155' }};">
                                                {{ $task->avg_success_score }}
                                            </span>
                                            <small class="text-muted" style="font-size:10px;">
                                                {{ $task->count_score_2 }}✓ · {{ $task->count_score_1 }}△ · {{ $task->count_score_0 }}✗
                                            </small>
                                        </div>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbut-prog-bar">
                                                <div class="tbut-prog-fill" style="width:{{ $task->success_rate }}%;background:#059669;"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:700;color:#059669;min-width:36px;">{{ $task->success_rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-time">
                                            <i class="material-icons" style="font-size:12px;">schedule</i>
                                            {{ $task->avg_duration ? gmdate('H:i:s', $task->avg_duration) : '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-run">{{ $task->avg_run_count ?? '—' }}x</span>
                                    </td>
                                    <td class="text-center">
                                        @if($task->success_class)
                                        <span class="tbut-classify-badge"
                                              style="color:{{ $task->success_class['color'] }};background:{{ $task->success_class['bg'] }};">
                                            {{ $task->success_class['label'] }}
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
                                @endforeach
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

        // ── Success Score Bar Chart ──
        const successCtx = document.getElementById('tbutSuccessChart');
        if (successCtx) {
            new Chart(successCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tasks->map(fn($t) => \Illuminate\Support\Str::limit($t->title, 22))->values()) !!},
                    datasets: [{
                        label: 'Avg Success Score (0-2)',
                        data: {!! json_encode($tasks->pluck('avg_success_score')->map(fn($v) => $v ?? 0)->values()) !!},
                        backgroundColor: {!! json_encode($tasks->map(function($t) {
                            if ($t->avg_success_score === null) return 'rgba(148,163,184,0.6)';
                            if ($t->avg_success_score >= 1.80) return 'rgba(22,163,74,0.85)';
                            if ($t->avg_success_score >= 1.50) return 'rgba(14,165,233,0.85)';
                            if ($t->avg_success_score >= 1.00) return 'rgba(180,83,9,0.85)';
                            return 'rgba(220,38,38,0.85)';
                        })->values()) !!},
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    scales: {
                        y: { min: 0, max: 2, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 0.5, color: '#94a3b8', font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 } } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` Score: ${ctx.raw} / 2` } }
                    }
                }
            });
        }

        // ── Time-on-Task Bar Chart ──
        const timeCtx = document.getElementById('tbutTimeChart');
        if (timeCtx) {
            new Chart(timeCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tasks->map(fn($t) => \Illuminate\Support\Str::limit($t->title, 22))->values()) !!},
                    datasets: [{
                        label: 'Avg Time-on-Task (menit)',
                        data: {!! json_encode($tasks->map(fn($t) => $t->avg_duration ? round($t->avg_duration / 60, 1) : 0)->values()) !!},
                        backgroundColor: 'rgba(14,165,233,0.8)',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    scales: {
                        y: { min: 0, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#94a3b8', font: { size: 10 }, callback: v => v + ' mnt' } },
                        x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 10 } } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.raw} menit` } }
                    }
                }
            });
        }

        @endif
    });
    </script>
    @endpush

</x-layout>

@push('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    .main-content { font-family: 'Inter', sans-serif; }

    .tbut-hero { background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%); border-radius: 18px; padding: 1.4rem 2rem; box-shadow: 0 8px 30px rgba(67,56,202,0.35); }
    .tbut-hero-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .tbut-filter-select { border-radius: 10px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.15); color: #fff; font-size: 13px; padding: .4rem .85rem; min-width: 180px; }
    .tbut-filter-select option { color: #334155; background: #fff; }

    .tbut-stat-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.06); padding: 1.2rem; border: 1px solid rgba(0,0,0,.04); transition: transform .2s, box-shadow .2s; }
    .tbut-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.1); }
    .tbut-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: .65rem; box-shadow: 0 4px 10px rgba(0,0,0,.15); }
    .tbut-stat-icon i { color: #fff; font-size: 21px; }
    .tbut-stat-label { font-size: 10.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 1px; }
    .tbut-stat-value { font-size: 1.85rem; font-weight: 800; color: #1e293b; margin: 0; line-height: 1.1; }
    .tbut-stat-sub { font-size: 11px; color: #94a3b8; margin: 4px 0 0; }
    .tbut-mini-bar { height: 5px; background: #f1f5f9; border-radius: 99px; overflow: hidden; margin-top: 8px; }
    .tbut-mini-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }

    .tbut-chart-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.04); overflow: hidden; }
    .tbut-chart-header { padding: 13px 18px; font-size: 13px; font-weight: 700; color: #334155; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; background: #fafbff; }
    .tbut-chart-body { padding: 14px 16px 12px; }

    .tbut-classify-guide { background: #fff; border-radius: 16px; border: 1.5px solid #e2e8f0; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
    .tbut-classify-guide-title { font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .5px; display: flex; align-items: center; gap: 6px; }
    .tbut-cat-card { border-radius: 10px; padding: 12px 14px; border: 1px solid transparent; }
    .tbut-cat-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 6px; }
    .tbut-cat-interp { font-size: 11.5px; color: #475569; margin: 0; line-height: 1.5; }

    .tbut-main-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.06); overflow: hidden; }
    .tbut-main-card-header { background: linear-gradient(135deg, #4338ca, #3730a3); padding: 14px 22px; display: flex; align-items: center; justify-content: space-between; font-size: 14px; font-weight: 700; color: #fff; }
    .tbut-badge-count { background: rgba(255,255,255,.2); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; }

    .tbut-table thead th { text-transform: uppercase; font-size: .63rem; font-weight: 700; letter-spacing: .5px; color: #8392ab; border-bottom: 2px solid #f0f2f5; padding: 1rem .75rem; white-space: nowrap; }
    .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; padding: .85rem .75rem; }
    .tbut-row { transition: background .15s ease; }
    .tbut-row:hover { background: #f8faff; }
    .tbut-task-title { font-size: 13px; font-weight: 700; color: #1e293b; margin: 0; }
    .tbut-task-material { font-size: 11px; color: #94a3b8; margin: 2px 0 0; }
    .tbut-count-pill { background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 8px; }
    .tbut-prog-bar { flex: 1; height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden; min-width: 60px; }
    .tbut-prog-fill { height: 100%; border-radius: 99px; }
    .tbut-chip-time { display: inline-flex; align-items: center; gap: 3px; font-size: 12px; font-weight: 600; color: #0ea5e9; background: rgba(14,165,233,.1); border-radius: 8px; padding: .25rem .6rem; }
    .tbut-chip-run { display: inline-block; font-size: 12px; font-weight: 700; color: #8b5cf6; background: rgba(139,92,246,.1); border-radius: 8px; padding: .25rem .6rem; }
    .tbut-classify-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 8px; }
    .tbut-detail-btn { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #4f46e5; background: rgba(79,70,229,.08); border: 1px solid rgba(79,70,229,.2); border-radius: 8px; padding: .3rem .75rem; text-decoration: none; transition: all .2s; }
    .tbut-detail-btn:hover { background: #4f46e5; color: #fff; }
</style>
@endpush
