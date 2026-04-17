<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="students" :userName="auth()->user()->name"
        :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Data Mahasiswa" />
        <div class="container-fluid py-4">

            {{-- Stats Banner --}}
            <div class="std-stats-row">
                <div class="std-stat-card">
                    <div class="std-stat-icon" style="background:rgba(0,87,184,.12);color:#0057B8"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="std-stat-num">{{ $students->total() }}</div>
                        <div class="std-stat-lbl">Total Mahasiswa</div>
                    </div>
                </div>
                <div class="std-stat-card">
                    <div class="std-stat-icon" style="background:rgba(99,102,241,.12);color:#6366f1"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <div class="std-stat-num">{{ $totalVlTasks }}</div>
                        <div class="std-stat-lbl">Tugas Virtual Lab</div>
                    </div>
                </div>
            </div>

            {{-- Search Form --}}
            <div class="row mb-4 mt-2">
                <div class="col-md-7 col-lg-5">
                    <form method="GET" action="{{ route('admin.students.index') }}">
                        <div class="d-flex align-items-center bg-white px-2 py-2 rounded-3 shadow-sm border" style="border-color: #e9ecef !important;">
                            <i class="material-icons text-muted ms-2 me-2">search</i>
                            <input type="text" name="search" class="form-control border-0 px-2"
                                   placeholder="Cari berdasarkan nama mahasiswa..."
                                   value="{{ request('search') }}"
                                   style="box-shadow: none; background: transparent;">
                            <button class="btn btn-primary mb-0 ms-2 rounded-2 px-4 py-2" type="submit" style="text-transform: none; font-weight: 600;">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card modern-card">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('importErrors'))
                            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                                <p>Beberapa baris tidak dapat diimpor:</p>
                                <ul>
                                    @foreach(session('importErrors') as $error)
                                        <li>Baris {{ $error['row'] }}: {{ implode(', ', $error['errors']) }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">people</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 fw-semibold" style="letter-spacing:.4px">Data Mahasiswa</h6>
                                </div>
                                <div class="d-flex me-0">
                                    <a href="{{ route('admin.students.import') }}" class="btn btn-sm btn-light text-primary mb-0 d-flex align-items-center gap-1 shadow-sm" style="border-radius:8px; font-weight:600;">
                                        <i class="material-icons text-sm">upload_file</i>
                                        <span>Tambah/Import Excel</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-3 pb-2 w-100">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="modern-table-header">
                                        <tr>
                                            <th style="padding: 1rem .75rem;">#</th>
                                            <th style="padding: 1rem .75rem;">Mahasiswa</th>
                                            <th class="text-center" style="padding: 1rem .75rem;">Progress Soal</th>
                                            <th class="text-center" style="padding: 1rem .75rem;">Virtual Lab</th>
                                            <th class="text-center" style="padding: 1rem .75rem;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $i => $student)
                                        <tr>
                                            {{-- No --}}
                                            <td style="width:40px;text-align:center;font-size:12px;color:#94a3b8;font-weight:700;">
                                                {{ $students->firstItem() + $i }}
                                            </td>
                                            {{-- Mahasiswa --}}
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="std-avatar">{{ strtoupper(substr($student->name,0,1)) }}</div>
                                                    <div>
                                                        <div class="std-name">{{ $student->name }}</div>
                                                        <div class="std-email">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- Progress Soal --}}
                                            <td class="text-center">
                                                <div class="std-progress-wrap">
                                                    <div class="progress" style="height:7px;border-radius:10px;background:#f1f5f9;">
                                                        <div class="progress-bar"
                                                             style="width:{{ $student->overall_progress }}%;background:linear-gradient(90deg,#0057B8,#6366f1);border-radius:10px;"
                                                             role="progressbar"></div>
                                                    </div>
                                                    <div class="std-progress-label">{{ $student->overall_progress }}%</div>
                                                </div>
                                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $student->total_answered_questions }} percobaan</div>
                                            </td>
                                            {{-- Virtual Lab --}}
                                            <td class="text-center">
                                                @if($student->vl_total > 0)
                                                    <div class="std-vl-wrap">
                                                        {{-- Mini progress bar --}}
                                                        @php
                                                            $vlPct = $student->vl_total_tasks > 0
                                                                ? round(($student->vl_completed / $student->vl_total_tasks) * 100)
                                                                : 0;
                                                        @endphp
                                                        <div class="progress mb-1" style="height:7px;border-radius:10px;background:#f1f5f9;">
                                                            <div class="progress-bar"
                                                                 style="width:{{ $vlPct }}%;background:linear-gradient(90deg,#059669,#10b981);border-radius:10px;"
                                                                 role="progressbar"></div>
                                                        </div>
                                                        <div class="d-flex justify-content-center gap-2 flex-wrap" style="margin-top:4px;">
                                                            <span class="std-vl-chip std-vl-chip-blue"
                                                                  title="Tugas dikerjakan">
                                                                <i class="fas fa-play-circle"></i> {{ $student->vl_total }}
                                                            </span>
                                                            <span class="std-vl-chip std-vl-chip-indigo"
                                                                  title="Tugas selesai">
                                                                <i class="fas fa-flag-checkered"></i> {{ $student->vl_completed }}
                                                            </span>
                                                            <span class="std-vl-chip {{ $student->vl_success > 0 ? 'std-vl-chip-green' : 'std-vl-chip-gray' }}"
                                                                  title="Output benar">
                                                                <i class="fas fa-check-circle"></i> {{ $student->vl_success }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span style="font-size:12px;color:#cbd5e1;">Belum ada</span>
                                                @endif
                                            </td>
                                            {{-- Aksi --}}
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.students.progress', $student) }}"
                                                       class="btn btn-sm btn-info mb-0 d-flex align-items-center gap-1 shadow-sm"
                                                       style="background: linear-gradient(135deg, #11cdef 0%, #1181ef 100%); border:none; border-radius: 8px;">
                                                        <i class="material-icons" style="font-size:14px">visibility</i>
                                                        <span>Detail</span>
                                                    </a>
                                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-0 d-flex align-items-center gap-1 shadow-sm"
                                                                style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); border:none; border-radius: 8px;"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')">
                                                            <i class="material-icons" style="font-size:14px">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-users" style="font-size:36px;color:#e2e8f0;"></i>
                                                <p class="text-sm mb-0 mt-2" style="color:#94a3b8;">Belum ada data mahasiswa</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $students->links() }}
                            </div>
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

/* Stats banner */
.std-stats-row { display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.std-stat-card {
    background:#fff; border-radius:16px; padding:16px 20px;
    display:flex; align-items:center; gap:14px;
    box-shadow:0 2px 12px rgba(0,0,0,.06); border:1px solid #f1f5f9;
    min-width:180px;
}
.std-stat-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0; }
.std-stat-num  { font-size:24px;font-weight:800;color:#1e293b;line-height:1; }
.std-stat-lbl  { font-size:12px;color:#64748b;font-weight:500;margin-top:2px; }

/* Avatar */
.std-avatar {
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(135deg,#0057B8,#6366f1);
    color:#fff;font-size:14px;font-weight:800;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.std-name  { font-size:14px;font-weight:700;color:#1e293b; }
.std-email { font-size:11px;color:#94a3b8; }

/* Progress */
.std-progress-wrap { width:120px;margin:0 auto; }
.std-progress-label { font-size:12px;font-weight:700;color:#0057B8;margin-top:3px;text-align:center; }

/* VL chips */
.std-vl-wrap { min-width:120px; }
.std-vl-chip {
    display:inline-flex;align-items:center;gap:4px;
    font-size:11px;font-weight:700;border-radius:6px;padding:3px 8px;
}
.std-vl-chip-blue   { background:#eff6ff;color:#0057B8; }
.std-vl-chip-indigo { background:#f5f3ff;color:#6366f1; }
.std-vl-chip-green  { background:#f0fdf4;color:#059669; }
.std-vl-chip-gray   { background:#f1f5f9;color:#94a3b8; }

/* Card System */
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
/* Tables */
.modern-table-header th {
    font-family: 'Inter', sans-serif;
    text-transform: uppercase;
    font-size: .63rem;
    font-weight: 700;
    letter-spacing: .5px;
    color: #8392ab;
    border-bottom: 2px solid #f0f2f5;
    white-space: nowrap;
}
.table tbody td {
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
    padding: .85rem .75rem;
}
.table tbody tr { transition: background .15s ease; }
.table tbody tr:hover { background: #f8faff; }
</style>