@extends('mahasiswa.layouts.app')

@section('title', auth()->check() ? 'Leaderboard' : 'Peringkat - Login Diperlukan')

@section('content')

{{-- ═══ GUEST LOCK SCREEN ═══ --}}
@guest
<div class="lb-lock-wrap">
    <div class="lb-lock-card">
        {{-- Blurred leaderboard preview --}}
        <div class="lb-blur-preview">
            <div class="lb-blur-row">
                <span class="lb-blur-rank lb-blur-rank--gold">🥇</span>
                <div class="lb-blur-bar" style="width:80%;"></div>
                <div class="lb-blur-score"></div>
            </div>
            <div class="lb-blur-row">
                <span class="lb-blur-rank lb-blur-rank--silver">🥈</span>
                <div class="lb-blur-bar" style="width:65%;"></div>
                <div class="lb-blur-score"></div>
            </div>
            <div class="lb-blur-row">
                <span class="lb-blur-rank lb-blur-rank--bronze">🥉</span>
                <div class="lb-blur-bar" style="width:50%;"></div>
                <div class="lb-blur-score"></div>
            </div>
            <div class="lb-blur-row" style="opacity:.6;">
                <span class="lb-blur-rank">4</span>
                <div class="lb-blur-bar" style="width:40%;"></div>
                <div class="lb-blur-score"></div>
            </div>
            <div class="lb-blur-row" style="opacity:.4;">
                <span class="lb-blur-rank">5</span>
                <div class="lb-blur-bar" style="width:30%;"></div>
                <div class="lb-blur-score"></div>
            </div>
        </div>

        {{-- Lock overlay content --}}
        <div class="lb-lock-overlay">
            <div class="lb-lock-icon-wrap">
                <div class="lb-lock-icon">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="lb-lock-svg">
                        <rect x="10" y="22" width="28" height="22" rx="5" fill="#004E98"/>
                        <path d="M16 22V16a8 8 0 0 1 16 0v6" stroke="#004E98" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="24" cy="33" r="3" fill="white"/>
                        <rect x="22.5" y="33" width="3" height="5" rx="1.5" fill="white"/>
                    </svg>
                </div>
            </div>
            <h2 class="lb-lock-title">Fitur Peringkat Terkunci</h2>
            <p class="lb-lock-desc">
                Halaman <strong>Peringkat</strong> hanya dapat diakses oleh pengguna yang sudah login.<br>
                Masuk atau daftar untuk melihat papan peringkat dan posisi Anda!
            </p>
            <div class="lb-lock-benefits">
                <div class="lb-benefit-item">
                    <i class="fas fa-trophy lb-benefit-icon" style="color:#F59E0B;"></i>
                    <span>Lihat peringkat semua mahasiswa</span>
                </div>
                <div class="lb-benefit-item">
                    <i class="fas fa-chart-line lb-benefit-icon" style="color:#10B981;"></i>
                    <span>Pantau progress & skor Anda</span>
                </div>
                <div class="lb-benefit-item">
                    <i class="fas fa-medal lb-benefit-icon" style="color:#3B82F6;"></i>
                    <span>Dapatkan badge berdasarkan pencapaian</span>
                </div>
            </div>
            <div class="lb-lock-actions">
                <a href="{{ route('login') }}" class="lb-btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" class="lb-btn-register">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
            </div>
        </div>
    </div>
</div>

@endguest

@push('css')
<style>
/* ── Guest lock screen styles ── */
.lb-lock-wrap {
    min-height: calc(100vh - 80px);
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}
.lb-lock-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 8px 40px rgba(0,78,152,.12);
    overflow: hidden;
    width: 100%;
    max-width: 600px;
    position: relative;
}
/* Blurred preview */
.lb-blur-preview {
    padding: 24px 28px 20px;
    filter: blur(6px);
    pointer-events: none;
    user-select: none;
    opacity: .65;
    border-bottom: 1px solid #E2E8F0;
}
.lb-blur-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #F1F5F9;
}
.lb-blur-row:last-child { border-bottom: none; }
.lb-blur-rank {
    font-size: 20px;
    width: 32px;
    text-align: center;
    flex-shrink: 0;
    font-weight: 800;
    color: #94A3B8;
}
.lb-blur-bar {
    height: 14px;
    background: linear-gradient(90deg, #CBD5E1, #E2E8F0);
    border-radius: 99px;
    flex: 1;
}
.lb-blur-score {
    width: 70px;
    height: 14px;
    background: linear-gradient(90deg, #BFDBFE, #DBEAFE);
    border-radius: 99px;
    flex-shrink: 0;
}
/* Lock overlay */
.lb-lock-overlay {
    padding: 32px 36px 36px;
    text-align: center;
}
.lb-lock-icon-wrap {
    margin-bottom: 20px;
}
.lb-lock-icon {
    width: 88px;
    height: 88px;
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    border-radius: 50%;
    border: 3px solid #BFDBFE;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: lb-pulse 2.4s ease-in-out infinite;
}
@keyframes lb-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0,78,152,.15); }
    50%       { box-shadow: 0 0 0 12px rgba(0,78,152,0); }
}
.lb-lock-svg { width: 44px; height: 44px; }
.lb-lock-title {
    font-size: 22px;
    font-weight: 800;
    color: #1E293B;
    margin-bottom: 12px;
}
.lb-lock-desc {
    font-size: 14px;
    color: #64748B;
    line-height: 1.7;
    margin-bottom: 24px;
}
.lb-lock-benefits {
    background: #F8FAFC;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 28px;
    text-align: left;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.lb-benefit-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13.5px;
    color: #334155;
    font-weight: 500;
}
.lb-benefit-icon {
    font-size: 16px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}
.lb-lock-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.lb-btn-login {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #004E98, #0074D9);
    color: #fff;
    text-decoration: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(0,78,152,.3);
    transition: all .2s;
}
.lb-btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,78,152,.4);
    color: #fff;
}
.lb-btn-register {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #004E98;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    border: 2px solid #BFDBFE;
    transition: all .2s;
}
.lb-btn-register:hover {
    background: #EFF6FF;
    border-color: #93C5FD;
    color: #004E98;
    transform: translateY(-2px);
}
@media (max-width: 480px) {
    .lb-lock-overlay { padding: 24px 20px 28px; }
    .lb-lock-actions { flex-direction: column; }
    .lb-btn-login, .lb-btn-register { justify-content: center; }
}
</style>
@endpush

{{-- ═══ AUTHENTICATED LEADERBOARD ═══ --}}
@auth
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4 leaderboard-card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex align-items-center justify-content-between px-3">
                            <div class="d-flex align-items-center">
                                <div class="trophy-icon-container me-3">
                                    <i class="fas fa-trophy trophy-icon"></i>
                                </div>
                                <div>
                                    <h4 class="text-dark text-capitalize mb-0 leaderboard-title">Leaderboard</h4>
                                    <p class="text-dark text-sm mb-0 opacity-8">Peringkat Terbaik Mahasiswa</p>
                                </div>
                            </div>
                            <div class="leaderboard-decoration">
                                <span class="medal-badge medal-gold">🥇</span>
                                <span class="medal-badge medal-silver">🥈</span>
                                <span class="medal-badge medal-bronze">🥉</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body px-0 pb-2">    
                    <div class="podium-wrapper mb-5">
                        <div class="podium-display">
                            <div class="podium-item second-place">
                                @if(isset($leaderboardData[1]) && $leaderboardData[1]->total_correct_questions > 0)
                                    <div class="player-avatar">
                                        <span class="medal-badge">🥈</span>
                                        <h5 class="player-name">{{ $leaderboardData[1]->name }}</h5>
                                        <span class="level-badge level-{{ $leaderboardData[1]->badge_color }}">{{ $leaderboardData[1]->badge }}</span>
                                        <div class="score-display">{{ $leaderboardData[1]->formatted_score }} poin</div>
                                    </div>
                                    <div class="podium-base second">2</div>
                                @endif
                            </div>

                            <div class="podium-item first-place">
                                @if(isset($leaderboardData[0]) && $leaderboardData[0]->total_correct_questions > 0)
                                    <i class="fas fa-crown crown-icon"></i>
                                    <div class="player-avatar">
                                        <span class="medal-badge">🥇</span>
                                        <h5 class="player-name">{{ $leaderboardData[0]->name }}</h5>
                                        <span class="level-badge level-{{ $leaderboardData[0]->badge_color }}">{{ $leaderboardData[0]->badge }}</span>
                                        <div class="score-display">{{ $leaderboardData[0]->formatted_score }} poin</div>
                                    </div>
                                    <div class="podium-base first">1</div>
                                @endif
                            </div>

                            <div class="podium-item third-place">
                                @if(isset($leaderboardData[2]) && $leaderboardData[2]->total_correct_questions > 0)
                                    <div class="player-avatar">
                                        <span class="medal-badge">🥉</span>
                                        <h5 class="player-name">{{ $leaderboardData[2]->name }}</h5>
                                        <span class="level-badge level-{{ $leaderboardData[2]->badge_color }}">{{ $leaderboardData[2]->badge }}</span>
                                        <div class="score-display">{{ $leaderboardData[2]->formatted_score }} poin</div>
                                    </div>
                                    <div class="podium-base third">3</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive p-0 mx-3">
                        <div class="animated-border-table">
                            <table class="table leaderboard-table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-3">
                                            <i class="fas fa-medal me-2"></i>PERINGKAT
                                        </th>
                                        <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-3">
                                            <i class="fas fa-user me-2"></i>MAHASISWA
                                        </th>
                                        <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-3">
                                            <i class="fas fa-star me-2"></i>LEVEL
                                        </th>
                                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">
                                            <i class="fas fa-calendar-check me-2"></i>TANGGAL SELESAI
                                        </th>
                                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">
                                            <i class="fas fa-chart-line me-2"></i>PROGRESS
                                        </th>
                                        <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-3">
                                            <i class="fas fa-dollar-sign me-2"></i>SKOR
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboardData as $data)
                                    <tr class="leaderboard-row @if($data->id === auth()->id()) highlight-row @endif">
                                        <td>
                                            <div class="d-flex px-3 py-2 justify-content-center">
                                                @if($data->rank <= 3)
                                                    <div class="top-rank rank-{{ $data->rank }}">{{ $data->rank }}</div>
                                                @else
                                                    <span class="font-weight-bold">{{ $data->rank }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-2">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $data->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-2 py-2">
                                                <span class="level-badge level-{{ $data->badge_color }}">{{ $data->badge }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="completion-date {{ $data->completion_date ? 'completed' : 'not-completed' }}">
                                                <i class="fas fa-{{ $data->completion_date ? 'calendar-check' : 'hourglass-half' }}"></i>
                                                {{ $data->completion_date ? date('d M Y', strtotime($data->completion_date)) : 'Belum selesai' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="progress-wrapper mx-auto">
                                                <div class="progress leaderboard-progress">
                                                    <div class="progress-bar bg-gradient-{{ $data->badge_color }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $data->percentage }}%" 
                                                         aria-valuenow="{{ $data->percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="text-sm text-center mt-1">{{ $data->percentage }}%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-2 py-2">
                                                <span class="score-badge">{{ $data->formatted_score }} poin</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3 mb-3 px-3">
                            {{ $leaderboardData->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="{{ asset('css/mahasiswa.css') }}" rel="stylesheet">
<style>
    /* Reset default badge styles */
    .level-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.375rem;
        text-align: center;
    }
    
    /* Define specific badge colors */
    .level-secondary {
        background-color: #6c757d !important;
        color: white !important;
    }
    
    .level-success {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .level-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .level-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }
    
    /* Override any conflicting styles */
    .podium-item .level-badge {
        margin-top: 5px;
    }
    
    /* Style untuk badge skor */
    .score-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 0.375rem;
        background-color: #3498db;
        color: white;
    }
    
    /* Style untuk skor di podium */
    .score-display {
        margin-top: 5px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        background-color: rgba(255, 255, 255, 0.7);
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-block;
    }
    
    /* Skor pada peringkat pertama */
    .first-place .score-display {
        background-color: rgba(255, 215, 0, 0.3);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfeti untuk peringkat teratas
    document.addEventListener('DOMContentLoaded', function() {
        @if($currentUserRank && $currentUserRank->rank <= 3)
            // Konfeti untuk peringkat 1-3
            const colors = [
                ['#004e98', '#0074d9'], // Dark blue - peringkat 1
                ['#0074d9', '#3498db'], // Medium blue - peringkat 2
                ['#3498db', '#4fc3f7']  // Light blue - peringkat 3
            ];
            
            const selectedColors = colors[{{ $currentUserRank->rank - 1 }}];
            
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: selectedColors,
                startVelocity: 30,
                gravity: 0.5,
                ticks: 200,
                shapes: ['square', 'circle'],
                zIndex: 1000
            });
        @endif
    });

    function showFeedback(result, score, attemptNumber) {
        // Kode yang sudah dimodifikasi di atas
    }
</script>
@endpush
@endauth

@endsection