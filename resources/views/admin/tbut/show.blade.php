<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth :titlePage="'TBUT — ' . $task->title" />

        <div class="container-fluid py-4">

            {{-- ===== HERO BANNER ===== --}}
            <div class="tbut-hero animate-fade-in-down mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="position-relative" style="z-index:2;">
                        <nav aria-label="breadcrumb" class="mb-1">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.tbut.index') }}"
                                        class="text-white opacity-8 text-sm text-decoration-none d-flex align-items-center">
                                        <i class="material-icons align-middle me-1" style="font-size:16px">arrow_back</i>
                                        Analisis TBUT
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-white opacity-6 text-sm" aria-current="page">
                                    {{ \Illuminate\Support\Str::limit($task->title, 40) }}
                                </li>
                            </ol>
                        </nav>
                        <h5 class="text-white fw-bold mb-1 mt-2" style="font-size:1.3rem; letter-spacing: 0.5px;">
                            <i class="material-icons align-middle me-2" style="font-size:24px">assignment</i>
                            {{ $task->title }}
                        </h5>
                        <p class="text-white opacity-8 mb-0 text-sm" style="padding-left:36px;">
                            Materi: <strong>{{ $task->material->title ?? '—' }}</strong>
                            &nbsp;·&nbsp;
                            @php $diffColors = ['beginner' => '#2dce89', 'intermediate' => '#fb6340', 'advanced' => '#f5365c']; @endphp
                            <span style="color:{{ $diffColors[$task->difficulty] ?? '#fff' }}; font-weight:600;">{{ ucfirst($task->difficulty) }}</span>
                        </p>
                    </div>
                    <a href="{{ route('admin.tbut.index') }}"
                        class="btn btn-sm bg-white fw-bold flex-shrink-0 d-flex align-items-center gap-1 position-relative"
                        style="border-radius:12px;color:#0057B8;padding:0.6rem 1.2rem;z-index:2;">
                        <i class="material-icons text-sm">arrow_back</i> Kembali
                    </a>
                </div>
            </div>

            {{-- ===== STAT CARDS (ISO 9241-11) ===== --}}
            <div class="row g-3 mb-4 animate-fade-in-up">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#0057B8,#003b7d)">
                            <i class="material-icons">people</i>
                        </div>
                        <p class="tbut-stat-label">Total Peserta</p>
                        <h3 class="tbut-stat-value">{{ $stats['total'] }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1)">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <p class="tbut-stat-label">Avg Success Score</p>
                        <h3 class="tbut-stat-value" style="color:{{ $stats['success_class']['color'] ?? '#334155' }}">
                            {{ $stats['avg_success_score'] ?? '—' }}<span style="font-size:12px;color:#94a3b8;"> / 2</span>
                        </h3>
                        @if($stats['success_class'])
                        <p class="tbut-stat-sub" style="color:{{ $stats['success_class']['color'] }};font-weight:600;">{{ $stats['success_class']['label'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#059669,#047857)">
                            <i class="material-icons">trending_up</i>
                        </div>
                        <p class="tbut-stat-label">Task Success Rate</p>
                        <h3 class="tbut-stat-value" style="color:#059669">{{ $stats['success_rate'] }}%</h3>
                        <div class="tbut-mini-bar">
                            <div class="tbut-mini-fill" style="width:{{ $stats['success_rate'] }}%;background:#059669"></div>
                        </div>
                        <p class="tbut-stat-sub" style="font-weight:600;">
                            <span style="color:#16a34a">{{ $stats['count_score_2'] }}✓</span> · 
                            <span style="color:#b45309">{{ $stats['count_score_1'] }}△</span> · 
                            <span style="color:#dc2626">{{ $stats['count_score_0'] }}✗</span>
                        </p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
                            <i class="material-icons">timer</i>
                        </div>
                        <p class="tbut-stat-label">Avg Time-on-Task</p>
                        <h3 class="tbut-stat-value" style="font-size:1.4rem">
                            {{ $stats['avg_duration'] ? gmdate('H:i:s', intval($stats['avg_duration'])) : '--:--:--' }}
                        </h3>
                        <p class="tbut-stat-sub">Efficiency (ISO 9241-11)</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                            <i class="material-icons">play_circle</i>
                        </div>
                        <p class="tbut-stat-label">Avg Run Code</p>
                        <h3 class="tbut-stat-value">
                            {{ $stats['avg_run_count'] ? $stats['avg_run_count'] : '—' }}x
                        </h3>
                        <p class="tbut-stat-sub">Metrik tambahan</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                            <i class="material-icons">download</i>
                        </div>
                        <p class="tbut-stat-label">Export Data</p>
                        <a href="{{ route('admin.tbut.export.task', $task->id) }}" class="btn btn-sm btn-warning fw-bold mt-2" style="border-radius:10px;font-size:12px;display:flex;align-items:center;justify-content:center;gap:4px;">
                            <i class="material-icons" style="font-size:16px;">table_chart</i> Excel
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== SESSION TABLE ===== --}}
            <div class="row animate-fade-in-up delay-2">
                <div class="col-12">
                    <div class="card modern-card mt-5">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="modern-header px-4 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3"
                                        style="width:42px;height:42px;border-radius:12px">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">people</i>
                                    </div>
                                    <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px;font-size:1.05rem;">Detail Per Mahasiswa</h6>
                                </div>
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 me-2"
                                    style="border-radius:20px;font-size:12px;box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                                    {{ $sessions->count() }} Peserta
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2 mt-2">
                            @if($sessions->isEmpty())
                                <div class="text-center py-5">
                                    <i class="material-icons text-muted" style="font-size:52px">inbox</i>
                                    <p class="text-muted mt-2 mb-0">Belum ada mahasiswa yang mengerjakan tugas ini.</p>
                                </div>
                            @else
                                <div class="table-responsive px-3">
                                    <table class="table align-items-center mb-0 tbut-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mahasiswa</th>
                                                <th class="text-center">Success Score</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Waktu Mulai</th>
                                                <th class="text-center">Waktu Submit</th>
                                                <th class="text-center">Time-on-Task</th>
                                                <th class="text-center">Output</th>
                                                <th class="text-center">Run Code</th>
                                                <th class="text-center">Kode Final</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sessions as $i => $sess)
                                                <tr class="tbut-row">
                                                    <td><span class="text-xs text-muted fw-bold">{{ $i + 1 }}</span></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="tbut-avatar">
                                                                {{ strtoupper(substr($sess->user->name ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-sm fw-bold" style="color:#344767">
                                                                    {{ $sess->user->name ?? '—' }}
                                                                </p>
                                                                <p class="mb-0 text-xs text-muted">
                                                                    {{ $sess->user->email ?? '' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $score = !$sess->is_completed ? 0 : ($sess->is_success ? 2 : 1);
                                                            $scoreColors = [0 => '#dc2626', 1 => '#b45309', 2 => '#16a34a'];
                                                            $scoreBgs = [0 => '#fee2e2', 1 => '#fef9c3', 2 => '#dcfce7'];
                                                            $scoreLabels = [0 => 'Gagal', 1 => 'Dgn Kesulitan', 2 => 'Tanpa Kesulitan'];
                                                        @endphp
                                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:700;padding:4px 12px;border-radius:8px;color:{{ $scoreColors[$score] }};background:{{ $scoreBgs[$score] }};">
                                                            {{ $score }} — {{ $scoreLabels[$score] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($sess->is_completed)
                                                            <span class="tbut-status-badge completed">
                                                                <i class="material-icons" style="font-size:14px">check</i> Selesai
                                                            </span>
                                                        @else
                                                            <span class="tbut-status-badge pending">
                                                                <i class="material-icons" style="font-size:14px">hourglass_empty</i> Proses
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-xs text-muted d-block">{{ $sess->started_at ? $sess->started_at->timezone('Asia/Jakarta')->format('d M Y') : '—' }}</span>
                                                        @if($sess->started_at)
                                                            <span class="tbut-time-label mt-1">{{ $sess->started_at->timezone('Asia/Jakarta')->format('H:i:s') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-xs text-muted d-block">{{ $sess->submitted_at ? $sess->submitted_at->timezone('Asia/Jakarta')->format('d M Y') : '—' }}</span>
                                                        @if($sess->submitted_at)
                                                            <span class="tbut-time-label mt-1">{{ $sess->submitted_at->timezone('Asia/Jakarta')->format('H:i:s') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="tbut-time-chip">
                                                            <i class="material-icons" style="font-size:14px">schedule</i>
                                                            {{ $sess->duration_seconds > 0 ? gmdate('H:i:s', $sess->duration_seconds) : '—' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($sess->is_success)
                                                            <span class="tbut-output-badge correct">
                                                                <i class="material-icons" style="font-size:14px">done_all</i> Benar
                                                            </span>
                                                        @else
                                                            <span class="tbut-output-badge wrong">
                                                                <i class="material-icons" style="font-size:14px">close</i> Belum Tepat
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="tbut-run-chip">{{ $sess->run_count }}x</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($sess->final_code)
                                                            <button class="tbut-code-btn" data-bs-toggle="modal"
                                                                data-bs-target="#codeModal{{ $sess->id }}">
                                                                <i class="material-icons" style="font-size:16px">code</i> Lihat
                                                            </button>

                                                            {{-- Code Modal --}}
                                                            <div class="modal fade" id="codeModal{{ $sess->id }}" tabindex="-1">
                                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                    <div class="modal-content"
                                                                        style="border-radius:20px;overflow:hidden;border:none;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
                                                                        <div class="modal-header"
                                                                            style="background:linear-gradient(135deg,#0057B8,#003b7d);border:none;padding:1.25rem 1.5rem;">
                                                                            <h6 class="modal-title text-white fw-semibold d-flex align-items-center">
                                                                                <i class="material-icons align-middle me-2"
                                                                                    style="font-size:20px">code</i>
                                                                                Kode Final — {{ $sess->user->name ?? 'Mahasiswa' }}
                                                                            </h6>
                                                                            <button type="button" class="btn-close btn-close-white"
                                                                                data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body p-0">
                                                                            <pre class="m-0 p-4"
                                                                                style="background:#1e1e1e;color:#d4d4d4;font-size:0.9rem;max-height:450px;overflow-y:auto;tab-size:4;font-family:'JetBrains Mono','Fira Code',monospace">{{ htmlspecialchars($sess->final_code) }}</pre>
                                                                        </div>
                                                                        <div class="modal-footer"
                                                                            style="background:#f8f9fa;border-top:1px solid #eee;padding:1rem 1.5rem;">
                                                                            <button type="button" class="btn btn-sm btn-secondary fw-bold mb-0"
                                                                                data-bs-dismiss="modal"
                                                                                style="border-radius:10px;padding:0.5rem 1.25rem;">Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted text-xs fw-bold">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center py-5 text-muted">
                                                        <i class="material-icons d-block mb-2" style="font-size:40px">inbox</i>
                                                        Belum ada mahasiswa yang mengerjakan tugas ini.
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

        /* Modern Card */
        .modern-card { border: none; box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.05); border-radius: 20px; background: #fff; overflow: visible; margin-top: 2.5rem !important; }
        .modern-header { background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%); box-shadow: 0 8px 25px -8px rgba(0, 87, 184, 0.45); border-radius: 16px; transform: translateY(-20px); }

        /* Table */
        .tbut-table thead th { font-family: 'Inter', sans-serif; text-transform: uppercase; font-size: .7rem; font-weight: 700; letter-spacing: .5px; color: #8392ab; border-bottom: 2px solid #f0f2f5; padding: 1.2rem 1rem; white-space: nowrap; }
        .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; padding: 1rem; transition: background 0.2s; }
        .tbut-row:hover td { background: #f8faff; }

        /* Avatar */
        .tbut-avatar { width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, #0057B8, #003b7d); color: #fff; font-weight: 700; font-size: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,87,184,0.2); }

        /* Badges */
        .tbut-status-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 700; padding: .4rem .8rem; border-radius: 8px; }
        .tbut-status-badge.completed { background: rgba(45, 206, 137, .12); color: #1a9e63; }
        .tbut-status-badge.pending { background: rgba(251, 99, 64, .12); color: #d94a28; }

        .tbut-output-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 700; padding: .4rem .8rem; border-radius: 8px; }
        .tbut-output-badge.correct { background: rgba(17, 205, 239, .12); color: #0a9ab8; }
        .tbut-output-badge.wrong { background: rgba(245, 54, 92, .12); color: #b41a3a; }

        .tbut-time-chip { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #fb6340; background: rgba(251, 99, 64, .1); border-radius: 8px; padding: .4rem .8rem; }
        .tbut-time-label { display: inline-block; font-size: 11.5px; color: #0057B8; font-weight: 600; background: rgba(0,87,184,0.05); padding: 2px 8px; border-radius: 6px; }

        .tbut-run-chip { display: inline-block; font-size: 12px; font-weight: 700; color: #8655fc; background: rgba(134, 85, 252, .1); border-radius: 8px; padding: .4rem .8rem; }

        .tbut-code-btn { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: #0057B8; background: rgba(0, 87, 184, .08); border: 1px solid rgba(0, 87, 184, .2); border-radius: 8px; padding: .45rem .85rem; cursor: pointer; transition: all .2s ease; }
        .tbut-code-btn:hover { background: #0057B8; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,87,184,0.2); }
    </style>
    @endpush
</x-layout>