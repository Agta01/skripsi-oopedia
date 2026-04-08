@extends('mahasiswa.layouts.app')

@section('title', 'Profil Saya')

@section('content')

@php
    $user      = auth()->user();
    $initials  = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('');
    $joinDate  = \Carbon\Carbon::parse($user->created_at)->locale('id')->isoFormat('D MMMM Y');
@endphp

<div class="pf-wrap">
    <div class="pf-hero">
        <div class="pf-hero__blur"></div>
        <div class="pf-avatar-ring">
            <div class="pf-avatar">{{ $initials }}</div>
        </div>
        <div class="pf-hero__info">
            <h1 class="pf-hero__name">{{ $user->name }}</h1>
            <p class="pf-hero__role"><i class="fas fa-graduation-cap"></i> Mahasiswa</p>
            <p class="pf-hero__join"><i class="fas fa-calendar-alt"></i> Bergabung {{ $joinDate }}</p>
        </div>
    </div>

    {{-- ── ALERT ── --}}
    @if (session('success'))
    <div class="pf-alert pf-alert--success" id="pf-alert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('pf-alert').remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="pf-alert pf-alert--error" id="pf-alert-err">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $errors->first() }}</span>
        <button onclick="document.getElementById('pf-alert-err').remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif

    {{-- ── GRID ── --}}
    <div class="pf-grid">

        {{-- ── KARTU KIRI: Info --}}
        <div class="pf-card">
            <div class="pf-card__header">
                <i class="fas fa-id-card"></i>
                <span>Informasi Akun</span>
            </div>
            <div class="pf-info-list">
                <div class="pf-info-item">
                    <span class="pf-info-item__label"><i class="fas fa-user"></i> Nama</span>
                    <span class="pf-info-item__val">{{ $user->name }}</span>
                </div>
                <div class="pf-info-item">
                    <span class="pf-info-item__label"><i class="fas fa-envelope"></i> Email</span>
                    <span class="pf-info-item__val">{{ $user->email }}</span>
                </div>
                <div class="pf-info-item">
                    <span class="pf-info-item__label"><i class="fas fa-user-tag"></i> Role</span>
                    <span class="pf-info-item__val">
                        <span class="pf-badge">Mahasiswa</span>
                    </span>
                </div>
                <div class="pf-info-item">
                    <span class="pf-info-item__label"><i class="fas fa-calendar"></i> Bergabung</span>
                    <span class="pf-info-item__val">{{ $joinDate }}</span>
                </div>
            </div>
        </div>

        {{-- ── KARTU KANAN: Form edit --}}
        <div class="pf-card pf-card--form">
            <div class="pf-card__header">
                <i class="fas fa-pencil-alt"></i>
                <span>Edit Profil</span>
            </div>

            <form method="POST" action="{{ route('mahasiswa.profile.update') }}" id="profile-form">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="pf-field">
                    <label class="pf-label" for="pf-name">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </label>
                    <input id="pf-name"
                           type="text"
                           name="name"
                           class="pf-input @error('name') pf-input--err @enderror"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Nama lengkap kamu"
                           required>
                    @error('name')
                        <span class="pf-err-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="pf-field">
                    <label class="pf-label" for="pf-email">
                        <i class="fas fa-envelope"></i> Alamat Email
                    </label>
                    <input id="pf-email"
                           type="email"
                           name="email"
                           class="pf-input @error('email') pf-input--err @enderror"
                           value="{{ old('email', $user->email) }}"
                           placeholder="email@contoh.com"
                           required>
                    @error('email')
                        <span class="pf-err-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="pf-divider">
                    <span>Ganti Password <small>(opsional)</small></span>
                </div>

                {{-- Password baru --}}
                <div class="pf-field">
                    <label class="pf-label" for="pf-pass">
                        <i class="fas fa-lock"></i> Password Baru
                    </label>
                    <div class="pf-input-wrap">
                        <input id="pf-pass"
                               type="password"
                               name="password"
                               class="pf-input pf-input--icon @error('password') pf-input--err @enderror"
                               placeholder="Minimal 6 karakter">
                        <button type="button" class="pf-eye" onclick="togglePass('pf-pass', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="pf-hint">Kosongkan jika tidak ingin mengubah password</span>
                    @error('password')
                        <span class="pf-err-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi password --}}
                <div class="pf-field">
                    <label class="pf-label" for="pf-pass-confirm">
                        <i class="fas fa-lock"></i> Konfirmasi Password Baru
                    </label>
                    <div class="pf-input-wrap">
                        <input id="pf-pass-confirm"
                               type="password"
                               name="password_confirmation"
                               class="pf-input pf-input--icon"
                               placeholder="Ulangi password baru">
                        <button type="button" class="pf-eye" onclick="togglePass('pf-pass-confirm', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="pf-btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>

    </div>{{-- /pf-grid --}}
</div>{{-- /pf-wrap --}}

@push('css')
<style>
/* ═══════════════════════════════════════
   PROFILE PAGE STYLES
   Background: putih bersih + aksen biru
═══════════════════════════════════════ */

.pf-wrap {
    padding: 24px 28px 48px;
    display: flex;
    flex-direction: column;
    gap: 24px;
    background: #F8FAFC;
    min-height: calc(100vh - 70px);
}

/* ── Hero ── */
.pf-hero {
    background: linear-gradient(135deg, #004E98 0%, #0074D9 100%);
    border-radius: 20px;
    padding: 36px 40px;
    display: flex;
    align-items: center;
    gap: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,78,152,.22);
}
.pf-hero__blur {
    position: absolute;
    top: -60px; right: -60px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}

/* Avatar ring animation */
.pf-avatar-ring {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    animation: pf-pulse 3s ease-in-out infinite;
    position: relative;
    z-index: 1;
}
.pf-avatar-ring::before {
    content: '';
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    border: 2px dashed rgba(255,255,255,.4);
    animation: pf-spin 12s linear infinite;
}
.pf-avatar {
    width: 76px; height: 76px;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    color: #fff;
    font-size: 28px;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    letter-spacing: 1px;
}
@keyframes pf-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,.2); }
    50%      { box-shadow: 0 0 0 12px rgba(255,255,255,.05); }
}
@keyframes pf-spin {
    to { transform: rotate(360deg); }
}

.pf-hero__info { z-index: 1; }
.pf-hero__name {
    color: #fff; font-size: 24px; font-weight: 800;
    margin: 0 0 6px;
}
.pf-hero__role, .pf-hero__join {
    color: rgba(255,255,255,.75); font-size: 13px;
    margin: 0 0 4px; display: flex; align-items: center; gap: 6px;
}

/* ── Alert ── */
.pf-alert {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 14px;
    font-size: 14px; font-weight: 500;
}
.pf-alert button {
    margin-left: auto; background: transparent; border: none;
    cursor: pointer; font-size: 14px; opacity: .6;
    transition: opacity .2s;
}
.pf-alert button:hover { opacity: 1; }
.pf-alert--success {
    background: rgba(16,185,129,.1); color: #059669;
    border: 1px solid rgba(16,185,129,.25);
}
.pf-alert--error {
    background: rgba(239,68,68,.1); color: #DC2626;
    border: 1px solid rgba(239,68,68,.25);
}

/* ── Grid 2 kolom ── */
.pf-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
}

/* ── Card ── */
.pf-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    padding: 24px;
}
.pf-card__header {
    display: flex; align-items: center; gap: 10px;
    font-size: 15px; font-weight: 700; color: #1E293B;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 2px solid #F1F5F9;
}
.pf-card__header i { color: #004E98; font-size: 16px; }

/* ── Info list ── */
.pf-info-list { display: flex; flex-direction: column; gap: 16px; }
.pf-info-item { display: flex; flex-direction: column; gap: 4px; }
.pf-info-item__label {
    font-size: 11px; font-weight: 600;
    color: #94A3B8; text-transform: uppercase; letter-spacing: .5px;
    display: flex; align-items: center; gap: 6px;
}
.pf-info-item__val {
    font-size: 14px; color: #1E293B; font-weight: 500;
    word-break: break-all;
}
.pf-badge {
    display: inline-flex; align-items: center;
    padding: 3px 12px; border-radius: 99px;
    background: rgba(0,78,152,.08); color: #004E98;
    font-size: 12px; font-weight: 700;
}

/* ── Form fields ── */
.pf-field { margin-bottom: 18px; }
.pf-label {
    display: flex; align-items: center; gap: 7px;
    font-size: 13px; font-weight: 600; color: #374151;
    margin-bottom: 7px;
}
.pf-label i { color: #004E98; font-size: 12px; }

.pf-input {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    font-size: 14px; color: #1E293B;
    background: #FAFBFF;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
}
.pf-input:focus {
    border-color: #0074D9;
    box-shadow: 0 0 0 3px rgba(0,116,217,.12);
    background: #fff;
}
.pf-input--err { border-color: #EF4444 !important; }
.pf-input--icon { padding-right: 44px; }

.pf-input-wrap { position: relative; }
.pf-eye {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    background: transparent; border: none; cursor: pointer;
    color: #94A3B8; font-size: 14px;
    transition: color .2s; padding: 4px;
}
.pf-eye:hover { color: #004E98; }

.pf-hint { font-size: 11px; color: #94A3B8; margin-top: 4px; display: block; }
.pf-err-msg {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; color: #EF4444; margin-top: 5px;
}

/* ── Divider ── */
.pf-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 22px 0 18px;
    color: #94A3B8; font-size: 12px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
}
.pf-divider::before, .pf-divider::after {
    content: ''; flex: 1; height: 1px; background: #E2E8F0;
}
.pf-divider small { font-weight: 400; font-size: 11px; text-transform: lowercase; }

/* ── Submit Button ── */
.pf-btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #004E98 0%, #0074D9 100%);
    color: #fff; border: none;
    padding: 12px 28px; border-radius: 12px;
    font-size: 14px; font-weight: 700;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(0,116,217,.35);
    width: 100%; justify-content: center;
    margin-top: 6px;
}
.pf-btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,116,217,.45);
}

/* ── Responsive ── */
@media (max-width: 860px) {
    .pf-grid { grid-template-columns: 1fr; }
    .pf-hero { flex-direction: column; text-align: center; padding: 28px 20px; }
    .pf-hero__role, .pf-hero__join { justify-content: center; }
    .pf-wrap { padding: 16px; }
}
</style>
@endpush

@push('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush

@endsection