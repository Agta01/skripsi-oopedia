<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Analisis TBUT" />

        <div class="container-fluid py-4">

            {{-- ═══ HERO BANNER ═══ --}}
            <div class="tbut-hero animate-fade-in-down mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="position-relative" style="z-index:2;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="tbut-hero-icon">
                                <i class="material-icons" style="font-size:22px;color:#fff;">assignment_turned_in</i>
                            </div>
                            <h4 class="text-white fw-bold mb-0" style="letter-spacing: 0.5px;">
                                Task-Based Usability Testing (TBUT)
                            </h4>
                        </div>
                        <p class="text-white mb-0" style="font-size:13px;opacity:.85;padding-left:56px;">
                            Evaluasi berdasarkan ISO 9241-11: Effectiveness, Efficiency, Satisfaction (Saputra, 2025)
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-shrink-0 flex-wrap position-relative" style="z-index:2;">
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <label class="text-white mb-0 fw-semibold" style="font-size:13px;opacity:.9;">Filter:</label>
                            <select name="material_id" class="tbut-filter-select" onchange="this.form.submit()">
                                <option value="">Semua Materi</option>
                                @foreach($materials as $mat)
                                    <option value="{{ $mat->id }}" {{ $materialId == $mat->id ? 'selected' : '' }}>
                                        {{ $mat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <a href="{{ route('admin.tbut.export', ['material_id' => $materialId]) }}" class="btn btn-sm bg-white fw-bold d-flex align-items-center gap-2 mb-0" style="border-radius:10px;color:#0057B8;padding:0.5rem 1rem;">
                            <i class="material-icons" style="font-size:18px;">download</i> Export Excel
                        </a>
                    </div>
                </div>
            </div>

            {{-- ═══ SUMMARY STAT CARDS (ISO 9241-11) ═══ --}}
            <div class="row g-3 mb-4 animate-fade-in-up">

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
                            <span style="color:#16a34a;font-weight:600;">{{ $countScore2 }} tanpa kesulitan</span> ·
                            <span style="color:#b45309;font-weight:600;">{{ $countScore1 }} dgn kesulitan</span> ·
                            <span style="color:#dc2626;font-weight:600;">{{ $countScore0 }} gagal</span>
                        </p>
                    </div>
                </div>

            </div>

            {{-- ═══ SKALA PENILAIAN GUIDE ═══ --}}
            <div class="tbut-classify-guide mb-4 animate-fade-in-up delay-1">
                <div class="tbut-classify-guide-title mb-3">
                    <i class="material-icons" style="font-size:18px;color:#0057B8;">info</i>
                    Skala Task Success Score (Saputra, 2025)
                </div>
                <div class="row g-3">
                    @foreach([
                        ['skor'=>'2', 'label'=>'Berhasil Tanpa Kesulitan', 'color'=>'#16a34a','bg'=>'#dcfce7','desc'=>'Mahasiswa menyelesaikan tugas dengan output yang tepat'],
                        ['skor'=>'1', 'label'=>'Berhasil Dengan Kesulitan', 'color'=>'#b45309','bg'=>'#fef9c3','desc'=>'Mahasiswa menyelesaikan tugas tapi output belum tepat'],
                        ['skor'=>'0', 'label'=>'Gagal', 'color'=>'#dc2626','bg'=>'#fee2e2','desc'=>'Mahasiswa tidak berhasil menyelesaikan tugas (belum submit)'],
                    ] as $cat)
                    <div class="col-12 col-lg-4">
                        <div class="tbut-cat-card" style="border-left:4px solid {{ $cat['color'] }};background:{{ $cat['bg'] }};">
                            <div class="d-flex align-items-center gap-2 mb-2">
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
            <div class="row g-3 mb-4 animate-fade-in-up delay-1">
                {{-- Success Score per Task --}}
                <div class="col-lg-6">
                    <div class="modern-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#4f46e5;font-size:20px;">bar_chart</i>
                            Avg Task Success Score per Tugas (Effectiveness)
                        </div>
                        <div class="tbut-chart-body mt-2">
                            <canvas id="tbutSuccessChart" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Time-on-Task per Task --}}
                <div class="col-lg-6">
                    <div class="modern-card">
                        <div class="tbut-chart-header">
                            <i class="material-icons" style="color:#0ea5e9;font-size:20px;">schedule</i>
                            Avg Time-on-Task per Tugas (Efficiency)
                        </div>
                        <div class="tbut-chart-body mt-2">
                            <canvas id="tbutTimeChart" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══ TASKS TABLE ═══ --}}
            <div class="card tbut-main-card animate-fade-in-up delay-2">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="tbut-main-header">
                        <h6>
                            <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center" style="width:38px;height:38px;border-radius:10px;margin-right:12px;">
                                <i class="material-icons" style="font-size:20px;color:#0057B8;">assignment</i>
                            </div>
                            Rekap Per Tugas — ISO 9241-11
                        </h6>
                        <span class="tbut-badge-count">{{ $tasks->count() }} Tugas</span>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 mt-2">
                    @if($tasks->isEmpty())
                    <div class="text-center py-5">
                        <i class="material-icons text-muted" style="font-size:52px;">inbox</i>
                        <p class="text-muted mt-2">Belum ada tugas atau sesi TBUT.</p>
                    </div>
                    @else
                    <div class="table-responsive px-3">
                        <table class="table tbut-table align-items-center mb-0">
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
                                            <span class="fw-bold" style="font-size:16px;color:{{ $task->success_class['color'] ?? '#344767' }};">
                                                {{ $task->avg_success_score }}
                                            </span>
                                            <small class="text-muted" style="font-size:10px;font-weight:600;">
                                                {{ $task->count_score_2 }}✓ · {{ $task->count_score_1 }}△ · {{ $task->count_score_0 }}✗
                                            </small>
                                        </div>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="tbut-prog-bar">
                                                <div class="tbut-prog-fill" style="width:{{ $task->success_rate }}%;background:#059669;"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:700;color:#059669;min-width:36px;">{{ $task->success_rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="tbut-chip-time">
                                            <i class="material-icons" style="font-size:14px;">schedule</i>
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
                                            <i class="material-icons" style="font-size:14px;">open_in_new</i> Detail
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

    @push('head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        .main-content { font-family: 'Inter', sans-serif; }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-in-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }

        /* Hero Banner */
        .tbut-hero { 
            background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%); 
            border-radius: 20px; 
            padding: 1.5rem 2.5rem; 
            box-shadow: 0 10px 30px rgba(0, 87, 184, 0.25); 
            position: relative; 
            overflow: hidden;
        }
        .tbut-hero::before {
            content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;
        }
        .tbut-hero::after {
            content: ''; position: absolute; bottom: -30px; left: 20%; width: 100px; height: 100px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;
        }
        .tbut-hero-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; backdrop-filter: blur(5px); }
        .tbut-filter-select { border-radius: 10px; border: 1px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.1); color: #fff; font-size: 13px; padding: .45rem 1rem; min-width: 200px; backdrop-filter: blur(10px); transition: all 0.3s; }
        .tbut-filter-select:focus { outline: none; background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.5); }
        .tbut-filter-select option { color: #334155; background: #fff; }

        /* Stat Cards */
        .tbut-stat-card { background: #fff; border-radius: 18px; box-shadow: 0 10px 30px 0 rgba(0,0,0,.05); padding: 1.5rem; border: none; transition: all .3s cubic-bezier(0.16, 1, 0.3, 1); }
        .tbut-stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px 0 rgba(0,0,0,.08); }
        .tbut-stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 6px 15px rgba(0,0,0,.1); transition: transform 0.3s; }
        .tbut-stat-card:hover .tbut-stat-icon { transform: scale(1.05); }
        .tbut-stat-icon i { color: #fff; font-size: 24px; }
        .tbut-stat-label { font-size: 11px; font-weight: 700; color: #8392ab; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
        .tbut-stat-value { font-size: 1.75rem; font-weight: 800; color: #344767; margin: 0; line-height: 1.2; }
        .tbut-stat-sub { font-size: 12px; color: #94a3b8; margin: 6px 0 0; }
        
        .tbut-mini-bar { height: 6px; background: #f0f2f5; border-radius: 99px; overflow: hidden; margin-top: 10px; }
        .tbut-mini-fill { height: 100%; border-radius: 99px; transition: width 1s cubic-bezier(0.16, 1, 0.3, 1); }

        /* Guide & Chart Cards */
        .modern-card { background: #fff; border-radius: 18px; box-shadow: 0 10px 30px 0 rgba(0,0,0,.05); border: none; padding: 1.25rem; transition: all 0.3s; }
        .modern-card:hover { box-shadow: 0 15px 35px 0 rgba(0,0,0,.08); }
        
        .tbut-classify-guide { background: #fff; border-radius: 18px; border: none; padding: 20px 24px; box-shadow: 0 10px 30px 0 rgba(0,0,0,.05); }
        .tbut-classify-guide-title { font-size: 14px; font-weight: 700; color: #344767; text-transform: uppercase; letter-spacing: .5px; display: flex; align-items: center; gap: 8px; }
        .tbut-cat-card { border-radius: 14px; padding: 16px 18px; border: 1px solid rgba(0,0,0,0.02); transition: transform 0.2s; }
        .tbut-cat-card:hover { transform: translateY(-2px); }
        .tbut-cat-badge { display: inline-block; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 8px; }
        .tbut-cat-interp { font-size: 12.5px; color: #475569; margin: 8px 0 0; line-height: 1.5; }

        /* Charts */
        .tbut-chart-header { padding-bottom: 12px; font-size: 14px; font-weight: 700; color: #344767; border-bottom: 1px solid #f0f2f5; display: flex; align-items: center; gap: 8px; }
        
        /* Main Table Card */
        .tbut-main-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px 0 rgba(0,0,0,.05); overflow: visible; background: #fff; margin-top: 2.5rem !important; }
        .tbut-main-header { background: linear-gradient(135deg, #0057B8, #003b7d); box-shadow: 0 8px 25px -8px rgba(0, 87, 184, 0.45); border-radius: 16px; transform: translateY(-20px); padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .tbut-main-header h6 { color: #fff; margin: 0; font-weight: 600; font-size: 1.05rem; display: flex; align-items: center; }
        .tbut-badge-count { background: #fff; color: #0057B8; font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

        /* Table Styles */
        .tbut-table thead th { text-transform: uppercase; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; color: #8392ab; border-bottom: 2px solid #f0f2f5; padding: 1.2rem 1rem; white-space: nowrap; }
        .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; padding: 1rem; transition: background 0.2s; }
        .tbut-row:hover td { background: #f8faff; }
        .tbut-task-title { font-size: 14px; font-weight: 600; color: #344767; margin: 0; }
        .tbut-task-material { font-size: 12px; color: #8392ab; margin: 4px 0 0; }
        
        .tbut-count-pill { background: rgba(0,87,184,0.08); color: #0057B8; font-size: 13px; font-weight: 700; padding: 5px 12px; border-radius: 10px; display: inline-block; }
        .tbut-prog-bar { flex: 1; height: 6px; background: #f0f2f5; border-radius: 99px; overflow: hidden; min-width: 60px; }
        .tbut-prog-fill { height: 100%; border-radius: 99px; }
        
        .tbut-chip-time { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #fb6340; background: rgba(251,99,64,0.1); border-radius: 8px; padding: 0.4rem 0.8rem; }
        .tbut-chip-run { display: inline-block; font-size: 12px; font-weight: 700; color: #8655fc; background: rgba(134,85,252,0.1); border-radius: 8px; padding: 0.4rem 0.8rem; }
        .tbut-classify-badge { display: inline-block; font-size: 11.5px; font-weight: 700; padding: 5px 12px; border-radius: 8px; }
        
        .tbut-detail-btn { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: #0057B8; background: rgba(0,87,184,.08); border: 1px solid rgba(0,87,184,.2); border-radius: 8px; padding: 0.45rem 0.85rem; text-decoration: none; transition: all .2s; }
        .tbut-detail-btn:hover { background: #0057B8; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,87,184,0.2); }
    </style>
    @endpush
</x-layout>

