<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="questions" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="{{ $material ? 'Soal untuk '.$material->title : 'Semua Soal' }}" />
        <div class="container-fluid py-4">
            <!-- Search Form -->
            <form method="GET" action="{{ $material ? route('admin.materials.questions.index', $material) : route('admin.questions.index') }}" class="mb-4">
                <div class="row align-items-center bg-white p-3 border-radius-xl shadow-sm mx-0 modern-filter-container">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <div class="input-group input-group-outline bg-light rounded-pill px-3 py-2 d-flex align-items-center modern-search-bar" style="border: 1px solid #e0e6ed;">
                            <span class="input-group-text border-0 bg-transparent pe-2" style="color: #0057B8;">
                                <i class="material-icons text-md">search</i>
                            </span>
                            <div class="d-flex flex-grow-1 align-items-center position-relative">
                                <label class="form-label mb-0 text-muted d-none">Cari berdasarkan soal...</label>
                                <input type="text" name="search" class="form-control border-0 bg-transparent px-2 flex-grow-1 shadow-none" placeholder="Cari soal atau pembuat..." value="{{ request('search') }}" style="outline: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group input-group-outline bg-light rounded-pill px-3 py-2 modern-search-bar d-flex align-items-center" style="border: 1px solid #e0e6ed;">
                            <i class="material-icons text-md text-secondary me-2">tune</i>
                            <select name="difficulty" class="form-control border-0 bg-transparent shadow-none px-2" style="cursor: pointer; appearance: none; -webkit-appearance: none; outline: none;">
                                <option value="">Semua Tingkat Kesulitan</option>
                                <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary rounded-pill w-100 mb-0 py-2 d-flex align-items-center justify-content-center modern-btn-filter" type="submit" style="background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%); letter-spacing: 0.5px; height: 42px;">
                            <i class="material-icons text-sm me-2">filter_alt</i> Terapkan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Questions Table -->    
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card my-4 modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center px-4">
                                    <div class="icon icon-shape bg-white text-center border-radius-md shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="material-icons opacity-10" style="font-size: 24px; color: #0057B8 !important;">quiz</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 modern-title" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px;">
                                        {{ $material ? 'Soal: ' . Str::limit($material->title, 40) : 'Daftar Semua Soal' }}
                                    </h6>
                                </div>
                                @if($material)
                                    <a href="{{ route('admin.materials.questions.create', $material) }}" class="btn btn-light rounded-pill px-4 me-4 modern-btn-add py-2 text-primary font-weight-bold d-flex align-items-center gap-1">
                                        <i class="material-icons text-sm">add</i> Tambah Soal
                                    </a>
                                @else
                                    <a href="{{ route('admin.questions.create') }}" class="btn btn-light rounded-pill px-4 me-4 modern-btn-add py-2 text-primary font-weight-bold d-flex align-items-center gap-1">
                                        <i class="material-icons text-sm">add</i> Tambah Soal
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body px-4 pb-4 pt-4 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            @forelse($questions as $question)
                            <div class="card mb-4 shadow-sm border-0 modern-question-card" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
                                <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom border-light gap-3">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="badge border border-primary text-primary px-3 py-2" style="background-color: rgba(0, 87, 184, 0.05); border-radius: 8px;">
                                            <i class="material-icons text-sm me-1 align-middle">menu_book</i> {{ Str::limit($question->material->title, 35) }}
                                        </span>
                                        <span class="badge modern-difficulty-badge modern-difficulty-{{ strtolower($question->difficulty) }}">
                                            {{ ucfirst($question->difficulty) }}
                                        </span>
                                        <span class="badge bg-light text-secondary border px-3 py-2" style="border-radius: 8px;">
                                            <i class="material-icons text-sm align-middle me-1">category</i> {{ $question->formatted_type }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($material)
                                            <a href="{{ route('admin.materials.questions.edit', ['material' => $material, 'question' => $question]) }}" class="btn btn-sm btn-outline-info mb-0 d-flex align-items-center px-3 rounded-pill modern-btn-outline" data-bs-toggle="tooltip" title="Edit Soal">
                                                <i class="material-icons text-sm me-1">edit</i> Edit
                                            </a>
                                            <form action="{{ route('admin.materials.questions.destroy', ['material' => $material, 'question' => $question]) }}" method="POST" class="d-inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger mb-0 d-flex align-items-center px-3 rounded-pill modern-btn-outline" data-bs-toggle="tooltip" title="Hapus Soal" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                                    <i class="material-icons text-sm me-1">delete</i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-info mb-0 d-flex align-items-center px-3 rounded-pill modern-btn-outline" data-bs-toggle="tooltip" title="Edit Soal">
                                                <i class="material-icons text-sm me-1">edit</i> Edit
                                            </a>
                                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger mb-0 d-flex align-items-center px-3 rounded-pill modern-btn-outline" data-bs-toggle="tooltip" title="Hapus Soal" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                                    <i class="material-icons text-sm me-1">delete</i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body p-4 bg-white">
                                    <!-- Question Text -->
                                    <div class="mb-4 text-dark fs-6 modern-question-text" style="line-height: 1.7; font-size: 15px;">
                                        {!! $question->question_text !!}
                                    </div>
                                    
                                    <!-- Answers Grid -->
                                    <h6 class="text-xs font-weight-bolder text-uppercase mb-3 text-muted" style="letter-spacing: 0.5px;">Pilihan Jawaban</h6>
                                    <div class="row g-3">
                                        @foreach($question->answers as $answer)
                                        <div class="col-md-6 col-xl-3">
                                            <div class="p-3 border rounded-3 h-100 position-relative modern-answer-block {{ $answer->is_correct ? 'is-correct border-success' : 'is-incorrect border-light' }}">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="answer-icon mt-1">
                                                        @if($answer->is_correct)
                                                            <i class="material-icons text-success fs-5">check_circle</i>
                                                        @else
                                                            <i class="material-icons text-secondary opacity-5 fs-5">radio_button_unchecked</i>
                                                        @endif
                                                    </div>
                                                    <div class="answer-content">
                                                        <span class="text-sm d-block {{ $answer->is_correct ? 'text-success fw-bold' : 'text-secondary' }}" style="line-height: 1.5;">
                                                            {{ $answer->answer_text }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer bg-light py-3 px-4 border-top border-light d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-gradient-info me-2 rounded-circle shadow-sm d-flex align-items-center justify-content-center text-white font-weight-bold" style="width: 28px; height: 28px; font-size: 12px;">
                                            {{ strtoupper(substr($question->createdBy->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-secondary">Dibuat oleh <strong class="text-dark">{{ $question->createdBy->name }}</strong></span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 bg-white border-radius-lg">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-8">
                                    <div class="icon-shape bg-light rounded-circle mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                        <i class="material-icons text-secondary" style="font-size: 40px;">quiz</i>
                                    </div>
                                    <h5 class="text-dark mb-1 font-weight-bolder">Belum ada soal pada materi ini</h5>
                                    <p class="text-secondary text-sm mb-4">Tambahkan soal baru agar muncul di daftar ini.</p>
                                    @if($material)
                                        <a href="{{ route('admin.materials.questions.create', $material) }}" class="btn btn-primary rounded-pill px-5 shadow-sm py-2">
                                            <i class="material-icons text-sm me-1">add</i> Tambah Soal Baru
                                        </a>
                                    @else
                                        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary rounded-pill px-5 shadow-sm py-2">
                                            <i class="material-icons text-sm me-1">add</i> Tambah Soal Baru
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for displaying full question -->
        <div class="modal fade" id="fullQuestionModal" tabindex="-1" aria-labelledby="fullQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fullQuestionModalLabel">Detail Pertanyaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="fullQuestionContent">
                        <!-- Question content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        // Store questions data for use in JavaScript
        const questionsData = [
            @foreach($questions as $q)
                {
                    id: {{ $q->id }},
                    text: {!! json_encode($q->question_text) !!}
                },
            @endforeach
        ];
        
        function viewFullQuestion(questionId) {
            // Find the question by ID
            const question = questionsData.find(q => q.id === questionId);
            
            if (question) {
                // Set the modal content
                document.getElementById('fullQuestionContent').innerHTML = question.text;
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('fullQuestionModal'));
                modal.show();
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    @endpush
    <x-admin.tutorial />

</x-layout>

<style>
    /* Modern UI Refinements */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .main-content {
        font-family: 'Inter', sans-serif;
    }

    /* Cards & Containers */
    .modern-card {
        border: none;
        box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        background: #ffffff;
        overflow: visible;
        margin-top: 3rem !important;
    }
    
    .modern-filter-container {
        border-radius: 16px !important;
        border: 1px solid #f0f2f5;
    }

    /* Headers */
    .modern-header {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        box-shadow: 0 8px 25px -8px rgba(0, 87, 184, 0.5) !important;
        border-radius: 16px;
        position: relative;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }
    
    .modern-title {
        font-family: 'Inter', sans-serif;
    }

    .modern-btn-add {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }

    .modern-btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        background-color: #f8f9fa;
        color: #003b7d !important;
    }
    
    .modern-btn-filter {
        box-shadow: 0 4px 10px rgba(0, 87, 184, 0.2);
        transition: all 0.3s ease;
    }
    .modern-btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(0, 87, 184, 0.3);
    }

    /* Search Bar */
    .modern-search-bar {
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .modern-search-bar:focus-within {
        border-color: #0057B8 !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 87, 184, 0.15) !important;
    }

    /* Table & Rows */
    .modern-table th {
        font-family: 'Inter', sans-serif;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.5px;
        color: #8392ab;
        border-bottom: 2px solid #f0f2f5;
        padding-top: 1.5rem !important;
        padding-bottom: 1rem !important;
    }

    .modern-table td {
        border-bottom: 1px solid #f0f2f5;
    }

    .modern-row {
        transition: all 0.2s ease;
    }

    .modern-row:hover {
        background-color: #f8faff;
    }
    
    .modern-row:hover + .answers-row {
        background-color: #f8faff;
    }
    
    .answers-row {
        transition: all 0.2s ease;
    }

    .modern-text-primary {
        color: #344767;
        font-family: 'Inter', sans-serif;
    }

    .modern-text-description {
        color: #67748e;
        line-height: 1.5;
        font-weight: 400;
        font-size: 13px !important;
    }
    
    .modern-read-more {
        transition: color 0.2s;
    }
    .modern-read-more:hover {
        color: #003b7d !important;
    }

    /* Badges */
    .modern-difficulty-badge {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        letter-spacing: 0.3px;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
    }
    
    .modern-difficulty-beginner {
        background-color: rgba(45, 206, 137, 0.15);
        color: #2dce89;
        border: 1px solid rgba(45, 206, 137, 0.2);
    }
    
    .modern-difficulty-medium {
        background-color: rgba(251, 99, 64, 0.15);
        color: #fb6340;
        border: 1px solid rgba(251, 99, 64, 0.2);
    }
    
    .modern-difficulty-hard {
        background-color: rgba(245, 54, 92, 0.15);
        color: #f5365c;
        border: 1px solid rgba(245, 54, 92, 0.2);
    }

    /* Answer Blocks (Grid View) */
    .modern-answer-block {
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }
    
    .modern-answer-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .modern-answer-block.is-correct {
        background-color: #f0fdf4 !important;
    }
    
    .modern-answer-block.is-correct:hover {
        box-shadow: 0 4px 12px rgba(45, 206, 137, 0.15);
    }
    
    /* Modern Question Cards */
    .modern-question-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    .modern-question-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06) !important;
    }
    
    .modern-question-text img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 10px 0;
    }
    
    .modern-btn-outline {
        border-width: 1.5px;
        background: transparent;
        transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .btn-outline-info.modern-btn-outline {
        color: #0057b8;
        border-color: #0057b8;
    }
    .btn-outline-info.modern-btn-outline:hover {
        background-color: #0057b8;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 87, 184, 0.3);
        transform: translateY(-2px);
    }

    .btn-outline-danger.modern-btn-outline {
        color: #f5365c;
        border-color: #f5365c;
    }
    .btn-outline-danger.modern-btn-outline:hover {
        background-color: #f5365c;
        color: #fff;
        box-shadow: 0 4px 10px rgba(245, 54, 92, 0.3);
        transform: translateY(-2px);
    }
</style>
