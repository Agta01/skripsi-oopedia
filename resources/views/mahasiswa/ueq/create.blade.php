@extends('mahasiswa.layouts.app')

@section('title', 'UEQ Survey')

@section('content')
<div class="ueq-survey-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <!-- Header Section -->
            <div class="ueq-header text-center mb-4">
                <div class="ueq-logo mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="OOPEDIA Logo" style="height: 70px;">
                </div>
                <h2 class="ueq-title">User Experience Questionnaire</h2>
                <p class="ueq-subtitle text-muted">
                    Bantu kami meningkatkan kualitas OOPEDIA dengan memberikan penilaian Anda
                </p>
            </div>

            <form id="ueqForm" method="POST" action="{{ route('mahasiswa.ueq.store') }}">
                @csrf
                
                <!-- Warning Alert -->
                <div class="alert alert-info ueq-warning-alert mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle me-3" style="font-size: 1.5rem; margin-top: 2px;"></i>
                        <div>
                            <h5 class="alert-heading mb-2">
                                <strong>Perhatian!</strong>
                            </h5>
                            <p class="mb-2">
                                Anda <strong>wajib mengisi semua 26 pertanyaan</strong> sebelum dapat mengirimkan survei ini.
                            </p>
                            <ul class="mb-0 ps-3">
                                <li>Setiap pertanyaan memiliki skala penilaian dari 1 sampai 7</li>
                                <li>Pilih angka yang paling sesuai dengan pengalaman Anda</li>
                                <li>Tombol submit akan aktif setelah semua pertanyaan dijawab</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Identity Card -->
                <div class="ueq-card mb-4">
                    <div class="ueq-card-header">
                        <i class="fas fa-user-circle me-2"></i>
                        <span>Identitas Responden</span>
                    </div>
                    <div class="ueq-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="ueq-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="ueq-input @error('nim') is-invalid @enderror" 
                                    id="nim" name="nim" value="{{ old('nim') }}" 
                                    placeholder="Masukkan NIM" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="ueq-label">Nama Lengkap</label>
                                <input type="text" class="ueq-input ueq-input-readonly" 
                                    value="{{ auth()->user()->name }}" readonly>
                                <small class="text-muted">Diambil dari profil Anda</small>
                            </div>
                            <div class="col-md-4">
                                <label class="ueq-label">Kelas <span class="text-danger">*</span></label>
                                <input type="text" class="ueq-input @error('class') is-invalid @enderror" 
                                    id="class" name="class" value="{{ old('class') }}" 
                                    placeholder="Contoh: SIB-2E" required>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Section -->
                @php
                    $questions = [
                        ['name' => 'annoying_enjoyable', 'left' => 'Menyebalkan', 'right' => 'Menyenangkan'],
                        ['name' => 'not_understandable_understandable', 'left' => 'Sulit dipahami', 'right' => 'Mudah dipahami'],
                        ['name' => 'creative_dull', 'left' => 'Kreatif', 'right' => 'Monoton'],
                        ['name' => 'easy_difficult', 'left' => 'Mudah', 'right' => 'Sulit'],
                        ['name' => 'valuable_inferior', 'left' => 'Bermanfaat', 'right' => 'Kurang bermanfaat'],
                        ['name' => 'boring_exciting', 'left' => 'Membosankan', 'right' => 'Menarik'],
                        ['name' => 'not_interesting_interesting', 'left' => 'Tidak menarik', 'right' => 'Menarik'],
                        ['name' => 'unpredictable_predictable', 'left' => 'Tidak dapat diprediksi', 'right' => 'Dapat diprediksi'],
                        ['name' => 'fast_slow', 'left' => 'Cepat', 'right' => 'Lambat'],
                        ['name' => 'inventive_conventional', 'left' => 'Inovatif', 'right' => 'Konvensional'],
                        ['name' => 'obstructive_supportive', 'left' => 'Menghambat', 'right' => 'Mendukung'],
                        ['name' => 'good_bad', 'left' => 'Baik', 'right' => 'Buruk'],
                        ['name' => 'complicated_easy', 'left' => 'Rumit', 'right' => 'Sederhana'],
                        ['name' => 'unlikable_pleasing', 'left' => 'Tidak disukai', 'right' => 'Menyenangkan'],
                        ['name' => 'usual_leading_edge', 'left' => 'Biasa saja', 'right' => 'Terdepan'],
                        ['name' => 'unpleasant_pleasant', 'left' => 'Tidak menyenangkan', 'right' => 'Menyenangkan'],
                        ['name' => 'secure_not_secure', 'left' => 'Aman', 'right' => 'Tidak aman'],
                        ['name' => 'motivating_demotivating', 'left' => 'Memotivasi', 'right' => 'Tidak memotivasi'],
                        ['name' => 'meets_expectations_does_not_meet', 'left' => 'Memenuhi ekspektasi', 'right' => 'Tidak sesuai ekspektasi'],
                        ['name' => 'inefficient_efficient', 'left' => 'Tidak efisien', 'right' => 'Efisien'],
                        ['name' => 'clear_confusing', 'left' => 'Jelas', 'right' => 'Membingungkan'],
                        ['name' => 'impractical_practical', 'left' => 'Tidak praktis', 'right' => 'Praktis'],
                        ['name' => 'organized_cluttered', 'left' => 'Terorganisir', 'right' => 'Berantakan'],
                        ['name' => 'attractive_unattractive', 'left' => 'Menarik', 'right' => 'Tidak menarik'],
                        ['name' => 'friendly_unfriendly', 'left' => 'Ramah', 'right' => 'Tidak ramah'],
                        ['name' => 'conservative_innovative', 'left' => 'Konservatif', 'right' => 'Inovatif'],
                    ];
                @endphp

                <div class="ueq-card mb-4">
                    <div class="ueq-card-header">
                        <i class="fas fa-star me-2"></i>
                        <span>Penilaian Aspek ({{ count($questions) }} Item)</span>
                    </div>
                    <div class="ueq-card-body p-0">
                        @foreach($questions as $index => $q)
                        <div class="ueq-question {{ $errors->has($q['name']) || (session('missingFields') && in_array($q['name'], session('missingFields'))) ? 'ueq-question-error' : '' }}" 
                             data-question="{{ $q['name'] }}" id="q-{{ $q['name'] }}">
                            <div class="ueq-question-number">{{ $index + 1 }}</div>
                            <div class="ueq-question-content">
                                <div class="ueq-question-labels">
                                    <span class="ueq-label-left">{{ $q['left'] }}</span>
                                    <span class="ueq-label-right">{{ $q['right'] }}</span>
                                </div>
                                <div class="ueq-rating-scale">
                                    @for($i = 1; $i <= 7; $i++)
                                    <label class="ueq-rating-item">
                                        <input type="radio" name="{{ $q['name'] }}" value="{{ $i }}" 
                                               {{ old($q['name']) == $i ? 'checked' : '' }} required>
                                        <span class="ueq-rating-circle">{{ $i }}</span>
                                    </label>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Feedback Section -->
                <div class="ueq-card mb-4">
                    <div class="ueq-card-header">
                        <i class="fas fa-comment-dots me-2"></i>
                        <span>Masukan Tambahan</span>
                    </div>
                    <div class="ueq-card-body">
                        <div class="mb-3">
                            <label class="ueq-label">Komentar <span class="text-danger">*</span></label>
                            <textarea class="ueq-textarea @error('comments') is-invalid @enderror" 
                                id="comments" name="comments" rows="4" required
                                placeholder="Bagikan pengalaman Anda menggunakan OOPEDIA...">{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="ueq-label">Saran Perbaikan <span class="text-danger">*</span></label>
                            <textarea class="ueq-textarea @error('suggestions') is-invalid @enderror" 
                                id="suggestions" name="suggestions" rows="4" required
                                placeholder="Apa yang bisa kami tingkatkan?">{{ old('suggestions') }}</textarea>
                            @error('suggestions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mb-5">
                    <button type="submit" class="ueq-submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i>
                        <span id="submitText">Kirim Survey</span>
                    </button>
                    <div class="ueq-progress-info mt-3" id="progressInfo">
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            <span id="progressText">0 dari 26 pertanyaan terjawab</span>
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
/* UEQ Survey Modern Styles */
.ueq-survey-wrapper {
    padding: 2rem 0;
    max-width: 100%;
}

.ueq-header {
    margin-bottom: 2rem;
}

.ueq-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.ueq-subtitle {
    font-size: 1rem;
    color: #64748b;
}

/* Warning Alert */
.ueq-warning-alert {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
    border: 2px solid #3b82f6;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
}

.ueq-warning-alert .alert-heading {
    color: #1e40af;
    font-size: 1.1rem;
}

.ueq-warning-alert p {
    color: #1e3a8a;
    font-size: 0.95rem;
}

.ueq-warning-alert ul {
    color: #1e40af;
    font-size: 0.9rem;
}

.ueq-warning-alert ul li {
    margin-bottom: 0.25rem;
}

.ueq-warning-alert i {
    color: #3b82f6;
}

/* Card Styles */
.ueq-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.ueq-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.ueq-card-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.ueq-card-body {
    padding: 1.5rem;
}

/* Form Inputs */
.ueq-label {
    display: block;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.ueq-input, .ueq-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.ueq-input:focus, .ueq-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.ueq-input-readonly {
    background-color: #f8fafc;
    cursor: not-allowed;
}

.ueq-textarea {
    resize: vertical;
    min-height: 100px;
}

/* Question Styles */
.ueq-question {
    display: flex;
    padding: 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
}

.ueq-question:last-child {
    border-bottom: none;
}

.ueq-question:hover {
    background-color: #f8fafc;
}

.ueq-question-error {
    background-color: #fef2f2;
    border-left: 4px solid #ef4444;
}

.ueq-question-number {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 1.5rem;
    font-size: 0.9rem;
}

.ueq-question-content {
    flex: 1;
}

.ueq-question-labels {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.ueq-label-left {
    color: #ef4444;
}

.ueq-label-right {
    color: #10b981;
}

/* Rating Scale */
.ueq-rating-scale {
    display: flex;
    justify-content: space-between;
    gap: 0.5rem;
    position: relative;
}

.ueq-rating-scale::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 5%;
    right: 5%;
    height: 2px;
    background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%);
    transform: translateY(-50%);
    z-index: 0;
    opacity: 0.3;
}

.ueq-rating-item {
    flex: 1;
    display: flex;
    justify-content: center;
    cursor: pointer;
    position: relative;
    z-index: 1;
}

.ueq-rating-item input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.ueq-rating-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #64748b;
    transition: all 0.2s ease;
    user-select: none;
}

.ueq-rating-item:hover .ueq-rating-circle {
    border-color: #3b82f6;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
}

.ueq-rating-item input[type="radio"]:checked + .ueq-rating-circle {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-color: #2563eb;
    color: white;
    transform: scale(1.15);
    box-shadow: 0 6px 12px rgba(37, 99, 235, 0.4);
}

/* Submit Button */
.ueq-submit-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.ueq-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.ueq-submit-btn:active {
    transform: translateY(0);
}

.ueq-submit-btn:disabled {
    background: #94a3b8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.ueq-progress-info {
    margin-top: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .ueq-question {
        flex-direction: column;
    }
    
    .ueq-question-number {
        margin-bottom: 1rem;
        margin-right: 0;
    }
    
    .ueq-question-labels {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .ueq-rating-circle {
        width: 38px;
        height: 38px;
        font-size: 0.9rem;
    }
    
    .ueq-rating-scale {
        gap: 0.25rem;
    }
}

/* Animation for error highlighting */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.ueq-question-shake {
    animation: shake 0.5s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ueqForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const progressText = document.getElementById('progressText');
    const questions = document.querySelectorAll('.ueq-question');
    const totalQuestions = 26;

    // Update progress
    function updateProgress() {
        let answered = 0;
        questions.forEach(q => {
            const name = q.dataset.question;
            const checked = document.querySelector(`input[name="${name}"]:checked`);
            if (checked) {
                answered++;
                q.classList.remove('ueq-question-error');
            }
        });

        progressText.textContent = `${answered} dari ${totalQuestions} pertanyaan terjawab`;
        
        if (answered === totalQuestions) {
            submitBtn.disabled = false;
            submitText.textContent = 'Kirim Survey';
        } else {
            submitBtn.disabled = true;
            submitText.textContent = `Lengkapi ${totalQuestions - answered} pertanyaan lagi`;
        }
    }

    // Add change listeners
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', updateProgress);
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        let unanswered = [];
        questions.forEach(q => {
            const name = q.dataset.question;
            const checked = document.querySelector(`input[name="${name}"]:checked`);
            if (!checked) {
                unanswered.push(q);
                q.classList.add('ueq-question-error', 'ueq-question-shake');
                setTimeout(() => q.classList.remove('ueq-question-shake'), 500);
            }
        });

        if (unanswered.length > 0) {
            e.preventDefault();
            unanswered[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Show alert using SweetAlert if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Lengkap',
                    text: `Masih ada ${unanswered.length} pertanyaan yang belum dijawab.`,
                    confirmButtonColor: '#3b82f6'
                });
            }
            return false;
        }
    });

    // Initialize
    updateProgress();

    // Scroll to first error on page load if any
    @if($errors->any() || session('missingFields'))
        setTimeout(() => {
            const firstError = document.querySelector('.ueq-question-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 500);
    @endif
});
</script>
@endpush