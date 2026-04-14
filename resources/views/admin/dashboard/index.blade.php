<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="dashboard" :userName="$userName" :userRole="$userRole"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Dashboard Admin"></x-navbars.navs.auth>

        <div class="container-fluid py-4">

            <style>
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                }
                @keyframes pulse-glow {
                    0%, 100% { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                    50% { box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
                }
                @keyframes gradient-shift {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
                }
                @keyframes shimmer {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(100%); }
                }
                .animate-fade-in {
                    animation: fadeInUp 0.5s ease-out forwards;
                }
                .animate-float {
                    animation: float 3s ease-in-out infinite;
                }
                .kpi-card {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    position: relative;
                    overflow: hidden;
                }
                .kpi-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
                    transition: 0.5s;
                }
                .kpi-card:hover::before {
                    left: 100%;
                }
                .kpi-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                }
                .kpi-card .icon-wrapper {
                    transition: all 0.3s ease;
                }
                .kpi-card:hover .icon-wrapper {
                    transform: scale(1.1) rotate(5deg);
                }
                .chart-container {
                    transition: all 0.3s ease;
                }
                .chart-container:hover {
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                }
                .table-hover tbody tr {
                    transition: all 0.2s ease;
                }
                .table-hover tbody tr:hover {
                    background: linear-gradient(90deg, rgba(79,172,254,0.05) 0%, rgba(0,242,254,0.05) 100%);
                    transform: translateX(3px);
                }
                .material-item {
                    transition: all 0.3s ease;
                }
                .material-item:hover {
                    transform: translateX(5px);
                }
                .btn-modern {
                    position: relative;
                    overflow: hidden;
                    transition: all 0.3s ease;
                }
                .btn-modern::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                    transition: 0.5s;
                }
                .btn-modern:hover::before {
                    left: 100%;
                }
                .btn-modern:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 20px rgba(79,172,254,0.3);
                }
                .activity-row {
                    animation: fadeInUp 0.4s ease-out;
                }
                .activity-row:nth-child(1) { animation-delay: 0.05s; }
                .activity-row:nth-child(2) { animation-delay: 0.1s; }
                .activity-row:nth-child(3) { animation-delay: 0.15s; }
                .activity-row:nth-child(4) { animation-delay: 0.2s; }
                .activity-row:nth-child(5) { animation-delay: 0.25s; }
            </style>

            {{-- ═══════════════════════════════════════════
                 ROW 1: KPI Stats Cards
            ═══════════════════════════════════════════ --}}
            <div class="row g-3 mb-4">

                {{-- Total Mahasiswa --}}
                <div class="col-xl-3 col-sm-6 animate-fade-in" style="animation-delay: 0s">
                    <div class="card border-0 h-100 kpi-card" style="background:linear-gradient(135deg,#667eea,#764ba2);background-size:200% 200%;animation:gradient-shift 5s ease infinite;border-radius:16px!important">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white text-sm opacity-8 mb-1 fw-semibold text-uppercase ls-1">Total Mahasiswa</p>
                                <h2 class="text-white fw-bolder mb-0">{{ $totalStudents }}</h2>
                                <small class="text-white opacity-7">terdaftar</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-circle icon-wrapper" style="width:56px;height:56px;background:rgba(255,255,255,.2)">
                                <i class="material-icons text-white" style="font-size:28px">group</i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mahasiswa Aktif --}}
                <div class="col-xl-3 col-sm-6 animate-fade-in" style="animation-delay: 0.1s">
                    <div class="card border-0 h-100 kpi-card" style="background:linear-gradient(135deg,#11998e,#38ef7d);background-size:200% 200%;animation:gradient-shift 5s ease infinite;border-radius:16px!important">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white text-sm opacity-8 mb-1 fw-semibold text-uppercase ls-1">Mahasiswa Aktif</p>
                                <h2 class="text-white fw-bolder mb-0">{{ $activeStudents }}</h2>
                                <small class="text-white opacity-7">7 hari terakhir</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-circle icon-wrapper" style="width:56px;height:56px;background:rgba(255,255,255,.2)">
                                <i class="material-icons text-white" style="font-size:28px">person_outline</i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Materi --}}
                <div class="col-xl-3 col-sm-6 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="card border-0 h-100 kpi-card" style="background:linear-gradient(135deg,#f093fb,#f5576c);background-size:200% 200%;animation:gradient-shift 5s ease infinite;border-radius:16px!important">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white text-sm opacity-8 mb-1 fw-semibold text-uppercase ls-1">Total Materi</p>
                                <h2 class="text-white fw-bolder mb-0">{{ $totalMaterials }}</h2>
                                <small class="text-white opacity-7">materi aktif</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-circle icon-wrapper" style="width:56px;height:56px;background:rgba(255,255,255,.2)">
                                <i class="material-icons text-white" style="font-size:28px">library_books</i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Soal --}}
                <div class="col-xl-3 col-sm-6 animate-fade-in" style="animation-delay: 0.3s">
                    <div class="card border-0 h-100 kpi-card" style="background:linear-gradient(135deg,#4facfe,#00f2fe);background-size:200% 200%;animation:gradient-shift 5s ease infinite;border-radius:16px!important">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white text-sm opacity-8 mb-1 fw-semibold text-uppercase ls-1">Total Soal</p>
                                <h2 class="text-white fw-bolder mb-0">{{ $totalQuestions }}</h2>
                                <small class="text-white opacity-7">soal tersedia</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-circle icon-wrapper" style="width:56px;height:56px;background:rgba(255,255,255,.2)">
                                <i class="material-icons text-white" style="font-size:28px">quiz</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 ROW 2: Charts
            ═══════════════════════════════════════════ --}}
            <div class="row g-3 mb-4">

                {{-- Bar Chart: Completion Rate per Materi --}}
                <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.4s">
                    <div class="card border-0 h-100 chart-container" style="border-radius:16px!important">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 fw-bolder">Completion Rate per Materi</h6>
                                    <p class="text-secondary text-sm mb-0">Persentase soal yang dijawab benar</p>
                                </div>
                                <span class="badge bg-gradient-primary" style="animation: pulse-glow 2s ease-in-out infinite">Chart</span>
                            </div>
                        </div>
                        <div class="card-body pt-3 pb-3">
                            <canvas id="materialChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: TBUT Completion --}}
                <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.5s">
                    <div class="card border-0 h-100 chart-container" style="border-radius:16px!important">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h6 class="mb-0 fw-bolder">Analisis TBUT</h6>
                            <p class="text-secondary text-sm mb-0">Task-Based Usability Testing</p>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <div style="max-width:200px;width:100%;margin-bottom:16px">
                                <canvas id="tbutDonut"></canvas>
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width:10px;height:10px;border-radius:50%;background:#28a745;display:inline-block"></span>
                                        <span class="text-sm">Selesai</span>
                                    </div>
                                    <span class="badge bg-gradient-success">{{ $tbutCompleted }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width:10px;height:10px;border-radius:50%;background:#e9ecef;display:inline-block"></span>
                                        <span class="text-sm">Belum</span>
                                    </div>
                                    <span class="badge bg-gradient-secondary">{{ $tbutTotal - $tbutCompleted }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between text-sm">
                                    <span class="text-secondary">Avg Waktu</span>
                                    <span class="fw-bold">{{ $tbutAvgDur ? gmdate('i:s', intval($tbutAvgDur)) : '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between text-sm mt-1">
                                    <span class="text-secondary">Avg Run Code</span>
                                    <span class="fw-bold">{{ $tbutAvgRun ? round($tbutAvgRun, 1) : '-' }}x</span>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('admin.tbut.index') }}" class="btn btn-sm btn-outline-primary w-100 btn-modern">
                                        <i class="material-icons text-sm me-1" style="vertical-align:middle">open_in_new</i> Detail Analisis
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 ROW 3: Students Table + Popular Materials
            ═══════════════════════════════════════════ --}}
            <div class="row g-3 mb-4">

                {{-- Top Students --}}
                <div class="col-lg-7 animate-fade-in" style="animation-delay: 0.6s">
                    <div class="card border-0" style="border-radius:16px!important">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 fw-bolder">Top 5 Mahasiswa Aktif</h6>
                                    <p class="text-secondary text-sm mb-0">Berdasarkan jumlah soal benar</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive px-3">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mahasiswa</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Soal Benar</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($studentProgress as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3 py-1">
                                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle student-avatar" style="width:36px;height:36px;font-size:14px;background:linear-gradient(135deg,#667eea,#764ba2);flex-shrink:0">
                                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm fw-semibold student-name">{{ $student->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $student->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge bg-gradient-primary">{{ $student->completed_questions }}</span>
                                            </td>
                                            <td class="align-middle" style="min-width:120px">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:6px;border-radius:4px">
                                                        <div class="progress-bar bg-gradient-primary" style="width:{{ min(100, $student->materials_progress) }}%"></div>
                                                    </div>
                                                    <small class="text-secondary fw-semibold text-xs">{{ $student->materials_progress }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-secondary py-4">
                                                <i class="material-icons d-block mb-1">hourglass_empty</i> Belum ada data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Popular Materials --}}
                <div class="col-lg-5 animate-fade-in" style="animation-delay: 0.7s">
                    <div class="card border-0 h-100" style="border-radius:16px!important">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h6 class="mb-0 fw-bolder">Materi Terpopuler</h6>
                            <p class="text-secondary text-sm mb-0">Berdasarkan jumlah pelajar</p>
                        </div>
                        <div class="card-body pt-3">
                            @forelse($popularMaterials as $i => $mat)
                            @php
                                $colors = ['#667eea','#11998e','#f093fb','#4facfe','#f5576c'];
                                $color  = $colors[$i % count($colors)];
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-3 material-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-2 material-rank" style="width:38px;height:38px;background:{{ $color }};flex-shrink:0;font-size:15px">
                                        {{ $i + 1 }}
                                    </div>
                                    <div>
                                        <p class="mb-0 text-sm fw-semibold text-truncate material-title" style="max-width:160px">{{ $mat->title }}</p>
                                        <p class="mb-0 text-xs text-secondary">{{ $mat->students_count }} mahasiswa</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <p class="mb-0 text-sm fw-bold" style="color:{{ $color }}">{{ $mat->completion_rate ?? 0 }}%</p>
                                    <p class="mb-0 text-xs text-secondary">completion</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-secondary py-4">
                                <i class="material-icons d-block mb-1">inbox</i> Belum ada data
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════
                 ROW 4: Recent Activity
            ═══════════════════════════════════════════ --}}
            <div class="row g-3">
                <div class="col-12 animate-fade-in" style="animation-delay: 0.8s">
                    <div class="card border-0" style="border-radius:16px!important">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 px-3 activity-header">
                                <h6 class="text-white mb-0">
                                    <i class="material-icons text-sm me-2" style="vertical-align:middle">history</i>
                                    Aktivitas Terbaru
                                </h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive px-3">
                                <table class="table table-hover align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mahasiswa</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Materi</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProgress as $prog)
                                        <tr class="activity-row">
                                            <td>
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle me-3 activity-avatar" style="width:32px;height:32px;font-size:12px;background:linear-gradient(135deg,#4facfe,#00f2fe);flex-shrink:0">
                                                        {{ strtoupper(substr($prog->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $prog->user->name ?? '-' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $prog->material->title ?? '-' }}</p>
                                                <p class="text-xs text-secondary mb-0">Soal #{{ $prog->question_id }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if($prog->is_correct)
                                                    <span class="badge badge-sm bg-gradient-success"><i class="fas fa-check me-1"></i>Benar</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-danger"><i class="fas fa-times me-1"></i>Salah</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs">{{ $prog->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-secondary py-4">
                                                <i class="material-icons d-block mb-1">inbox</i> Belum ada aktivitas.
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

            <style>
                .activity-header {
                    transition: all 0.3s ease;
                }
                .activity-header:hover {
                    transform: scale(1.02);
                }
                .activity-avatar {
                    transition: all 0.3s ease;
                }
                tr.activity-row:hover .activity-avatar {
                    transform: scale(1.1);
                    box-shadow: 0 4px 15px rgba(79,172,254,0.3);
                }
                .student-avatar {
                    transition: all 0.3s ease;
                }
                tr:hover .student-avatar {
                    transform: scale(1.1) rotate(5deg);
                    box-shadow: 0 5px 15px rgba(102,126,234,0.4);
                }
                .student-name {
                    transition: color 0.2s ease;
                }
                tr:hover .student-name {
                    color: #667eea !important;
                }
                .material-rank {
                    transition: all 0.3s ease;
                }
                .material-item:hover .material-rank {
                    transform: scale(1.1) rotate(-5deg);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                }
                .material-title {
                    transition: color 0.2s ease;
                }
                .material-item:hover .material-title {
                    color: #667eea !important;
                }
                .badge {
                    transition: all 0.2s ease;
                }
                .badge:hover {
                    transform: scale(1.05);
                }
                .progress-bar {
                    transition: width 1s ease-out;
                }
            </style>

        </div>{{-- /container-fluid --}}
    </main>
    <x-admin.tutorial />
</x-layout>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    Chart.defaults.font.family = "'Inter', 'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#7b809a';

    const chartAnimation = {
        duration: 1000,
        easing: 'easeOutQuart'
    };

    const barAnimation = {
        duration: 1500,
        easing: 'easeOutQuart',
        delay: (context) => context.dataIndex * 100
    };

    // ── 1. Bar Chart: Completion Rate per Materi ──────────────────────────
    const matLabels = @json($materialStats->pluck('title'));
    const matRates  = @json($materialStats->pluck('completion_rate'));
    const matCount  = @json($materialStats->pluck('active_students'));

    const gradients = ['#667eea','#11998e','#f093fb','#4facfe','#f5576c','#fa8231','#a29bfe'];
    const bgColors  = matLabels.map((_, i) => gradients[i % gradients.length]);

    const materialChart = new Chart(document.getElementById('materialChart'), {
        type: 'bar',
        data: {
            labels: matLabels,
            datasets: [
                {
                    label: 'Completion Rate (%)',
                    data: matRates,
                    backgroundColor: bgColors.map(c => c + 'cc'),
                    borderColor: bgColors,
                    borderWidth: 2,
                    borderRadius: 8,
                    yAxisID: 'y',
                },
                {
                    label: 'Mahasiswa Aktif',
                    data: matCount,
                    type: 'line',
                    borderColor: '#764ba2',
                    backgroundColor: 'rgba(118,75,162,0.12)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#764ba2',
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: { 
                    borderRadius: 8,
                    padding: 12,
                    backgroundColor: 'rgba(26, 29, 41, 0.95)',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 },
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y;
                                if (context.datasetIndex === 0) label += '%';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Completion Rate (%)' },
                    grid: { color: '#f0f0f0' },
                    ticks: { font: { size: 11 } }
                },
                y2: {
                    position: 'right',
                    beginAtZero: true,
                    title: { display: true, text: 'Mahasiswa Aktif' },
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            },
            animation: {
                ...barAnimation,
                onComplete: function() {
                    const chart = this;
                    chart.data.datasets[1].animation = {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    };
                }
            }
        }
    });

    // ── 2. Donut Chart: TBUT Completion ───────────────────────────────────
    const tbutCompleted = {{ $tbutCompleted }};
    const tbutPending   = {{ $tbutTotal - $tbutCompleted }};

    const tbutDonut = new Chart(document.getElementById('tbutDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Belum Selesai'],
            datasets: [{
                data: [tbutCompleted, tbutPending],
                backgroundColor: [
                    '#11998e',
                    '#e9ecef'
                ],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            cutout: '72%',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    borderRadius: 8,
                    padding: 12,
                    backgroundColor: 'rgba(26, 29, 41, 0.95)',
                    cornerRadius: 8
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200,
                easing: 'easeOutQuart'
            }
        },
        plugins: [{
            id: 'centerText',
            afterDraw(chart) {
                const { ctx, chartArea: { top, bottom, left, right } } = chart;
                const cx = (left + right) / 2;
                const cy = (top + bottom) / 2;
                const total = tbutCompleted + tbutPending;
                const pct = total > 0 ? Math.round((tbutCompleted / total) * 100) : 0;
                ctx.save();
                ctx.font = 'bold 24px Inter, sans-serif';
                ctx.fillStyle = '#344767';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(pct + '%', cx, cy - 8);
                ctx.font = '12px Inter, sans-serif';
                ctx.fillStyle = '#7b809a';
                ctx.fillText('selesai', cx, cy + 14);
                ctx.restore();
            }
        }]
    });

    // Add smooth hover effects
    document.querySelectorAll('.chart-container').forEach(container => {
        container.addEventListener('mouseenter', () => {
            container.style.transform = 'translateY(-2px)';
            container.style.boxShadow = '0 15px 35px rgba(0,0,0,0.15)';
        });
        container.addEventListener('mouseleave', () => {
            container.style.transform = 'translateY(0)';
            container.style.boxShadow = '';
        });
    });
});
</script>
<style>
    #materialChart, #tbutDonut {
        transition: all 0.3s ease;
    }
</style>
@endpush
