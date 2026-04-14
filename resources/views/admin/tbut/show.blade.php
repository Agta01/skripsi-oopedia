<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth :titlePage="'TBUT — ' . $task->title" />

        <div class="container-fluid py-4">

            {{-- ===== HERO BANNER ===== --}}
            <div class="tbut-hero animate-fade-in-down mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <nav aria-label="breadcrumb" class="mb-1">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.tbut.index') }}" class="text-white opacity-8 text-sm text-decoration-none">
                                        <i class="material-icons align-middle" style="font-size:14px">arrow_back</i> Analisis TBUT
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-white opacity-6 text-sm" aria-current="page">{{ \Illuminate\Support\Str::limit($task->title, 40) }}</li>
                            </ol>
                        </nav>
                        <h5 class="text-white fw-bold mb-1" style="font-size:1.2rem">
                            <i class="material-icons align-middle me-2" style="font-size:20px">assignment</i>
                            {{ $task->title }}
                        </h5>
                        <p class="text-white opacity-8 mb-0 text-sm">
                            Materi: <strong>{{ $task->material->title ?? '—' }}</strong>
                            &nbsp;·&nbsp;
                            @php $diffColors = ['beginner'=>'#2dce89','intermediate'=>'#fb6340','advanced'=>'#f5365c']; @endphp
                            <span style="color:{{ $diffColors[$task->difficulty] ?? '#fff' }}">{{ ucfirst($task->difficulty) }}</span>
                        </p>
                    </div>
                    <a href="{{ route('admin.tbut.index') }}" class="btn btn-sm btn-light fw-semibold flex-shrink-0 d-flex align-items-center gap-1" style="border-radius:10px;color:#0057B8">
                        <i class="material-icons text-sm">arrow_back</i> Kembali
                    </a>
                </div>
            </div>

            {{-- ===== STAT CARDS ===== --}}
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
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#2dce89,#1a9e63)">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <p class="tbut-stat-label">Selesai</p>
                        <h3 class="tbut-stat-value">{{ $stats['completed'] }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#11cdef,#0a9ab8)">
                            <i class="material-icons">trending_up</i>
                        </div>
                        <p class="tbut-stat-label">Completion Rate</p>
                        <h3 class="tbut-stat-value" style="color:#11cdef">{{ $stats['completion_rate'] }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $stats['completion_rate'] }}%;background:#11cdef"></div></div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#f5365c,#b41a3a)">
                            <i class="material-icons">verified</i>
                        </div>
                        <p class="tbut-stat-label">Success Rate</p>
                        <h3 class="tbut-stat-value" style="color:#f5365c">{{ $stats['success_rate'] }}%</h3>
                        <div class="tbut-mini-bar"><div class="tbut-mini-fill" style="width:{{ $stats['success_rate'] }}%;background:#f5365c"></div></div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#fb6340,#d94a28)">
                            <i class="material-icons">timer</i>
                        </div>
                        <p class="tbut-stat-label">Avg Durasi</p>
                        <h3 class="tbut-stat-value" style="font-size:1.4rem">{{ $stats['avg_duration'] ? gmdate('i:s', intval($stats['avg_duration'])) : '--:--' }}</h3>
                        <p class="tbut-stat-sub">menit : detik</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="tbut-stat-card">
                        <div class="tbut-stat-icon" style="background:linear-gradient(135deg,#8655fc,#5e2fc7)">
                            <i class="material-icons">play_circle</i>
                        </div>
                        <p class="tbut-stat-label">Avg Run Code</p>
                        <h3 class="tbut-stat-value">{{ $stats['avg_run_count'] ? round($stats['avg_run_count'], 1) : '—' }}x</h3>
                    </div>
                </div>
            </div>

            {{-- ===== SESSION TABLE ===== --}}
            <div class="row animate-fade-in-up delay-2">
                <div class="col-12">
                    <div class="card modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">people</i>
                                    </div>
                                    <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Detail Per Mahasiswa</h6>
                                </div>
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 me-2" style="border-radius:20px;font-size:12px">
                                    {{ $sessions->count() }} Peserta
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
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
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Waktu Mulai</th>
                                            <th class="text-center">Waktu Submit</th>
                                            <th class="text-center">Durasi</th>
                                            <th class="text-center">Output</th>
                                            <th class="text-center">Run Code</th>
                                            <th class="text-center">Kode Final</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sessions as $i => $sess)
                                        <tr class="tbut-row">
                                            <td><span class="text-xs text-muted">{{ $i + 1 }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="tbut-avatar">
                                                        {{ strtoupper(substr($sess->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-sm fw-semibold" style="color:#344767">{{ $sess->user->name ?? '—' }}</p>
                                                        <p class="mb-0 text-xs text-muted">{{ $sess->user->email ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($sess->is_completed)
                                                    <span class="tbut-status-badge completed">
                                                        <i class="material-icons" style="font-size:12px">check</i> Selesai
                                                    </span>
                                                @else
                                                    <span class="tbut-status-badge pending">
                                                        <i class="material-icons" style="font-size:12px">hourglass_empty</i> Proses
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-xs text-muted">{{ $sess->started_at ? $sess->started_at->format('d M Y') : '—' }}</span>
                                                @if($sess->started_at)
                                                <span class="tbut-time-label">{{ $sess->started_at->format('H:i') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-xs text-muted">{{ $sess->submitted_at ? $sess->submitted_at->format('d M Y') : '—' }}</span>
                                                @if($sess->submitted_at)
                                                <span class="tbut-time-label">{{ $sess->submitted_at->format('H:i') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="tbut-time-chip">
                                                    <i class="material-icons" style="font-size:13px">schedule</i>
                                                    {{ $sess->duration_seconds > 0 ? gmdate('i:s', $sess->duration_seconds) : '—' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($sess->is_success)
                                                    <span class="tbut-output-badge correct">
                                                        <i class="material-icons" style="font-size:12px">done_all</i> Benar
                                                    </span>
                                                @else
                                                    <span class="tbut-output-badge wrong">
                                                        <i class="material-icons" style="font-size:12px">close</i> Belum Tepat
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="tbut-run-chip">{{ $sess->run_count }}x</span>
                                            </td>
                                            <td class="text-center">
                                                @if($sess->final_code)
                                                    <button class="tbut-code-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#codeModal{{ $sess->id }}">
                                                        <i class="material-icons" style="font-size:14px">code</i> Lihat
                                                    </button>

                                                    {{-- Code Modal --}}
                                                    <div class="modal fade" id="codeModal{{ $sess->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none">
                                                                <div class="modal-header" style="background:linear-gradient(135deg,#0057B8,#003b7d);border:none">
                                                                    <h6 class="modal-title text-white fw-semibold">
                                                                        <i class="material-icons align-middle me-2" style="font-size:18px">code</i>
                                                                        Kode Final — {{ $sess->user->name ?? 'Mahasiswa' }}
                                                                    </h6>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <pre class="m-0 p-4" style="background:#1a1d2e;color:#c9d1d9;font-size:0.83rem;max-height:450px;overflow-y:auto;tab-size:4;font-family:'JetBrains Mono','Fira Code',monospace">{{ htmlspecialchars($sess->final_code) }}</pre>
                                                                </div>
                                                                <div class="modal-footer" style="background:#f8f9fa;border:none">
                                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:8px">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted text-xs">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5 text-muted">
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
</x-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    .main-content { font-family: 'Inter', sans-serif; }

    /* Hero */
    .tbut-hero {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        border-radius: 18px; padding: 1.5rem 2rem;
        box-shadow: 0 8px 30px rgba(0,87,184,0.3);
    }

    /* Modern Card */
    .modern-card {
        border: none; box-shadow: 0 10px 30px 0 rgba(0,0,0,0.05);
        border-radius: 16px; background: #fff;
        overflow: visible; margin-top: 2.5rem !important;
    }
    .modern-header {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        box-shadow: 0 8px 25px -8px rgba(0,87,184,0.45);
        border-radius: 16px; transform: translateY(-20px);
    }

    /* Stat Cards */
    .tbut-stat-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06); padding: 1.25rem 1rem 1rem;
        border: 1px solid rgba(0,0,0,0.04); transition: transform .25s ease, box-shadow .25s ease;
    }
    .tbut-stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .tbut-stat-icon {
        width: 40px; height: 40px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .65rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .tbut-stat-icon i { color: #fff; font-size: 20px; }
    .tbut-stat-label { font-size: 10px; font-weight: 600; color: #8392ab; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .tbut-stat-value { font-size: 1.65rem; font-weight: 700; color: #344767; margin: 0; line-height: 1.1; }
    .tbut-stat-sub { font-size: 10px; color: #adb5bd; margin: 4px 0 0; }
    .tbut-mini-bar { height: 5px; background: #f0f2f5; border-radius: 99px; overflow: hidden; margin-top: 8px; }
    .tbut-mini-fill { height: 100%; border-radius: 99px; }

    /* Table */
    .tbut-table thead th {
        font-family: 'Inter', sans-serif; text-transform: uppercase;
        font-size: .63rem; font-weight: 700; letter-spacing: .5px; color: #8392ab;
        border-bottom: 2px solid #f0f2f5; padding: 1rem .75rem; white-space: nowrap;
    }
    .tbut-table tbody td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; padding: .85rem .75rem; }
    .tbut-row { transition: background .15s ease; }
    .tbut-row:hover { background: #f8faff; }

    /* Avatar */
    .tbut-avatar {
        width: 34px; height: 34px; border-radius: 10px;
        background: linear-gradient(135deg,#0057B8,#003b7d);
        color: #fff; font-weight: 700; font-size: 14px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    /* Status badges */
    .tbut-status-badge {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 11px; font-weight: 700; padding: .28rem .65rem; border-radius: 8px;
    }
    .tbut-status-badge.completed { background: rgba(45,206,137,.12); color: #1a9e63; }
    .tbut-status-badge.pending   { background: rgba(251,99,64,.1);   color: #d94a28; }

    /* Output badges */
    .tbut-output-badge {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 11px; font-weight: 700; padding: .28rem .65rem; border-radius: 8px;
    }
    .tbut-output-badge.correct { background: rgba(17,205,239,.1); color: #0a9ab8; }
    .tbut-output-badge.wrong   { background: rgba(245,54,92,.1);  color: #b41a3a; }

    /* Time chip */
    .tbut-time-chip {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 12px; font-weight: 600; color: #fb6340;
        background: rgba(251,99,64,.1); border-radius: 8px; padding: .25rem .6rem;
    }
    .tbut-time-label {
        display: block; font-size: 11px; color: #0057B8; font-weight: 600;
    }

    /* Run chip */
    .tbut-run-chip {
        display: inline-block; font-size: 12px; font-weight: 700;
        color: #8655fc; background: rgba(134,85,252,.1);
        border-radius: 8px; padding: .25rem .6rem;
    }

    /* Code button */
    .tbut-code-btn {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; font-weight: 600; color: #0057B8;
        background: rgba(0,87,184,.08); border: 1px solid rgba(0,87,184,.2);
        border-radius: 8px; padding: .3rem .75rem; cursor: pointer;
        transition: all .2s ease;
    }
    .tbut-code-btn:hover { background: #0057B8; color: #fff; }
</style>
