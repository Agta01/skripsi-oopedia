@extends('mahasiswa.layouts.app')

@section('title', 'Level Soal - ' . $material->title)

@push('css')
    <style>
        /* CSS Modern untuk memastikan badge dan text terbaca sempurna */
        .level-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .level-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 180px;
        }

        .level-card:hover {
            transform: translateY(-5px);
        }

        .completed-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
        }

        /* Custom Themes directly in CSS to bypass Tailwind JIT purging issues */
        .theme-0-bg {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
        }

        .theme-0-border:hover {
            border-color: #60a5fa !important;
        }

        .theme-0-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%);
        }

        .theme-1-bg {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            border: 1px solid #fecdd3;
        }

        .theme-1-border:hover {
            border-color: #fb7185 !important;
        }

        .theme-1-btn {
            background: linear-gradient(135deg, #f43f5e 0%, #db2777 100%);
        }

        .theme-2-bg {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
        }

        .theme-2-border:hover {
            border-color: #fbbf24 !important;
        }

        .theme-2-btn {
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
        }

        .theme-3-bg {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
            border: 1px solid #e9d5ff;
        }

        .theme-3-border:hover {
            border-color: #c084fc !important;
        }

        .theme-3-btn {
            background: linear-gradient(135deg, #a855f7 0%, #c026d3 100%);
        }

        .theme-4-bg {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border: 1px solid #99f6e4;
        }

        .theme-4-border:hover {
            border-color: #2dd4bf !important;
        }

        .theme-4-btn {
            background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
        }

        /* ─── Completed Card Redesign ─── */
        .completed-done-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%) !important;
            border: 2px solid #86efac !important;
            opacity: 0.92;
        }

        .completed-ribbon {
            position: absolute;
            top: 0;
            right: 0;
            overflow: hidden;
            width: 90px;
            height: 90px;
            z-index: 2;
            pointer-events: none;
        }
        .completed-ribbon span {
            position: absolute;
            top: 18px;
            right: -20px;
            display: block;
            width: 110px;
            padding: 4px 0;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
            text-align: center;
            transform: rotate(45deg);
            box-shadow: 0 2px 8px rgba(21,128,61,.3);
        }

        .completed-check-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #16a34a, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            box-shadow: 0 4px 16px rgba(21,128,61,.35);
        }

        .completed-done-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(21,128,61,.1);
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid rgba(21,128,61,.2);
            margin-top: 6px;
        }</style>
@endpush

@section('content')
    <div class="container-fluid py-4 pb-5">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
            <div class="mb-3 mb-md-0">
                <h1 class="h3 fw-bold text-gray-800 mb-1">Level Soal: {{ $material->title }}</h1>
                <p class="text-muted mb-0"><i class="fas fa-map-signs me-1"></i>Pilih kotak level yang terbuka untuk mulai
                    tantangan.</p>
            </div>
            <div>
                <form method="GET" action="{{ route('mahasiswa.materials.questions.levels', $material) }}"
                    class="d-flex align-items-center bg-white border border-gray-200 rounded-pill px-3 py-2 shadow-sm">
                    <i class="fas fa-filter text-indigo-500 me-2 ps-1"></i>
                    <select name="difficulty"
                        class="form-select border-0 bg-transparent shadow-none fw-medium text-gray-700 py-0 pe-4"
                        onchange="this.form.submit()" style="cursor: pointer; min-width: 130px; font-size: 0.95rem;">
                        <option value="beginner" {{ $difficulty == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="medium" {{ $difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ $difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Info Alert -->
        @if(auth()->check() && auth()->user()->role_id === 3)
            <div class="alert bg-white border shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 tw-border-blue-100">
                <div class="tw-bg-blue-50 tw-text-blue-600 rounded-circle d-flex align-items-center justify-content-center me-3"
                    style="width: 40px; height: 40px; min-width: 40px;">
                    <i class="fas fa-info-circle fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 tw-text-blue-900">Sistem Penilaian Leaderboard</h6>
                    <p class="mb-0 text-sm text-gray-600">Skor Anda sangat bergantung pada <b>jumlah percobaan (steps)</b> yang
                        dihabiskan untuk menjawab benar. Berpikirlah matang-matang sebelum mengklik "Periksa Jawaban" untuk
                        memastikan skor optimal!</p>
                </div>
            </div>
        @endif

        <!-- Grid Level Cards -->
        <div class="row g-4 mt-2">
            @foreach($levels as $index => $level)
                @php
                    $themes = [
                        ['badge' => 'bg-primary'],
                        ['badge' => 'bg-danger'],
                        ['badge' => 'bg-warning text-dark'],
                        ['badge' => 'bg-dark'],
                        ['badge' => 'bg-info'],
                    ];
                    $themeIdx = $index % count($themes);
                    $theme = $themes[$themeIdx];
                @endphp
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    @if($level['status'] === 'locked')
                        <!-- LOCKED CARD -->
                        <div
                            class="card h-100 level-card theme-{{ $themeIdx }}-bg rounded-4 shadow-sm position-relative opacity-75">
                            <span class="badge bg-dark rounded-pill level-badge px-2 py-1 text-uppercase fw-semibold"><i
                                    class="fas fa-lock me-1"></i>{{ $level['difficulty'] }}</span>
                            <div
                                class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center pt-5">
                                <div
                                    class="tw-w-16 tw-h-16 tw-bg-white shadow-sm tw-text-gray-400 rounded-circle d-flex align-items-center justify-content-center mb-3">
                                    <span class="fs-4 fw-bold">{{ $level['level'] }}</span>
                                </div>
                                <h5 class="fw-bold text-gray-500 mb-0">Terkunci</h5>
                            </div>
                        </div>
                    @elseif($level['status'] === 'completed')
                        <!-- COMPLETED CARD — non-clickable, clearly done -->
                        <div class="d-block h-100" style="cursor:default;">
                            <div class="card h-100 level-card completed-card rounded-4 shadow-sm position-relative completed-done-card" style="pointer-events:none;">
                                <!-- Ribbon "Selesai" -->
                                <div class="completed-ribbon">
                                    <span><i class="fas fa-check me-1"></i>Selesai</span>
                                </div>
                                <span class="badge bg-success rounded-pill level-badge px-2 py-1 text-uppercase fw-semibold" style="right:auto;left:15px;">
                                    <i class="fas fa-check-double me-1"></i>{{ $level['difficulty'] }}
                                </span>
                                <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center pt-5">
                                    <div class="completed-check-circle mb-3">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1" style="color:#15803d;">Soal {{ $level['level'] }}</h5>
                                    <span class="completed-done-badge">
                                        <i class="fas fa-lock me-1" style="font-size:10px;"></i>Sudah Dikerjakan
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- UNLOCKED CARD -->
                        <a href="{{ route('mahasiswa.materials.questions.show', ['material' => $material->id, 'question' => $level['question_id'], 'difficulty' => $difficulty]) }}"
                            class="text-decoration-none d-block h-100">
                            <div
                                class="card h-100 level-card theme-{{ $themeIdx }}-bg theme-{{ $themeIdx }}-border rounded-4 shadow-sm position-relative">
                                <!-- Pulse effect -->
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="tw-flex tw-h-3 tw-w-3 position-relative">
                                        <span
                                            class="tw-animate-ping tw-absolute tw-inline-flex tw-h-full tw-w-full tw-rounded-full tw-bg-indigo-400 tw-opacity-75"></span>
                                        <span
                                            class="tw-relative tw-inline-flex tw-rounded-full tw-h-3 tw-w-3 tw-bg-indigo-500"></span>
                                    </span>
                                </div>
                                <span
                                    class="badge {{ $theme['badge'] }} bg-gradient rounded-pill level-badge px-2 py-1 text-uppercase fw-semibold"
                                    style="right: auto; left: 15px;">{{ $level['difficulty'] }}</span>
                                <div
                                    class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center pt-5">
                                    <div
                                        class="tw-w-16 tw-h-16 theme-{{ $themeIdx }}-btn tw-text-white shadow rounded-circle d-flex align-items-center justify-content-center mb-3">
                                        <i class="fas fa-play fs-4 ps-1"></i>
                                    </div>
                                    <h5 class="fw-bold text-gray-800 mb-1">Mulai Soal {{ $level['level'] }}</h5>
                                    <p class="text-xs text-indigo-500 mb-0 fw-medium">Klik untuk bermain</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            @endforeach

            <!-- Indicator Selesai (Bukan Kartu) -->
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-center justify-content-center" style="min-height: 200px;">
                @php 
                    $allCompleted = count(array_filter($levels, function ($l) {
                        return $l['status'] !== 'completed'; 
                    })) === 0; 
                @endphp
                
                @if($allCompleted)
                    <!-- COMPLETED ALL STATE: Flat Text Indicator -->
                    <div class="text-center" style="cursor: default; opacity: 0.9;">
                        <div class="mb-3">
                            <i class="fas fa-trophy" style="font-size: 3.5rem; color: #fbbf24; filter: drop-shadow(0 4px 6px rgba(251,191,36,0.3));"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #d97706; letter-spacing: 0.5px;">SEMUA SELESAI</h5>
                        <p class="text-muted mb-0 fw-medium" style="font-size: 0.85rem;">Kamu luar biasa!</p>
                    </div>
                @else
                    <!-- NOT COMPLETED ALL STATE: Flat Text Indicator -->
                    <div class="text-center" style="cursor: default; opacity: 0.4;">
                        <div class="mb-3">
                            <i class="fas fa-trophy" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                        </div>
                        <h6 class="fw-bold mb-0" style="color: #94a3b8; letter-spacing: 0.5px;">BELUM SELESAI</h6>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-5 text-center px-3">
            <hr class="tw-border-gray-200 mb-4">
            <a href="{{ route('mahasiswa.materials.index') }}"
                class="btn btn-light bg-white border border-gray-300 shadow-sm px-4 py-2 fw-medium text-gray-700 rounded-pill hover:tw-bg-gray-50 transition-all d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Materi
            </a>
        </div>
    </div>

    <style>
        @keyframes float {
            0% {
                transform: translatey(0px);
            }

            50% {
                transform: translatey(-10px);
            }

            100% {
                transform: translatey(0px);
            }
        }
    </style>
@endsection