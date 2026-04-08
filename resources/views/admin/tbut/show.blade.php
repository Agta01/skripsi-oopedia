<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="tbut" :userName="auth()->user()->name" :userRole="auth()->user()->role->name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth :titlePage="'TBUT — ' . $task->title" />

        <div class="container-fluid py-4">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.tbut.index') }}" class="text-primary">Analisis TBUT</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $task->title }}</li>
                </ol>
            </nav>

            {{-- Summary Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Total Peserta</p>
                            <h4 class="font-weight-bolder text-primary mb-0">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Selesai</p>
                            <h4 class="font-weight-bolder text-success mb-0">{{ $stats['completed'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Completion Rate</p>
                            <h4 class="font-weight-bolder text-info mb-0">{{ $stats['completion_rate'] }}%</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Avg Durasi</p>
                            <h5 class="font-weight-bolder mb-0" style="color:#fd7e14">
                                {{ $stats['avg_duration'] ? gmdate('i:s', intval($stats['avg_duration'])) : '-' }}
                            </h5>
                            <p class="text-xs text-secondary mb-0">menit:detik</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Min Durasi</p>
                            <h5 class="font-weight-bolder text-secondary mb-0">
                                {{ $stats['min_duration'] !== null ? gmdate('i:s', intval($stats['min_duration'])) : '-' }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <p class="text-sm text-secondary mb-1">Avg Run Code</p>
                            <h4 class="font-weight-bolder mb-0" style="color:#6f42c1">
                                {{ $stats['avg_run_count'] ? round($stats['avg_run_count'], 1) : '-' }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Session Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">
                                    <i class="material-icons me-2" style="vertical-align:middle">people</i>
                                    Detail Per Mahasiswa — {{ $task->title }}
                                </h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mahasiswa</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Mulai</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Submit</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durasi</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Run Code</th>
                                            <th class="text-secondary opacity-7">Kode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sessions as $i => $sess)
                                        <tr>
                                            <td><p class="text-xs text-secondary mb-0 px-3">{{ $i + 1 }}</p></td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $sess->user->name ?? '-' }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $sess->user->email ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if($sess->is_completed)
                                                    <span class="badge badge-sm bg-gradient-success">Selesai</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-warning">Belum Selesai</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs">
                                                    {{ $sess->started_at ? $sess->started_at->format('d M Y H:i') : '-' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs">
                                                    {{ $sess->submitted_at ? $sess->submitted_at->format('d M Y H:i') : '-' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold">
                                                    {{ $sess->duration_seconds > 0 ? gmdate('i:s', $sess->duration_seconds) : '-' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gradient-secondary">{{ $sess->run_count }}x</span>
                                            </td>
                                            <td class="align-middle">
                                                @if($sess->final_code)
                                                    <button class="btn btn-link text-secondary mb-0 text-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#codeModal{{ $sess->id }}">
                                                        <i class="material-icons text-sm me-1">code</i> Lihat
                                                    </button>
                                                    <div class="modal fade" id="codeModal{{ $sess->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title">
                                                                        Kode Final — {{ $sess->user->name ?? 'Mahasiswa' }}
                                                                    </h6>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <pre class="m-0 p-4" style="background:#1e1e1e;color:#d4d4d4;font-size:0.85rem;max-height:400px;overflow-y:auto;border-radius:0 0 4px 4px;">{{ htmlspecialchars($sess->final_code) }}</pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-secondary text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-secondary py-4">
                                                <i class="material-icons d-block mb-2">inbox</i>
                                                Belum ada mahasiswa yang mengerjakan tugas ini.
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
