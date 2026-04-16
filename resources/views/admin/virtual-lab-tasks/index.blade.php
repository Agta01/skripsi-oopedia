<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name"
        :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Kelola Tugas Virtual Lab" />

        <div class="container-fluid py-4 px-4">

            {{-- ── STATS BANNER ── --}}
            <div class="vlt-stats-row">
                <div class="vlt-stat-card">
                    <div class="vlt-stat-icon" style="background:rgba(0,87,184,.12);color:#0057B8">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div>
                        <div class="vlt-stat-num">{{ $tasks->total() }}</div>
                        <div class="vlt-stat-label">Total Tugas</div>
                    </div>
                </div>
                <div class="vlt-stat-card">
                    <div class="vlt-stat-icon" style="background:rgba(16,185,129,.12);color:#059669">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="vlt-stat-num">{{ $materials->count() }}</div>
                        <div class="vlt-stat-label">Materi</div>
                    </div>
                </div>
                <div class="vlt-stat-card">
                    <div class="vlt-stat-icon" style="background:rgba(245,158,11,.12);color:#d97706">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="vlt-stat-num">{{ \App\Models\VirtualLabTask::whereNotNull('deadline_minutes')->count() }}</div>
                        <div class="vlt-stat-label">Punya Deadline</div>
                    </div>
                </div>
                <div class="vlt-stat-card">
                    <div class="vlt-stat-icon" style="background:rgba(99,102,241,.12);color:#6366f1">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="vlt-stat-num">{{ \App\Models\TbutSession::distinct('user_id')->count('user_id') }}</div>
                        <div class="vlt-stat-label">Mahasiswa Aktif</div>
                    </div>
                </div>
            </div>

            {{-- ── MAIN CARD ── --}}
            <div class="vlt-card">

                {{-- Header --}}
                <div class="vlt-card-header">
                    <div class="vlt-card-header-left">
                        <div class="vlt-header-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div>
                            <h5 class="vlt-card-title">Daftar Tugas Virtual Lab</h5>
                            <p class="vlt-card-sub">Kelola soal pemrograman Java untuk mahasiswa</p>
                        </div>
                    </div>
                    <div class="vlt-card-header-right">
                        {{-- Filter --}}
                        <form method="GET" action="{{ route('admin.virtual-lab-tasks.index') }}" id="filterForm">
                            <div class="vlt-filter-wrap">
                                <i class="fas fa-filter" style="color:#94a3b8;font-size:13px;"></i>
                                <select name="material_id" class="vlt-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Semua Materi</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ Str::limit($material->title, 30) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <a href="{{ route('admin.virtual-lab-tasks.create') }}" class="vlt-btn-primary">
                            <i class="fas fa-plus"></i> Tambah Tugas
                        </a>
                    </div>
                </div>

                {{-- Flash --}}
                @if(session('success'))
                <div class="vlt-alert vlt-alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="vlt-table">
                        <thead>
                            <tr>
                                <th style="width:36px;">#</th>
                                <th>Judul Tugas</th>
                                <th>Materi</th>
                                <th>Kesulitan</th>
                                <th>Deadline</th>
                                <th>Sesi TBUT</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $i => $task)
                            <tr>
                                <td class="vlt-td-num">{{ $tasks->firstItem() + $i }}</td>
                                <td>
                                    <div class="vlt-task-name">{{ $task->title }}</div>
                                    <div class="vlt-task-desc">{{ Str::limit(strip_tags($task->description), 55) }}</div>
                                </td>
                                <td>
                                    <span class="vlt-material-tag">
                                        <i class="fas fa-book" style="font-size:10px;opacity:.7;"></i>
                                        {{ Str::limit($task->material->title, 28) }}
                                    </span>
                                </td>
                                <td>
                                    @if($task->difficulty === 'beginner')
                                        <span class="vlt-badge vlt-badge-green">Beginner</span>
                                    @elseif($task->difficulty === 'intermediate')
                                        <span class="vlt-badge vlt-badge-orange">Intermediate</span>
                                    @else
                                        <span class="vlt-badge vlt-badge-red">Advanced</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->deadline_minutes)
                                        <div class="vlt-deadline-chip">
                                            <i class="fas fa-hourglass-half"></i>
                                            @if($task->deadline_minutes >= 60)
                                                {{ intdiv($task->deadline_minutes, 60) }}j
                                                @if($task->deadline_minutes % 60)
                                                    {{ $task->deadline_minutes % 60 }}m
                                                @endif
                                            @else
                                                {{ $task->deadline_minutes }}m
                                            @endif
                                        </div>
                                    @else
                                        <span class="vlt-no-deadline">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="vlt-session-info">
                                        <span class="vlt-session-count">{{ $task->tbutSessions->count() }}</span>
                                        <span class="vlt-session-label">sesi</span>
                                        @php $done = $task->tbutSessions->where('is_completed', true)->count(); @endphp
                                        @if($done > 0)
                                        <span class="vlt-session-done">{{ $done }} selesai</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="vlt-actions">
                                        <a href="{{ route('admin.virtual-lab-tasks.edit', $task->id) }}" class="vlt-btn-edit" title="Edit">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.virtual-lab-tasks.destroy', $task->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus tugas \'{{ addslashes($task->title) }}\'? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vlt-btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="vlt-empty">
                                    <div class="vlt-empty-icon"><i class="fas fa-flask"></i></div>
                                    <div class="vlt-empty-text">Belum ada tugas virtual lab.</div>
                                    <a href="{{ route('admin.virtual-lab-tasks.create') }}" class="vlt-btn-primary" style="margin-top:12px;display:inline-flex;">
                                        <i class="fas fa-plus"></i> Buat Tugas Pertama
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($tasks->hasPages())
                <div class="vlt-pagination">
                    {{ $tasks->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</x-layout>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

/* ══ Root ══════════════════════════════════════════════════════════════ */
.container-fluid { font-family: 'Inter', sans-serif; }

/* ══ Stats Row ══════════════════════════════════════════════════════════ */
.vlt-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.vlt-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1px solid #f1f5f9;
    transition: transform .2s, box-shadow .2s;
}
.vlt-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
.vlt-stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.vlt-stat-num  { font-size: 26px; font-weight: 800; color: #1e293b; line-height: 1; }
.vlt-stat-label{ font-size: 12px; color: #64748b; font-weight: 500; margin-top: 3px; }

/* ══ Main Card ════════════════════════════════════════════════════════ */
.vlt-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
    overflow: hidden;
    border: 1px solid #f1f5f9;
}
.vlt-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 28px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 14px;
    background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
}
.vlt-card-header-left  { display: flex; align-items: center; gap: 14px; }
.vlt-card-header-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.vlt-header-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.18);
    border: 1.5px solid rgba(255,255,255,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff; flex-shrink: 0;
}
.vlt-card-title { font-size: 16px; font-weight: 700; color: #fff; margin: 0; }
.vlt-card-sub   { font-size: 12px; color: rgba(255,255,255,.7); margin: 2px 0 0; }

/* Filter */
.vlt-filter-wrap {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.25);
    border-radius: 10px; padding: 6px 12px;
}
.vlt-select {
    background: transparent; border: none; outline: none;
    color: #fff; font-size: 13px; font-weight: 500;
    cursor: pointer;
}
.vlt-select option { background: #1e293b; color: #fff; }

/* Primary button */
.vlt-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: rgba(255,255,255,.18);
    border: 1.5px solid rgba(255,255,255,.35);
    color: #fff; font-size: 13px; font-weight: 700;
    text-decoration: none; white-space: nowrap;
    transition: all .18s;
}
.vlt-btn-primary:hover { background: rgba(255,255,255,.3); color: #fff; text-decoration: none; }

/* Alert */
.vlt-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 24px; font-size: 13px; font-weight: 600;
}
.vlt-alert-success { background: #f0fdf4; color: #15803d; border-bottom: 1px solid #bbf7d0; }

/* ══ Table ════════════════════════════════════════════════════════════ */
.vlt-table {
    width: 100%; border-collapse: collapse;
}
.vlt-table thead th {
    padding: 14px 16px;
    font-size: 11px; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: #94a3b8;
    background: #f8fafc;
    border-bottom: 2px solid #f1f5f9;
    white-space: nowrap;
}
.vlt-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}
.vlt-table tbody tr { transition: background .15s; }
.vlt-table tbody tr:hover { background: #f8faff; }
.vlt-table tbody tr:last-child td { border-bottom: none; }

.vlt-td-num { font-size: 12px; font-weight: 700; color: #94a3b8; text-align: center; }

.vlt-task-name { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
.vlt-task-desc { font-size: 12px; color: #94a3b8; line-height: 1.4; }

.vlt-material-tag {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600; color: #475569;
    background: #f1f5f9; border-radius: 6px;
    padding: 4px 10px;
}

/* Difficulty badges */
.vlt-badge {
    display: inline-block; padding: 4px 12px;
    border-radius: 20px; font-size: 11px; font-weight: 700;
    letter-spacing: .3px; white-space: nowrap;
}
.vlt-badge-green  { background: #dcfce7; color: #15803d; }
.vlt-badge-orange { background: #fef3c7; color: #b45309; }
.vlt-badge-red    { background: #fee2e2; color: #dc2626; }

/* Deadline chip */
.vlt-deadline-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700; color: #b45309;
    background: #fef3c7; border-radius: 8px;
    padding: 4px 10px;
}
.vlt-no-deadline { font-size: 16px; color: #cbd5e1; }

/* Session info */
.vlt-session-info { display: flex; align-items: center; gap: 5px; }
.vlt-session-count { font-size: 16px; font-weight: 800; color: #6366f1; }
.vlt-session-label { font-size: 11px; color: #94a3b8; }
.vlt-session-done  {
    font-size: 11px; font-weight: 600; color: #059669;
    background: #f0fdf4; border-radius: 6px; padding: 2px 7px;
}

/* Action buttons */
.vlt-actions { display: flex; align-items: center; gap: 8px; justify-content: center; }
.vlt-btn-edit {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 8px;
    background: linear-gradient(135deg, #0ea5e9, #0057B8);
    color: #fff; font-size: 12px; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    transition: all .18s; box-shadow: 0 2px 8px rgba(0,87,184,.25);
}
.vlt-btn-edit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,87,184,.35); color: #fff; text-decoration: none; }
.vlt-btn-delete {
    display: inline-flex; align-items: center;
    padding: 6px 10px; border-radius: 8px;
    background: linear-gradient(135deg, #f87171, #dc2626);
    color: #fff; font-size: 12px; font-weight: 700;
    border: none; cursor: pointer;
    transition: all .18s; box-shadow: 0 2px 8px rgba(220,38,38,.25);
}
.vlt-btn-delete:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(220,38,38,.35); }

/* Empty state */
.vlt-empty { text-align: center; padding: 56px 24px; }
.vlt-empty-icon { font-size: 40px; color: #e2e8f0; margin-bottom: 12px; }
.vlt-empty-text { font-size: 14px; color: #94a3b8; }

/* Pagination */
.vlt-pagination { padding: 16px 24px; border-top: 1px solid #f1f5f9; }

/* Responsive */
@media (max-width: 992px) {
    .vlt-stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .vlt-stats-row { grid-template-columns: 1fr 1fr; }
    .vlt-card-header { flex-direction: column; align-items: flex-start; }
}
</style>