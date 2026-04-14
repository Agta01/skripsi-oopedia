<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="ueq" :userName="$userName" :userRole="$userRole" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="UEQ Survey Results" />
        <div class="container-fluid py-4">

            {{-- ===== STAT CARDS ===== --}}
            <div class="row g-3 mb-4 animate-fade-in-up">
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#0057B8,#003b7d);">
                            <i class="material-icons">group</i>
                        </div>
                        <p class="ueq-stat-label">Responden</p>
                        <h4 class="ueq-stat-value">{{ $surveys->count() }}</h4>
                    </div>
                </div>
                @if($surveys->isNotEmpty())
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#2dce89,#1a9e63);">
                            <i class="material-icons">star</i>
                        </div>
                        <p class="ueq-stat-label">Attractiveness</p>
                        <h4 class="ueq-stat-value">{{ number_format($averages['attractiveness'] ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#fb6340,#d94a28);">
                            <i class="material-icons">visibility</i>
                        </div>
                        <p class="ueq-stat-label">Perspicuity</p>
                        <h4 class="ueq-stat-value">{{ number_format($averages['perspicuity'] ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#f5365c,#b41a3a);">
                            <i class="material-icons">speed</i>
                        </div>
                        <p class="ueq-stat-label">Efficiency</p>
                        <h4 class="ueq-stat-value">{{ number_format($averages['efficiency'] ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#11cdef,#0a9ab8);">
                            <i class="material-icons">verified_user</i>
                        </div>
                        <p class="ueq-stat-label">Dependability</p>
                        <h4 class="ueq-stat-value">{{ number_format($averages['dependability'] ?? 0, 2) }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="ueq-stat-card h-100">
                        <div class="ueq-stat-icon" style="background: linear-gradient(135deg,#8655fc,#5e2fc7);">
                            <i class="material-icons">auto_awesome</i>
                        </div>
                        <p class="ueq-stat-label">Stimulation</p>
                        <h4 class="ueq-stat-value">{{ number_format($averages['stimulation'] ?? 0, 2) }}</h4>
                    </div>
                </div>
                @endif
            </div>

            {{-- ===== CHART + EXPORT PANEL ===== --}}
            @if($surveys->isNotEmpty())
            <div class="row g-3 mb-4 animate-fade-in-up delay-2">
                {{-- Radar Chart --}}
                <div class="col-lg-7">
                    <div class="card modern-card h-100">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex align-items-center modern-header">
                                <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                    <i class="material-icons" style="font-size:22px;color:#0057B8">radar</i>
                                </div>
                                <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Visualisasi 6 Dimensi UEQ</h6>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="min-height:280px">
                            <div style="max-width:340px;width:100%;position:relative">
                                <canvas id="ueqRadarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary + Export --}}
                <div class="col-lg-5 d-flex flex-column gap-3">
                    {{-- Score summary --}}
                    <div class="card modern-card flex-grow-1">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex align-items-center modern-header">
                                <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                    <i class="material-icons" style="font-size:22px;color:#0057B8">bar_chart</i>
                                </div>
                                <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Rata-Rata Dimensi</h6>
                            </div>
                        </div>
                        <div class="card-body pt-3 pb-2 px-4">
                            @php
                            $dimensionMeta = [
                                'attractiveness'  => ['label' => 'Attractiveness',  'color' => '#0057B8'],
                                'perspicuity'     => ['label' => 'Perspicuity',     'color' => '#2dce89'],
                                'efficiency'      => ['label' => 'Efficiency',      'color' => '#fb6340'],
                                'dependability'   => ['label' => 'Dependability',   'color' => '#f5365c'],
                                'stimulation'     => ['label' => 'Stimulation',     'color' => '#11cdef'],
                                'novelty'         => ['label' => 'Novelty',         'color' => '#8655fc'],
                            ];
                            @endphp
                            @foreach($dimensionMeta as $key => $meta)
                            @php $score = $averages[$key] ?? 0; $pct = ($score / 7) * 100; @endphp
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-xs fw-semibold" style="color:#344767">{{ $meta['label'] }}</span>
                                    <span class="text-xs fw-bold" style="color:{{ $meta['color'] }}">{{ number_format($score,2) }}/7</span>
                                </div>
                                <div class="ueq-progress-bar">
                                    <div class="ueq-progress-fill" style="width:{{ $pct }}%;background:{{ $meta['color'] }}"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Export card --}}
                    <div class="card modern-card">
                        <div class="card-body px-4 py-3 d-flex align-items-center gap-3">
                            <div class="icon icon-shape bg-gradient-success shadow d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;border-radius:12px;">
                                <i class="material-icons text-white">download</i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-xs text-muted mb-1">Export data ke CSV</p>
                                <form method="GET" action="{{ route('admin.ueq.export') }}" class="d-flex gap-2 align-items-center flex-wrap">
                                    <select name="class" class="form-select form-select-sm border" style="max-width:160px;border-radius:8px;">
                                        <option value="">Semua Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success mb-0 d-flex align-items-center gap-1" style="border-radius:8px">
                                        <i class="fas fa-file-csv" style="font-size:13px"></i> Export CSV
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ===== UEQ SCORES TABLE ===== --}}
            <div class="row animate-fade-in-up delay-3">
                <div class="col-12">
                    <div class="card modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">assessment</i>
                                    </div>
                                    <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Skor per Responden</h6>
                                </div>
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 me-2" style="border-radius:20px;font-size:12px">
                                    {{ $surveys->count() }} Responden
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            @if($surveys->isEmpty())
                                <div class="text-center py-5">
                                    <i class="material-icons text-muted" style="font-size:48px">inbox</i>
                                    <p class="text-muted mt-2">Belum ada data survey UEQ.</p>
                                </div>
                            @else
                            <div class="table-responsive px-3">
                                <table class="table align-items-center mb-0 ueq-table">
                                    <thead>
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Kelas</th>
                                            <th class="text-center">Attract.</th>
                                            <th class="text-center">Perspic.</th>
                                            <th class="text-center">Effic.</th>
                                            <th class="text-center">Depend.</th>
                                            <th class="text-center">Stimul.</th>
                                            <th class="text-center">Novelty</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($surveys as $survey)
                                        <tr class="ueq-row">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="ueq-avatar">
                                                        {{ strtoupper(substr($survey->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-sm fw-semibold" style="color:#344767">{{ $survey->user->name }}</p>
                                                        <p class="mb-0 text-xs text-muted">{{ $survey->user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="text-xs text-secondary fw-semibold">{{ $survey->nim }}</span></td>
                                            <td><span class="badge ueq-badge-class">{{ $survey->class }}</span></td>
                                            @php
                                            $sc = [
                                                'attract'  => ($survey->annoying_enjoyable + $survey->good_bad + $survey->unlikable_pleasing + $survey->unpleasant_pleasant + $survey->attractive_unattractive + $survey->friendly_unfriendly) / 6,
                                                'perspic'  => ($survey->not_understandable_understandable + $survey->easy_difficult + $survey->complicated_easy + $survey->clear_confusing) / 4,
                                                'effic'    => ($survey->fast_slow + $survey->inefficient_efficient + $survey->impractical_practical + $survey->organized_cluttered) / 4,
                                                'depend'   => ($survey->unpredictable_predictable + $survey->obstructive_supportive + $survey->secure_not_secure + $survey->meets_expectations_does_not_meet) / 4,
                                                'stimul'   => ($survey->valuable_inferior + $survey->boring_exciting + $survey->not_interesting_interesting + $survey->motivating_demotivating) / 4,
                                                'novelty'  => ($survey->creative_dull + $survey->inventive_conventional + $survey->usual_leading_edge + $survey->conservative_innovative) / 4,
                                            ];
                                            @endphp
                                            @foreach($sc as $val)
                                            <td class="text-center">
                                                <span class="ueq-score-chip @if($val >= 5) good @elseif($val >= 3) mid @else low @endif">
                                                    {{ number_format($val, 2) }}
                                                </span>
                                            </td>
                                            @endforeach
                                            <td class="text-center">
                                                <span class="text-xs text-secondary">{{ $survey->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.ueq.detail', $survey->user_id) }}"
                                                   class="btn btn-sm btn-primary ueq-detail-btn mb-0">
                                                    <i class="material-icons" style="font-size:14px">open_in_new</i> Detail
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
            </div>

            {{-- ===== FEEDBACK/KOMENTAR TABLE ===== --}}
            @if($surveys->isNotEmpty())
            <div class="row mt-0 animate-fade-in-up delay-4">
                <div class="col-12">
                    <div class="card modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex align-items-center modern-header">
                                <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                    <i class="material-icons" style="font-size:22px;color:#0057B8">chat_bubble_outline</i>
                                </div>
                                <h6 class="text-white mb-0 fw-semibold" style="letter-spacing:.4px">Komentar &amp; Saran Mahasiswa</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive px-3">
                                <table class="table align-items-center mb-0 ueq-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Kelas</th>
                                            <th>Komentar</th>
                                            <th>Saran</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($surveys as $survey)
                                        <tr class="ueq-row">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="ueq-avatar">
                                                        {{ strtoupper(substr($survey->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm fw-semibold" style="color:#344767">{{ $survey->user->name }}</span>
                                                </div>
                                            </td>
                                            <td><span class="text-xs text-secondary fw-semibold">{{ $survey->nim }}</span></td>
                                            <td><span class="badge ueq-badge-class">{{ $survey->class }}</span></td>
                                            <td>
                                                <p class="text-xs text-secondary mb-0 ueq-comment-text">
                                                    {{ $survey->comments ? Str::limit($survey->comments, 80) : '—' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs text-secondary mb-0 ueq-comment-text">
                                                    {{ $survey->suggestions ? Str::limit($survey->suggestions, 80) : '—' }}
                                                </p>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-xs text-secondary">{{ $survey->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.ueq.detail', $survey->user_id) }}"
                                                   class="btn btn-sm btn-primary ueq-detail-btn mb-0">
                                                    <i class="material-icons" style="font-size:14px">open_in_new</i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </main>
    <x-admin.tutorial />

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($surveys->isNotEmpty())
        const ctx = document.getElementById('ueqRadarChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Attractiveness','Perspicuity','Efficiency','Dependability','Stimulation','Novelty'],
                    datasets: [{
                        label: 'Rata-Rata UEQ',
                        data: [
                            {{ number_format($averages['attractiveness'] ?? 0, 2) }},
                            {{ number_format($averages['perspicuity']    ?? 0, 2) }},
                            {{ number_format($averages['efficiency']     ?? 0, 2) }},
                            {{ number_format($averages['dependability']  ?? 0, 2) }},
                            {{ number_format($averages['stimulation']    ?? 0, 2) }},
                            {{ number_format($averages['novelty']        ?? 0, 2) }}
                        ],
                        backgroundColor: 'rgba(0, 87, 184, 0.15)',
                        borderColor: '#0057B8',
                        borderWidth: 2,
                        pointBackgroundColor: '#0057B8',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            min: 0,
                            max: 7,
                            ticks: { stepSize: 1, font: { size: 10 }, color: '#8392ab' },
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            pointLabels: { font: { size: 11, weight: '600' }, color: '#344767' }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.raw} / 7`
                            }
                        }
                    }
                }
            });
        }
        @endif
    });
    </script>
    @endpush

</x-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    .main-content { font-family: 'Inter', sans-serif; }

    /* ===== Card System ===== */
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

    /* ===== Stat Cards ===== */
    .ueq-stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        padding: 1.25rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        transition: transform .25s ease, box-shadow .25s ease;
        border: 1px solid rgba(0,0,0,0.04);
        margin-top: .25rem;
    }
    .ueq-stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .ueq-stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .ueq-stat-icon i { color: #fff; font-size: 22px; }
    .ueq-stat-label { font-size: 11px; font-weight: 600; color: #8392ab; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
    .ueq-stat-value { font-size: 1.6rem; font-weight: 700; color: #344767; margin: 0; line-height: 1.1; }

    /* ===== Progress Bars ===== */
    .ueq-progress-bar {
        height: 7px; background: #f0f2f5; border-radius: 99px; overflow: hidden;
    }
    .ueq-progress-fill {
        height: 100%; border-radius: 99px; transition: width .8s cubic-bezier(.4,0,.2,1);
    }

    /* ===== Tables ===== */
    .ueq-table thead th {
        font-family: 'Inter', sans-serif;
        text-transform: uppercase;
        font-size: .63rem;
        font-weight: 700;
        letter-spacing: .5px;
        color: #8392ab;
        border-bottom: 2px solid #f0f2f5;
        padding: 1rem .75rem;
        white-space: nowrap;
    }
    .ueq-table tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
        padding: .85rem .75rem;
    }
    .ueq-row { transition: background .15s ease; }
    .ueq-row:hover { background: #f8faff; }

    /* ===== Avatar ===== */
    .ueq-avatar {
        width: 34px; height: 34px; border-radius: 10px;
        background: linear-gradient(135deg, #0057B8, #003b7d);
        color: #fff; font-weight: 700; font-size: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ===== Badge class ===== */
    .ueq-badge-class {
        background: rgba(0,87,184,0.08);
        color: #0057B8;
        border: 1px solid rgba(0,87,184,0.15);
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        padding: .3rem .65rem;
    }

    /* ===== Score chip ===== */
    .ueq-score-chip {
        display: inline-block;
        min-width: 48px;
        text-align: center;
        padding: .25rem .5rem;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }
    .ueq-score-chip.good  { background: rgba(45,206,137,.12); color: #1a9e63; }
    .ueq-score-chip.mid   { background: rgba(251,99,64,.10);  color: #d94a28; }
    .ueq-score-chip.low   { background: rgba(245,54,92,.10);  color: #b41a3a; }

    /* ===== Detail button ===== */
    .ueq-detail-btn {
        background: linear-gradient(135deg,#0057B8,#003b7d);
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        padding: .3rem .75rem;
        display: inline-flex; align-items: center; gap: 4px;
        transition: box-shadow .2s, transform .2s;
        color: #fff !important;
    }
    .ueq-detail-btn:hover { box-shadow: 0 4px 12px rgba(0,87,184,.35); transform: translateY(-1px); }

    /* ===== Comment text ===== */
    .ueq-comment-text { max-width: 240px; line-height: 1.5; }
</style>