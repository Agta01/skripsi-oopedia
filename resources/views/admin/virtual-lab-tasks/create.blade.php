<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name"
        :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Tambah Tugas Virtual Lab" />
        <div class="container-fluid py-4 px-4">

            <form action="{{ route('admin.virtual-lab-tasks.store') }}" method="POST" enctype="multipart/form-data" id="taskForm">
                @csrf

                <div class="vlt-form-grid">

                    {{-- ═══ MAIN COLUMN ═══ --}}
                    <div class="vlt-col-main">

                        {{-- Basic Info --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#eff6ff;color:#0057B8"><i class="fas fa-info-circle"></i></div>
                                <h6 class="vlt-section-title">Informasi Dasar</h6>
                            </div>
                            <div class="vlt-section-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="vlt-label">Materi Sub-Bab <span class="text-danger">*</span></label>
                                        <select name="material_id" class="vlt-input" required>
                                            <option value="">— Pilih Materi —</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="vlt-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                        <select name="difficulty" class="vlt-input" required>
                                            <option value="beginner"     {{ old('difficulty') == 'beginner'     ? 'selected' : '' }}>🟢 Beginner</option>
                                            <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                            <option value="advanced"     {{ old('difficulty') == 'advanced'     ? 'selected' : '' }}>🔴 Advanced</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="vlt-label">Judul Tugas <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="vlt-input" placeholder="Contoh: Membuat Pola Bintang Segitiga" value="{{ old('title') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#f0fdf4;color:#059669"><i class="fas fa-align-left"></i></div>
                                <h6 class="vlt-section-title">Deskripsi &amp; Instruksi</h6>
                            </div>
                            <div class="vlt-section-body">
                                <label class="vlt-label">Instruksi tugas yang ditampilkan ke mahasiswa</label>
                                <textarea name="description" class="form-control tinymce" rows="10">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Template Code --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fas fa-code"></i></div>
                                <h6 class="vlt-section-title">Template Code</h6>
                                <span class="vlt-section-badge">Starter kode untuk mahasiswa</span>
                            </div>
                            <div class="vlt-section-body">
                                <textarea name="template_code" class="vlt-code" rows="12">{{ old('template_code', "public class Main {\n    public static void main(String[] args) {\n        // Tulis kode di sini\n    }\n}") }}</textarea>
                            </div>
                        </div>

                        {{-- Expected Output --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#f0fdf4;color:#059669"><i class="fas fa-terminal"></i></div>
                                <h6 class="vlt-section-title">Expected Output</h6>
                                <span class="vlt-section-badge">Kunci jawaban terminal</span>
                            </div>
                            <div class="vlt-section-body">
                                <label class="vlt-label">Output terminal yang dianggap benar (opsional)</label>
                                <small class="vlt-hint">Sistem mencocokkan output secara otomatis, tidak case-sensitive, spasi ekstra diabaikan.</small>
                                <textarea name="expected_output" class="vlt-code vlt-code-sm" rows="5" placeholder="Hello World!">{{ old('expected_output') }}</textarea>
                            </div>
                        </div>

                    </div>{{-- /main col --}}

                    {{-- ═══ SIDE COLUMN ═══ --}}
                    <div class="vlt-col-side">

                        {{-- Deadline --}}
                        <div class="vlt-section vlt-section-deadline">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#fff7ed;color:#d97706"><i class="fas fa-hourglass-half"></i></div>
                                <h6 class="vlt-section-title">Deadline</h6>
                            </div>
                            <div class="vlt-section-body">
                                <label class="vlt-label">Batas waktu pengerjaan</label>
                                <small class="vlt-hint">Saat waktu habis, sistem <strong>otomatis submit</strong> kode mahasiswa. Kosongkan jika tidak ada batas waktu.</small>
                                <div class="vlt-deadline-input-row">
                                    <input type="number" name="deadline_minutes" class="vlt-input"
                                           min="1" max="480" placeholder="60"
                                           value="{{ old('deadline_minutes') }}"
                                           id="deadlineInput"
                                           oninput="updateDeadlinePreview()">
                                    <span class="vlt-deadline-unit">menit</span>
                                </div>
                                <div id="deadlinePreview" class="vlt-deadline-preview" style="display:none;"></div>
                                <div class="vlt-deadline-presets">
                                    <span style="font-size:11px;color:#94a3b8;font-weight:600;">Preset cepat:</span>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(30)">30m</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(60)">1j</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(90)">1j30m</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(120)">2j</button>
                                </div>
                            </div>
                        </div>

                        {{-- Expected Result Image --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#fdf4ff;color:#9333ea"><i class="fas fa-image"></i></div>
                                <h6 class="vlt-section-title">Gambar Expected Result</h6>
                            </div>
                            <div class="vlt-section-body">
                                <label class="vlt-label">Contoh visual hasil (opsional)</label>
                                <small class="vlt-hint">JPG, PNG, GIF, WebP. Maks 2MB.</small>
                                <div class="vlt-upload-zone" id="uploadZone" onclick="document.getElementById('expected_result_image').click()">
                                    <input type="file" name="expected_result_image" id="expected_result_image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    <div id="uploadPlaceholder">
                                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:#c4b5fd;margin-bottom:8px;"></i>
                                        <div style="font-size:13px;color:#7c3aed;font-weight:600;">Klik untuk upload gambar</div>
                                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">atau drag &amp; drop di sini</div>
                                    </div>
                                    <div id="image-preview-container" style="display:none;">
                                        <img id="image-preview" src="" alt="Preview" style="max-height:180px;border-radius:8px;object-fit:contain;">
                                        <div style="font-size:11px;color:#94a3b8;margin-top:6px;">Klik untuk ganti gambar</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Test Cases (hidden/collapsed) --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header" style="cursor:pointer;" onclick="toggleTestCases()">
                                <div class="vlt-section-icon" style="background:#f0f9ff;color:#0284c7"><i class="fas fa-vial"></i></div>
                                <h6 class="vlt-section-title">Test Cases</h6>
                                <span class="vlt-section-badge">Opsional</span>
                                <i class="fas fa-chevron-down ms-auto" id="testCasesChevron" style="color:#94a3b8;font-size:13px;transition:transform .2s;"></i>
                            </div>
                            <div id="testCasesBody" class="vlt-section-body" style="display:none;">
                                <small class="vlt-hint">Format JSON: <code>[{"input": "5", "output": "25"}]</code></small>
                                <textarea name="test_cases" class="vlt-code vlt-code-sm" rows="4" placeholder='[{"input": "5", "output": "25"}]'>{{ old('test_cases') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="vlt-submit-bar">
                            <a href="{{ route('admin.virtual-lab-tasks.index') }}" class="vlt-btn-cancel">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="vlt-btn-save">
                                <i class="fas fa-save"></i> Simpan Tugas
                            </button>
                        </div>

                    </div>{{-- /side col --}}
                </div>
            </form>
        </div>
    </main>

@push('js')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('image-preview-container').style.display = 'block';
        document.getElementById('image-preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function updateDeadlinePreview() {
    const mins = parseInt(document.getElementById('deadlineInput').value) || 0;
    const preview = document.getElementById('deadlinePreview');
    if (mins < 1) { preview.style.display = 'none'; return; }
    const h = Math.floor(mins / 60), m = mins % 60;
    preview.style.display = 'block';
    preview.innerHTML = `⏱️ <strong>${h > 0 ? h+'j ' : ''}${m > 0 ? m+'m' : ''}</strong> — mahasiswa harus menyelesaikan dalam waktu ini`;
}

function setDeadline(mins) {
    document.getElementById('deadlineInput').value = mins;
    updateDeadlinePreview();
}

function toggleTestCases() {
    const body = document.getElementById('testCasesBody');
    const chev = document.getElementById('testCasesChevron');
    const isHidden = body.style.display === 'none';
    body.style.display = isHidden ? 'block' : 'none';
    chev.style.transform = isHidden ? 'rotate(180deg)' : '';
}
</script>
@endpush

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

/* ── Grid ── */
.vlt-form-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
.vlt-col-main, .vlt-col-side { display: flex; flex-direction: column; gap: 16px; }

/* ── Section Card ── */
.vlt-section {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    overflow: hidden;
}
.vlt-section-header {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px;
    border-bottom: 1px solid #f8fafc;
    background: #fafbfc;
}
.vlt-section-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.vlt-section-title { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; }
.vlt-section-badge {
    font-size: 11px; font-weight: 600; color: #64748b;
    background: #f1f5f9; border-radius: 6px; padding: 2px 8px;
}
.vlt-section-body { padding: 18px; }

/* ── Deadline section highlight ── */
.vlt-section-deadline { border: 1.5px solid #fed7aa; }
.vlt-section-deadline .vlt-section-header { background: #fffbf5; border-bottom-color: #fed7aa; }

/* ── Inputs ── */
.vlt-label {
    display: block; font-size: 12px; font-weight: 700;
    color: #374151; margin-bottom: 6px; letter-spacing: .2px;
}
.vlt-hint {
    display: block; font-size: 11px; color: #94a3b8;
    margin-bottom: 8px; line-height: 1.5;
}
.vlt-input {
    width: 100%; padding: 10px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-size: 13px; color: #1e293b;
    transition: border-color .18s, box-shadow .18s;
    background: #fff;
}
.vlt-input:focus { border-color: #0057B8; box-shadow: 0 0 0 3px rgba(0,87,184,.08); }

.vlt-code {
    width: 100%; padding: 12px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 13px; color: #1e293b;
    background: #0d1117; color: #c9d1d9;
    resize: vertical; line-height: 1.7;
    transition: border-color .18s;
}
.vlt-code:focus { border-color: #6366f1; }
.vlt-code-sm { font-size: 12px; }

/* ── Deadline ── */
.vlt-deadline-input-row {
    display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.vlt-deadline-unit { font-size: 13px; font-weight: 600; color: #d97706; flex-shrink: 0; }
.vlt-deadline-preview {
    font-size: 12px; color: #92400e; background: #fff7ed;
    border: 1px solid #fed7aa; border-radius: 8px;
    padding: 8px 12px; margin-bottom: 10px; line-height: 1.5;
}
.vlt-deadline-presets { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.vlt-preset {
    font-size: 11px; font-weight: 700; color: #d97706;
    background: #fff7ed; border: 1px solid #fed7aa;
    border-radius: 6px; padding: 4px 10px; cursor: pointer;
    transition: all .15s;
}
.vlt-preset:hover { background: #d97706; color: #fff; border-color: #d97706; }

/* ── Upload zone ── */
.vlt-upload-zone {
    border: 2px dashed #c4b5fd; border-radius: 12px;
    background: #fdf4ff; padding: 24px;
    text-align: center; cursor: pointer;
    transition: all .2s;
}
.vlt-upload-zone:hover { border-color: #9333ea; background: #f5f3ff; }

/* ── Submit bar ── */
.vlt-submit-bar {
    display: flex; gap: 10px; padding: 4px 0;
}
.vlt-btn-cancel {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: #f1f5f9; color: #64748b;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .18s;
}
.vlt-btn-cancel:hover { background: #e2e8f0; color: #475569; text-decoration: none; }
.vlt-btn-save {
    flex: 2; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #0057B8, #003b7d); color: #fff;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(0,87,184,.3);
    transition: all .18s;
}
.vlt-btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,87,184,.4); }

/* Responsive */
@media (max-width: 1024px) {
    .vlt-form-grid { grid-template-columns: 1fr; }
}
</style>
</x-layout>