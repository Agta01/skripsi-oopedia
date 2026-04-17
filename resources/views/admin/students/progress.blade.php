<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="students" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Progress Mahasiswa" />
        <div class="container-fluid py-4 px-4">

            {{-- ── Page Header ── --}}
            <div class="prog-page-header">
                <div class="prog-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                    <h4 class="prog-title">{{ $student->name }}</h4>
                    <div class="prog-sub">
                        <i class="fas fa-envelope" style="font-size:11px;"></i> {{ $student->email }}
                        <span class="mx-2" style="color:#e2e8f0;">|</span>
                        <a href="{{ route('admin.students.index') }}" class="prog-back-link">
                            <i class="fas fa-arrow-left" style="font-size:10px;"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── Summary Chips ── --}}
            <div class="prog-summary-row">
                <div class="prog-chip" style="border-color:#bfdbfe;">
                    <div class="prog-chip-icon" style="background:#eff6ff;color:#0057B8"><i class="fas fa-book"></i></div>
                    <div>
                        <div class="prog-chip-val">{{ $materials->count() }}</div>
                        <div class="prog-chip-lbl">Materi</div>
                    </div>
                </div>
                <div class="prog-chip" style="border-color:#bbf7d0;">
                    <div class="prog-chip-icon" style="background:#f0fdf4;color:#059669"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="prog-chip-val">{{ $materials->where('progress', 100)->count() }}</div>
                        <div class="prog-chip-lbl">Materi Selesai</div>
                    </div>
                </div>
                <div class="prog-chip" style="border-color:#c4b5fd;">
                    <div class="prog-chip-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <div class="prog-chip-val">{{ $vlStats['total'] }}</div>
                        <div class="prog-chip-lbl">Lab Dikerjakan</div>
                    </div>
                </div>
                <div class="prog-chip" style="border-color:#a5f3fc;">
                    <div class="prog-chip-icon" style="background:#f0fdff;color:#0284c7"><i class="fas fa-flag-checkered"></i></div>
                    <div>
                        <div class="prog-chip-val">{{ $vlStats['completed'] }}</div>
                        <div class="prog-chip-lbl">Lab Selesai</div>
                    </div>
                </div>
                <div class="prog-chip" style="border-color:#bbf7d0;">
                    <div class="prog-chip-icon" style="background:#f0fdf4;color:#059669"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="prog-chip-val">{{ $vlStats['success'] }}</div>
                        <div class="prog-chip-lbl">Output Benar</div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════ --}}
            {{-- ── SECTION 1: Progress Soal per Materi ── --}}
            {{-- ══════════════════════════════════════════════════════════════════ --}}
            <div class="prog-section-card">
                <div class="prog-section-hdr" style="background:linear-gradient(135deg,#0057B8,#003b7d);">
                    <div class="prog-section-icon" style="background:rgba(255,255,255,.18);color:#fff;"><i class="fas fa-book-open"></i></div>
                    <h6 class="prog-section-title" style="color:#fff;">Progress Latihan Soal</h6>
                </div>
                <div class="prog-section-body">
                    <table class="prog-table">
                        <thead>
                            <tr>
                                <th>Materi</th>
                                <th class="text-center">Benar / Total</th>
                                <th>Progress</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Terakhir Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materials as $mat)
                            <tr>
                                <td>
                                    <div class="prog-mat-name">{{ $mat->title }}</div>
                                </td>
                                <td class="text-center">
                                    <span style="font-size:13px;font-weight:700;color:#1e293b;">
                                        {{ $mat->answered_questions }} / {{ $mat->total_questions }}
                                    </span>
                                </td>
                                <td style="min-width:140px;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="progress flex-grow-1" style="height:7px;border-radius:10px;background:#f1f5f9;">
                                            <div class="progress-bar"
                                                 style="width:{{ $mat->progress }}%;background:linear-gradient(90deg,#059669,#10b981);border-radius:10px;"
                                                 role="progressbar"></div>
                                        </div>
                                        <span style="font-size:12px;font-weight:700;color:#059669;min-width:35px;">{{ $mat->progress }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($mat->progress == 100)
                                        <span class="prog-badge prog-badge-green">Selesai</span>
                                    @elseif($mat->progress > 0)
                                        <span class="prog-badge prog-badge-orange">Dalam Proses</span>
                                    @else
                                        <span class="prog-badge prog-badge-gray">Belum</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size:12px;color:#64748b;">
                                    {{ $mat->last_accessed ? $mat->last_accessed->diffForHumans() : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="prog-empty">Belum ada data soal</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════ --}}
            {{-- ── SECTION 2: Virtual Lab Detail ── --}}
            {{-- ══════════════════════════════════════════════════════════════════ --}}
            <div class="prog-section-card">
                <div class="prog-section-hdr" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <div class="prog-section-icon" style="background:rgba(255,255,255,.18);color:#fff;"><i class="fas fa-laptop-code"></i></div>
                    <h6 class="prog-section-title" style="color:#fff;">Progress Virtual Lab</h6>
                    <div class="ms-auto d-flex gap-3" style="font-size:12px;color:rgba(255,255,255,.8);">
                        <span><i class="fas fa-stopwatch me-1"></i>Rata waktu: {{ $vlStats['avg_secs'] > 0 ? gmdate('i:s', round($vlStats['avg_secs'])) : '—' }}</span>
                        <span><i class="fas fa-redo me-1"></i>Rata run: {{ $vlStats['avg_runs'] > 0 ? round($vlStats['avg_runs'],1) : '—' }}x</span>
                    </div>
                </div>
                <div class="prog-section-body">
                    @if($vlSessions->count() > 0)
                    <table class="prog-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tugas</th>
                                <th>Materi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Output</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center">Run</th>
                                <th class="text-center">Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vlSessions as $i => $sess)
                            <tr>
                                <td style="color:#94a3b8;font-size:12px;font-weight:700;text-align:center;">{{ $i+1 }}</td>
                                <td>
                                    <div class="prog-mat-name">{{ $sess->task->title ?? '—' }}</div>
                                    @if($sess->task)
                                    <div class="prog-diff-badge
                                        {{ $sess->task->difficulty === 'beginner' ? 'prog-diff-green' : ($sess->task->difficulty === 'intermediate' ? 'prog-diff-orange' : 'prog-diff-red') }}">
                                        {{ ucfirst($sess->task->difficulty) }}
                                    </div>
                                    @endif
                                </td>
                                <td style="font-size:12px;color:#64748b;">
                                    {{ $sess->task->material->title ?? '—' }}
                                </td>
                                <td class="text-center">
                                    @if($sess->is_completed)
                                        <span class="prog-badge prog-badge-indigo">
                                            <i class="fas fa-flag-checkered me-1"></i>Selesai
                                        </span>
                                    @else
                                        <span class="prog-badge prog-badge-gray">
                                            <i class="fas fa-clock me-1"></i>Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($sess->is_success)
                                        <span class="prog-badge prog-badge-green">
                                            <i class="fas fa-check me-1"></i>Benar
                                        </span>
                                    @elseif($sess->is_completed)
                                        <span class="prog-badge prog-badge-red">
                                            <i class="fas fa-times me-1"></i>Salah
                                        </span>
                                    @else
                                        <span style="color:#cbd5e1;font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size:12px;font-weight:700;color:#475569;">
                                    {{ $sess->duration_seconds > 0 ? $sess->formattedDuration() : '—' }}
                                </td>
                                <td class="text-center">
                                    <span style="font-size:13px;font-weight:800;color:#6366f1;">{{ $sess->run_count }}x</span>
                                </td>
                                <td class="text-center" style="font-size:11px;color:#94a3b8;">
                                    {{ $sess->started_at ? $sess->started_at->format('d M Y\nH:i') : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="prog-empty-state">
                        <i class="fas fa-laptop-code"></i>
                        <p>Mahasiswa ini belum mengerjakan tugas virtual lab.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── Recent Activity ── --}}
            @if($recent_activities->count() > 0)
            <div class="prog-section-card">
                <div class="prog-section-hdr" style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                    <div class="prog-section-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-history"></i></div>
                    <h6 class="prog-section-title" style="color:#1e293b;">Aktivitas Latihan Soal Terbaru</h6>
                </div>
                <div class="prog-section-body" style="padding:12px 0;">
                    @foreach($recent_activities as $act)
                    <div class="prog-activity-row">
                        <div class="prog-activity-dot {{ $act->is_correct ? 'prog-dot-green' : 'prog-dot-red' }}"></div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:12px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {!! Str::limit(strip_tags($act->question_title), 60) !!}
                            </div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $act->material_title }}</div>
                        </div>
                        <div>
                            @if($act->is_correct)
                                <span class="prog-badge prog-badge-green"><i class="fas fa-check me-1"></i>Benar</span>
                            @else
                                <span class="prog-badge prog-badge-red"><i class="fas fa-times me-1"></i>Salah</span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#94a3b8;min-width:80px;text-align:right;">
                            {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </main>
    <x-admin.tutorial />
</x-layout>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

/* Page Header */
.prog-page-header { display:flex;align-items:center;gap:16px;margin-bottom:20px; }
.prog-avatar {
    width:56px;height:56px;border-radius:16px;flex-shrink:0;
    background:linear-gradient(135deg,#0057B8,#6366f1);
    color:#fff;font-size:20px;font-weight:800;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 6px 20px rgba(0,87,184,.3);
}
.prog-title { font-size:22px;font-weight:800;color:#1e293b;margin:0 0 4px; }
.prog-sub   { font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;flex-wrap:wrap; }
.prog-back-link { color:#0057B8;text-decoration:none;font-weight:600; }
.prog-back-link:hover { text-decoration:underline; }

/* Summary chips */
.prog-summary-row { display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap; }
.prog-chip {
    background:#fff;border-radius:14px;padding:12px 16px;
    display:flex;align-items:center;gap:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    border:1.5px solid #f1f5f9;
    flex:1;min-width:120px;
}
.prog-chip-icon { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0; }
.prog-chip-val  { font-size:22px;font-weight:800;color:#1e293b;line-height:1; }
.prog-chip-lbl  { font-size:11px;color:#94a3b8;font-weight:500;margin-top:2px;white-space:nowrap; }

/* Section card */
.prog-section-card {
    background:#fff;border-radius:16px;
    box-shadow:0 2px 14px rgba(0,0,0,.06);
    border:1px solid #f1f5f9;
    overflow:hidden;margin-bottom:20px;
}
.prog-section-hdr {
    display:flex;align-items:center;gap:10px;
    padding:14px 20px;
}
.prog-section-icon { width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0; }
.prog-section-title { font-size:14px;font-weight:700;margin:0; }
.prog-section-body { padding:4px 0; }

/* Table */
.prog-table { width:100%;border-collapse:collapse; }
.prog-table thead th {
    padding:12px 16px;font-size:11px;font-weight:700;
    letter-spacing:.5px;text-transform:uppercase;color:#94a3b8;
    background:#f8fafc;border-bottom:2px solid #f1f5f9;white-space:nowrap;
}
.prog-table tbody td { padding:12px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
.prog-table tbody tr:hover { background:#f8faff; }
.prog-table tbody tr:last-child td { border-bottom:none; }

.prog-mat-name { font-size:13px;font-weight:700;color:#1e293b; }
.prog-empty { text-align:center;padding:32px;color:#94a3b8;font-size:13px; }

/* Badges */
.prog-badge { display:inline-flex;align-items:center;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px; }
.prog-badge-green  { background:#dcfce7;color:#15803d; }
.prog-badge-orange { background:#fef3c7;color:#b45309; }
.prog-badge-gray   { background:#f1f5f9;color:#64748b; }
.prog-badge-red    { background:#fee2e2;color:#dc2626; }
.prog-badge-indigo { background:#ede9fe;color:#6d28d9; }

/* Difficulty */
.prog-diff-badge { display:inline-block;font-size:9px;font-weight:700;padding:2px 7px;border-radius:6px;margin-top:3px; }
.prog-diff-green  { background:#dcfce7;color:#15803d; }
.prog-diff-orange { background:#fef3c7;color:#b45309; }
.prog-diff-red    { background:#fee2e2;color:#dc2626; }

/* Empty state */
.prog-empty-state { text-align:center;padding:48px;color:#94a3b8; }
.prog-empty-state i { font-size:36px;color:#e2e8f0;display:block;margin-bottom:10px; }
.prog-empty-state p { font-size:14px; }

/* Activity */
.prog-activity-row {
    display:flex;align-items:center;gap:12px;
    padding:10px 20px;border-bottom:1px solid #f8fafc;
}
.prog-activity-row:last-child { border-bottom:none; }
.prog-activity-dot { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
.prog-dot-green { background:#10b981; }
.prog-dot-red   { background:#f43f5e; }

@media (max-width:768px) {
    .prog-summary-row { flex-wrap:wrap; }
    .prog-chip { min-width:calc(50% - 6px);flex:none; }
}
</style>