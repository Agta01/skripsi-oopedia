<x-layout bodyClass="g-sidenav-show bg-gray-200">
    @push('head')
        <x-head.tinymce-config />
    @endpush

    <x-navbars.sidebar activePage="questions" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Tambah Soal" />
        <div class="container-fluid py-4 px-4">

            {{-- Page Header --}}
            <div class="qs-page-header">
                <div class="qs-page-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div>
                    <h4 class="qs-page-title">Tambah Soal Baru</h4>
                    <div class="qs-page-sub">
                        @if(isset($material))
                            <a href="{{ route('admin.materials.questions.index', $material) }}" class="qs-link">Soal Materi</a>
                            <i class="fas fa-chevron-right" style="font-size:9px;color:#cbd5e1;"></i>
                            <span style="color:#94a3b8;font-size:12px;">{{ Str::limit($material->title, 35) }}</span>
                        @else
                            <a href="{{ route('admin.questions.index') }}" class="qs-link">Kelola Soal</a>
                        @endif
                        <i class="fas fa-chevron-right" style="font-size:9px;color:#cbd5e1;"></i>
                        <span style="color:#94a3b8;font-size:12px;">Tambah Baru</span>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="qs-alert qs-alert-err">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="qs-alert qs-alert-warn">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('warning') }}</div>
            </div>
            @endif

            @if (isset($material))
                <form method="POST" action="{{ route('admin.materials.questions.store', $material) }}" class="qs-form" id="questionForm">
            @else
                <form method="POST" action="{{ route('admin.questions.store') }}" class="qs-form" id="questionForm">
            @endif
            @csrf

            <div class="qs-grid">

                {{-- ═══ MAIN COLUMN ═══ --}}
                <div class="qs-col-main">

                    {{-- Materi + Meta --}}
                    <div class="qs-card">
                        <div class="qs-card-hdr">
                            <div class="qs-card-icon" style="background:#eff6ff;color:#0057B8;"><i class="fas fa-info-circle"></i></div>
                            <h6 class="qs-card-title">Informasi Soal</h6>
                        </div>
                        <div class="qs-card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="qs-label">Materi <span class="text-danger">*</span></label>
                                    @if(isset($material))
                                        <input type="hidden" name="material_id" value="{{ $material->id }}">
                                        <div class="qs-material-chip">
                                            <i class="fas fa-book" style="font-size:12px;color:#0057B8;"></i>
                                            {{ $material->title }}
                                        </div>
                                    @else
                                        <select name="material_id" id="material_id" class="qs-input" required>
                                            <option value="">— Pilih Materi —</option>
                                            @foreach($materials as $mat)
                                                <option value="{{ $mat->id }}">{{ $mat->title }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="qs-label">Tipe Soal <span class="text-danger">*</span></label>
                                    <select name="question_type" class="qs-input" required id="questionTypeSelect">
                                        <option value="fill_in_the_blank">✏️ Fill in the Blank</option>
                                        <option value="radio_button">🔘 Radio Button (Pilihan Ganda)</option>
                                        <option value="drag_and_drop">🔀 Drag and Drop</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="qs-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                    <select name="difficulty" class="qs-input" required>
                                        <option value="beginner">🟢 Beginner</option>
                                        <option value="medium">🟡 Medium</option>
                                        <option value="hard">🔴 Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Question text --}}
                    <div class="qs-card">
                        <div class="qs-card-hdr">
                            <div class="qs-card-icon" style="background:#f0fdf4;color:#059669;"><i class="fas fa-align-left"></i></div>
                            <h6 class="qs-card-title">Teks Pertanyaan</h6>
                        </div>
                        <div class="qs-card-body">
                            <textarea id="content-editor" name="question_text">{{ old('question_text') }}</textarea>
                        </div>
                    </div>

                    {{-- Answers --}}
                    <div class="qs-card">
                        <div class="qs-card-hdr" style="background: linear-gradient(135deg,#4f46e5 0%,#6366f1 100%);">
                            <div class="qs-card-icon" style="background:rgba(255,255,255,.2);color:#fff;"><i class="fas fa-list-ul"></i></div>
                            <h6 class="qs-card-title" style="color:#fff;">Jawaban</h6>
                            <span class="qs-answer-hint" id="answerHint">Pilih satu sebagai jawaban benar</span>
                        </div>
                        <div class="qs-card-body">

                            <div id="answers-container">
                                {{-- Initial single answer --}}
                                <div class="qs-answer-entry" data-index="0">
                                    <div class="qs-answer-number">A</div>
                                    <input type="text" name="answers[0][answer_text]"
                                           class="qs-input qs-answer-input" placeholder="Tulis opsi jawaban…" required>
                                    <input type="hidden" name="answers[0][is_correct]" value="0">
                                    <label class="qs-radio-wrap" title="Tandai sebagai jawaban benar">
                                        <input class="qs-radio" type="radio" name="correct_answer" value="0">
                                        <span class="qs-radio-label">Benar</span>
                                    </label>
                                </div>
                            </div>

                            <button type="button" class="qs-btn-add-answer" id="add-answer-btn" onclick="addAnswer()">
                                <i class="fas fa-plus"></i> Tambah Opsi Jawaban
                            </button>
                        </div>
                    </div>

                </div>{{-- /main col --}}

                {{-- ═══ SIDE COLUMN ═══ --}}
                <div class="qs-col-side">

                    {{-- Type description --}}
                    <div class="qs-card" id="typeInfoCard">
                        <div class="qs-card-hdr">
                            <div class="qs-card-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="fas fa-info"></i></div>
                            <h6 class="qs-card-title">Tentang Tipe Soal</h6>
                        </div>
                        <div class="qs-card-body" id="typeInfoBody">
                            <div class="qs-type-info" id="info-fill_in_the_blank">
                                <div class="qs-type-badge" style="background:#eff6ff;color:#0057B8;">✏️ Fill in the Blank</div>
                                <p class="qs-type-desc">Mahasiswa mengisi jawaban sendiri. Hanya satu kolom jawaban yang disediakan sistem.</p>
                            </div>
                            <div class="qs-type-info" id="info-radio_button" style="display:none;">
                                <div class="qs-type-badge" style="background:#f5f3ff;color:#7c3aed;">🔘 Radio Button</div>
                                <p class="qs-type-desc">Pilihan ganda klasik. Tambahkan minimal 2 opsi dan tandai satu sebagai jawaban benar.</p>
                            </div>
                            <div class="qs-type-info" id="info-drag_and_drop" style="display:none;">
                                <div class="qs-type-badge" style="background:#fef3c7;color:#d97706;">🔀 Drag and Drop</div>
                                <p class="qs-type-desc">Mahasiswa menyusun opsi jawaban dengan menyeret. Urutan jawaban pertama dianggap benar.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="qs-submit-bar">
                        @if(isset($material))
                            <a href="{{ route('admin.materials.questions.index', $material) }}" class="qs-btn-cancel"><i class="fas fa-times"></i> Batal</a>
                        @else
                            <a href="{{ route('admin.questions.index') }}" class="qs-btn-cancel"><i class="fas fa-times"></i> Batal</a>
                        @endif
                        <button type="submit" class="qs-btn-save" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Soal
                        </button>
                    </div>

                    {{-- Tips --}}
                    <div class="qs-card" style="border-color:#fef3c7;">
                        <div class="qs-card-hdr" style="background:#fffbf5;border-bottom-color:#fef3c7;">
                            <div class="qs-card-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-lightbulb"></i></div>
                            <h6 class="qs-card-title">Tips Soal Quali</h6>
                        </div>
                        <div class="qs-card-body" style="padding-top:12px;">
                            <ul class="qs-tips">
                                <li>Gunakan bahasa yang jelas dan tidak ambigu</li>
                                <li>Untuk radio button, minimal 3–4 opsi jawaban</li>
                                <li>Tandai hanya <strong>satu</strong> jawaban benar</li>
                                <li>Tingkat kesulitan mempengaruhi poin mahasiswa</li>
                            </ul>
                        </div>
                    </div>
                </div>{{-- /side col --}}
            </div>

            </form>
        </div>
    </main>

    @push('js')
    <script>
    const LETTERS = ['A','B','C','D','E','F','G','H'];

    function addAnswer() {
        const container = document.getElementById('answers-container');
        const count     = container.querySelectorAll('.qs-answer-entry').length;
        const letter    = LETTERS[count] || count;

        const div = document.createElement('div');
        div.className = 'qs-answer-entry';
        div.dataset.index = count;
        div.innerHTML = `
            <div class="qs-answer-number">${letter}</div>
            <input type="text" name="answers[${count}][answer_text]"
                   class="qs-input qs-answer-input" placeholder="Tulis opsi jawaban…" required>
            <input type="hidden" name="answers[${count}][is_correct]" value="0">
            <label class="qs-radio-wrap" title="Tandai sebagai jawaban benar">
                <input class="qs-radio" type="radio" name="correct_answer" value="${count}">
                <span class="qs-radio-label">Benar</span>
            </label>
            <button type="button" class="qs-btn-remove" onclick="removeAnswer(this)" style="margin-left:4px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function removeAnswer(btn) {
        const entry = btn.closest('.qs-answer-entry');
        const container = document.getElementById('answers-container');
        if (container.querySelectorAll('.qs-answer-entry').length <= 1) return;
        entry.remove();
        // Re-index
        container.querySelectorAll('.qs-answer-entry').forEach((el, i) => {
            el.dataset.index = i;
            el.querySelector('.qs-answer-number').textContent = LETTERS[i] || i;
            el.querySelector('input[type="text"]').name = `answers[${i}][answer_text]`;
            el.querySelector('input[type="hidden"]').name = `answers[${i}][is_correct]`;
            const radio = el.querySelector('input[type="radio"]');
            radio.name = 'correct_answer';
            radio.value = i;
        });
    }

    function handleQuestionTypeChange() {
        const type = document.getElementById('questionTypeSelect').value;
        const container = document.getElementById('answers-container');
        const addBtn = document.getElementById('add-answer-btn');

        // Clear existing
        container.innerHTML = '';

        if (type === 'fill_in_the_blank') {
            addBtn.style.display = 'none';
            addAnswer(); // Only one answer
            // Auto-set first as correct
            const radio = container.querySelector('input[type="radio"]');
            const hidden = container.querySelector('input[type="hidden"]');
            if (radio) radio.checked = true;
            if (hidden) hidden.value = '1';
        } else {
            addBtn.style.display = '';
            addAnswer();
            addAnswer();
        }

        // Update type info cards
        document.querySelectorAll('.qs-type-info').forEach(el => el.style.display = 'none');
        const infoEl = document.getElementById('info-' + type);
        if (infoEl) infoEl.style.display = '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const typeSelect = document.getElementById('questionTypeSelect');
        typeSelect.addEventListener('change', handleQuestionTypeChange);

        // Correct-answer syncing
        document.addEventListener('change', e => {
            if (e.target.type === 'radio' && e.target.name === 'correct_answer') {
                document.querySelectorAll('input[name$="[is_correct]"]').forEach((inp, i) => {
                    inp.value = (i.toString() === e.target.value) ? '1' : '0';
                });
            }
        });

        // Form validation
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const questionText = tinymce.get('content-editor')?.getContent();
            if (!questionText) { alert('Pertanyaan tidak boleh kosong!'); return; }

            const type = document.getElementById('questionTypeSelect').value;
            if (type === 'radio_button') {
                const selected = document.querySelector('input[name="correct_answer"]:checked');
                if (!selected) { alert('Pilih satu jawaban yang benar!'); return; }
            }
            this.submit();
        });

        document.getElementById('submitBtn').addEventListener('click', function(e) {
            setTimeout(() => {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
            }, 0);
        });

        handleQuestionTypeChange();
    });
    </script>
    @endpush
    <x-admin.tutorial />
</x-layout>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

/* Header */
.qs-page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
.qs-page-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(99,102,241,.35);
}
.qs-page-title { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px; }
.qs-page-sub   { display: flex; align-items: center; gap: 6px; font-size: 12px; flex-wrap: wrap; }
.qs-link { color: #6366f1; text-decoration: none; font-weight: 600; }
.qs-link:hover { text-decoration: underline; }

/* Alert */
.qs-alert { display: flex; align-items: flex-start; gap: 12px; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 600; }
.qs-alert-err  { background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; }
.qs-alert-warn { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }

/* Grid */
.qs-grid { display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; }
.qs-col-main, .qs-col-side { display: flex; flex-direction: column; gap: 16px; }

/* Card */
.qs-card { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
.qs-card-hdr { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #f8fafc; background: #fafbfc; }
.qs-card-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.qs-card-title { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; }
.qs-card-body { padding: 18px; }

.qs-answer-hint {
    font-size: 11px; font-weight: 600; color: rgba(255,255,255,.75);
    background: rgba(255,255,255,.15); border-radius: 6px; padding: 3px 10px;
    margin-left: auto;
}

/* Inputs */
.qs-label { display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.qs-input {
    width: 100%; padding: 11px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-size: 13px; color: #1e293b; font-family: 'Inter', sans-serif;
    transition: border-color .18s, box-shadow .18s; background: #fff;
}
.qs-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }

.qs-material-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: #eff6ff; border: 1.5px solid #bfdbfe;
    border-radius: 10px; padding: 10px 16px;
    font-size: 13px; font-weight: 700; color: #1e40af;
    width: 100%;
}

/* Answers */
.qs-answer-entry {
    display: flex; align-items: center; gap: 10px;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 12px; padding: 10px 14px;
    margin-bottom: 10px;
    transition: border-color .18s, box-shadow .18s;
}
.qs-answer-entry:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.08); background: #fff; }
.qs-answer-number {
    width: 28px; height: 28px; border-radius: 8px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff; font-size: 12px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.qs-answer-input { border: none; background: transparent; padding: 0; box-shadow: none; flex: 1; }
.qs-answer-input:focus { box-shadow: none; border: none; }

.qs-radio-wrap { display: flex; align-items: center; gap: 6px; cursor: pointer; flex-shrink: 0; }
.qs-radio { width: 16px; height: 16px; accent-color: #059669; cursor: pointer; }
.qs-radio-label { font-size: 12px; font-weight: 600; color: #64748b; white-space: nowrap; }

.qs-btn-remove {
    width: 28px; height: 28px; border-radius: 8px;
    background: #fef2f2; color: #dc2626; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; cursor: pointer; flex-shrink: 0;
    transition: background .15s;
}
.qs-btn-remove:hover { background: #dc2626; color: #fff; }

.qs-btn-add-answer {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 16px; border-radius: 10px;
    background: #f5f3ff; color: #6366f1;
    border: 1.5px dashed #a5b4fc; font-size: 13px; font-weight: 700;
    cursor: pointer; width: 100%; justify-content: center;
    transition: all .18s;
}
.qs-btn-add-answer:hover { background: #ede9fe; border-color: #6366f1; }

/* Type info */
.qs-type-badge { display: inline-block; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 8px; margin-bottom: 10px; }
.qs-type-desc  { font-size: 12px; color: #64748b; line-height: 1.6; margin: 0; }

/* Submit */
.qs-submit-bar { display: flex; gap: 10px; }
.qs-btn-cancel {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: #f1f5f9; color: #64748b; text-decoration: none; border: none; cursor: pointer;
    transition: background .18s;
}
.qs-btn-cancel:hover { background: #e2e8f0; color: #475569; text-decoration: none; }
.qs-btn-save {
    flex: 2; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(99,102,241,.3); transition: all .18s;
}
.qs-btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }

/* Tips */
.qs-tips { padding-left: 18px; margin: 0; }
.qs-tips li { font-size: 12px; color: #64748b; margin-bottom: 8px; line-height: 1.5; }

@media (max-width: 1024px) {
    .qs-grid { grid-template-columns: 1fr; }
}
</style>