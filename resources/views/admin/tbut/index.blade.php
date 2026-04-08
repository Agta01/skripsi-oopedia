<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Analisis TBUT" />

        <div class="container-fluid py-4">

            {{-- Header --}}
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bolder mb-0">Analisis TBUT — Task-Based Usability Testing</h5>
                        <p class="text-sm text-secondary mb-0">Efisiensi &amp; Efektivitas pengerjaan tugas Virtual Lab (ISO 9241-11)</p>
                    </div>
                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <select name="material_id" class="form-select form-select-sm" onchange="this.form.submit()">
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

            {{-- Summary Cards --}}
            @php
                $totalSessions  = \App\Models\TbutSession::count();
                $completedSess  = \App\Models\TbutSession::where('is_completed', true)->count();
                $avgDuration    = \App\Models\TbutSession::avg('duration_seconds');
                $avgRunCount    = \App\Models\TbutSession::avg('run_count');
                $completionRate = $totalSessions > 0 ? round(($completedSess / $totalSessions) * 100, 1) : 0;
            @endphp

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                                    <i class="material-icons text-white">people</i>
                                </div>
                                <div>
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Sesi</p>
                                    <h4 class="font-weight-bolder mb-0">{{ $totalSessions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3">
                                    <i class="material-icons text-white">check_circle</i>
                                </div>
                                <div>
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Completion Rate</p>
                                    <h4 class="font-weight-bolder mb-0">{{ $completionRate }}%</h4>
                                    <p class="text-xs text-secondary mb-0">{{ $completedSess }} dari {{ $totalSessions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3">
                                    <i class="material-icons text-white">timer</i>
                                </div>
                                <div>
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Durasi</p>
                                    <h4 class="font-weight-bolder mb-0">{{ $avgDuration ? gmdate('i:s', intval($avgDuration)) : '-' }}</h4>
                                    <p class="text-xs text-secondary mb-0">menit:detik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                                    <i class="material-icons text-white">play_circle</i>
                                </div>
                                <div>
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Run Code</p>
                                    <h4 class="font-weight-bolder mb-0">{{ $avgRunCount ? round($avgRunCount, 1) : '-' }}</h4>
                                    <p class="text-xs text-secondary mb-0">kali per sesi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Task Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">
                                    <i class="material-icons me-2" style="vertical-align:middle">assignment</i>
                                    Rekap Per Tugas
                                </h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tugas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kesulitan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Peserta</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Selesai</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completion Rate</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avg Waktu</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avg Run</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $task->title }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">{{ $task->material->title ?? '-' }}</p>
                                            </td>
                                            <td>
                                                @php
                                                    $diffColors = ['beginner'=>'success','intermediate'=>'warning','advanced'=>'danger'];
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $diffColors[$task->difficulty] ?? 'secondary' }}">
                                                    {{ ucfirst($task->difficulty) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="font-weight-bold">{{ $task->total_attempts }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-success font-weight-bold">{{ $task->completed_count }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="progress-wrapper w-75 mx-auto">
                                                    <div class="progress-info d-flex justify-content-between">
                                                        <span class="text-xs font-weight-bolder">{{ $task->completion_rate }}%</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success" style="width:{{ $task->completion_rate }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $task->avg_duration ? gmdate('i:s', intval($task->avg_duration)) : '-' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $task->avg_run_count ? round($task->avg_run_count, 1) : '-' }}x
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('admin.tbut.show', $task->id) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="material-icons text-sm me-2">visibility</i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-secondary py-4">
                                                <i class="material-icons d-block mb-2">inbox</i>
                                                Belum ada tugas atau sesi TBUT.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-admin.tutorial />
</x-layout>
