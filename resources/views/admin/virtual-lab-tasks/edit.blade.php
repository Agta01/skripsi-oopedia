<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Edit Tugas Virtual Lab" />
        <div class="container-fluid py-4 px-4">

            {{-- Breadcrumb --}}
            <div class="vlt-breadcrumb">
                <a href="{{ route('admin.virtual-lab-tasks.index') }}">Kelola Tugas Lab</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit: {{ Str::limit($virtualLabTask->title, 40) }}</span>
            </div>

            <form action="{{ route('admin.virtual-lab-tasks.update', $virtualLabTask->id) }}" method="POST" enctype="multipart/form-data" id="taskForm">
                @csrf
                @method('PUT')

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
                                                <option value="{{ $material->id }}" {{ $virtualLabTask->material_id == $material->id ? 'selected' : '' }}>{{ $material->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="vlt-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                        <select name="difficulty" class="vlt-input" required>
                                            <option value="beginner"     {{ $virtualLabTask->difficulty == 'beginner'     ? 'selected' : '' }}>🟢 Beginner</option>
                                            <option value="intermediate" {{ $virtualLabTask->difficulty == 'intermediate' ? 'selected' : '' }}>🟡 Intermediate</option>
                                            <option value="advanced"     {{ $virtualLabTask->difficulty == 'advanced'     ? 'selected' : '' }}>🔴 Advanced</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="vlt-label">Judul Tugas <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="vlt-input"
                                               value="{{ old('title', $virtualLabTask->title) }}" required>
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
                                <textarea name="description" class="form-control tinymce" rows="10">{{ old('description', $virtualLabTask->description) }}</textarea>
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
                                <textarea name="template_code" class="vlt-code" rows="12">{{ old('template_code', $virtualLabTask->template_code) }}</textarea>
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
                                <small class="vlt-hint">Sistem mencocokkan secara otomatis, tidak case-sensitive, spasi ekstra diabaikan.</small>
                                <textarea name="expected_output" class="vlt-code vlt-code-sm" rows="5"
                                          placeholder="Hello World!">{{ old('expected_output', $virtualLabTask->expected_output) }}</textarea>
                            </div>
                        </div>

                    </div>{{-- /main col --}}

                    {{-- ═══ SIDE COLUMN ═══ --}}
                    <div class="vlt-col-side">

                        {{-- TBUT Stats chip --}}
                        @php
                            $sessCount  = $virtualLabTask->tbutSessions->count();
                            $doneCount  = $virtualLabTask->tbutSessions->where('is_completed', true)->count();
                            $avgSecs    = $virtualLabTask->tbutSessions->where('is_completed', true)->avg('duration_seconds');
                        @endphp
                        @if($sessCount > 0)
                        <div class="vlt-stats-chip-row">
                            <div class="vlt-stats-chip vlt-chip-blue">
                                <i class="fas fa-users"></i>
                                <div>
                                    <div class="vlt-chip-num">{{ $sessCount }}</div>
                                    <div class="vlt-chip-lbl">Sesi</div>
                                </div>
                            </div>
                            <div class="vlt-stats-chip vlt-chip-green">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <div class="vlt-chip-num">{{ $doneCount }}</div>
                                    <div class="vlt-chip-lbl">Selesai</div>
                                </div>
                            </div>
                            @if($avgSecs)
                            <div class="vlt-stats-chip vlt-chip-purple">
                                <i class="fas fa-stopwatch"></i>
                                <div>
                                    <div class="vlt-chip-num">{{ gmdate('i:s', round($avgSecs)) }}</div>
                                    <div class="vlt-chip-lbl">Rata waktu</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Deadline --}}
                        <div class="vlt-section vlt-section-deadline">
                            <div class="vlt-section-header">
                                <div class="vlt-section-icon" style="background:#fff7ed;color:#d97706"><i class="fas fa-hourglass-half"></i></div>
                                <h6 class="vlt-section-title">Deadline</h6>
                                @if($virtualLabTask->deadline_minutes)
                                <span class="vlt-badge-deadline-active">Aktif</span>
                                @endif
                            </div>
                            <div class="vlt-section-body">
                                <label class="vlt-label">Batas waktu pengerjaan</label>
                                <small class="vlt-hint">Saat waktu habis, sistem <strong>otomatis submit</strong> kode mahasiswa. Kosongkan jika tidak ada batas waktu.</small>
                                <div class="vlt-deadline-input-row">
                                    <input type="number" name="deadline_minutes" class="vlt-input"
                                           min="1" max="480"
                                           placeholder="60"
                                           value="{{ old('deadline_minutes', $virtualLabTask->deadline_minutes) }}"
                                           id="deadlineInput"
                                           oninput="updateDeadlinePreview()">
                                    <span class="vlt-deadline-unit">menit</span>
                                </div>
                                <div id="deadlinePreview" class="vlt-deadline-preview" style="{{ $virtualLabTask->deadline_minutes ? '' : 'display:none;' }}">
                                    @if($virtualLabTask->deadline_minutes)
                                    ⏱️ <strong>{{ intdiv($virtualLabTask->deadline_minutes,60) > 0 ? intdiv($virtualLabTask->deadline_minutes,60).'j ' : '' }}{{ $virtualLabTask->deadline_minutes%60 > 0 ? ($virtualLabTask->deadline_minutes%60).'m' : '' }}</strong>
                                    — mahasiswa harus menyelesaikan dalam waktu ini
                                    @endif
                                </div>
                                <div class="vlt-deadline-presets">
                                    <span style="font-size:11px;color:#94a3b8;font-weight:600;">Preset:</span>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(30)">30m</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(60)">1j</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(90)">1j30m</button>
                                    <button type="button" class="vlt-preset" onclick="setDeadline(120)">2j</button>
                                    <button type="button" class="vlt-preset vlt-preset-clear" onclick="setDeadline(0)">Hapus</button>
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
                                @if($virtualLabTask->expected_result_image)
                                <div class="vlt-existing-img" id="existingImgBox">
                                    <img src="{{ asset('storage/' . $virtualLabTask->expected_result_image) }}" alt="Current" style="max-height:140px;border-radius:8px;object-fit:contain;width:100%;">
                                    <div class="vlt-existing-img-overlay" onclick="document.getElementById('removeImage').click()">
                                        <label for="removeImage" style="cursor:pointer;">
                                            <input type="checkbox" name="remove_expected_image" value="1" id="removeImage" class="d-none" onchange="toggleRemoveImg(this)">
                                            <i class="fas fa-trash"></i> Hapus Gambar
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <div class="vlt-upload-zone mt-2" id="uploadZone" onclick="document.getElementById('expected_result_image').click()">
                                    <input type="file" name="expected_result_image" id="expected_result_image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    <div id="uploadPlaceholder">
                                        <i class="fas fa-cloud-upload-alt" style="font-size:24px;color:#c4b5fd;margin-bottom:6px;"></i>
                                        <div style="font-size:12px;color:#7c3aed;font-weight:600;">{{ $virtualLabTask->expected_result_image ? 'Upload gambar baru' : 'Klik untuk upload' }}</div>
                                    </div>
                                    <div id="image-preview-container" style="display:none;">
                                        <img id="image-preview" src="" alt="Preview" style="max-height:150px;border-radius:8px;object-fit:contain;">
                                        <div style="font-size:11px;color:#94a3b8;margin-top:5px;">Klik untuk ganti</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Test Cases --}}
                        <div class="vlt-section">
                            <div class="vlt-section-header" style="cursor:pointer;" onclick="toggleTestCases()">
                                <div class="vlt-section-icon" style="background:#f0f9ff;color:#0284c7"><i class="fas fa-vial"></i></div>
                                <h6 class="vlt-section-title">Test Cases</h6>
                                <span class="vlt-section-badge">Opsional</span>
                                <i class="fas fa-chevron-down ms-auto" id="testCasesChevron" style="color:#94a3b8;font-size:13px;transition:transform .2s;"></i>
                            </div>
                            <div id="testCasesBody" class="vlt-section-body" style="display:none;">
                                <textarea name="test_cases" class="vlt-code vlt-code-sm" rows="4" placeholder='[{"input": "5", "output": "25"}]'>{{ old('test_cases', $virtualLabTask->test_cases ? json_encode($virtualLabTask->test_cases) : '') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="vlt-submit-bar">
                            <a href="{{ route('admin.virtual-lab-tasks.index') }}" class="vlt-btn-cancel">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="vlt-btn-save">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>{{-- /side col --}}
                </div>
            </form>
        </div>
    </main>
</x-layout>

@push('scripts')
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
    document.getElementById('deadlineInput').value = mins > 0 ? mins : '';
    updateDeadlinePreview();
}

function toggleTestCases() {
    const body = document.getElementById('testCasesBody');
    const chev = document.getElementById('testCasesChevron');
    const isHidden = body.style.display === 'none';
    body.style.display = isHidden ? 'block' : 'none';
    chev.style.transform = isHidden ? 'rotate(180deg)' : '';
}

function toggleRemoveImg(cb) {
    const box = document.getElementById('existingImgBox');
    if (box) box.style.opacity = cb.checked ? '.35' : '1';
}
</script>
@endpush

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

.vlt-breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: #94a3b8; margin-bottom: 16px;
}
.vlt-breadcrumb a { color: #0057B8; text-decoration: none; font-weight: 600; }
.vlt-breadcrumb a:hover { text-decoration: underline; }
.vlt-breadcrumb i { font-size: 9px; }

.vlt-form-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
.vlt-col-main, .vlt-col-side { display: flex; flex-direction: column; gap: 16px; }

.vlt-section {
    background: #fff; border-radius: 16px;
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
.vlt-badge-deadline-active {
    font-size: 11px; font-weight: 700; color: #d97706;
    background: #fef3c7; border-radius: 6px; padding: 2px 9px;
}
.vlt-section-body { padding: 18px; }
.vlt-section-deadline { border: 1.5px solid #fed7aa; }
.vlt-section-deadline .vlt-section-header { background: #fffbf5; border-bottom-color: #fed7aa; }

/* TBUT stats chips */
.vlt-stats-chip-row {
    display: flex; gap: 10px;
}
.vlt-stats-chip {
    flex: 1; display: flex; align-items: center; gap: 10px;
    background: #fff; border-radius: 12px; padding: 12px 14px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.vlt-stats-chip i { font-size: 18px; }
.vlt-chip-blue i { color: #6366f1; }
.vlt-chip-green i { color: #059669; }
.vlt-chip-purple i { color: #9333ea; }
.vlt-chip-num { font-size: 18px; font-weight: 800; color: #1e293b; line-height: 1; }
.vlt-chip-lbl { font-size: 10px; color: #94a3b8; font-weight: 600; margin-top: 2px; }

.vlt-label { display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.vlt-hint  { display: block; font-size: 11px; color: #94a3b8; margin-bottom: 8px; line-height: 1.5; }

.vlt-input {
    width: 100%; padding: 10px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-size: 13px; color: #1e293b;
    transition: border-color .18s, box-shadow .18s; background: #fff;
}
.vlt-input:focus { border-color: #0057B8; box-shadow: 0 0 0 3px rgba(0,87,184,.08); }

.vlt-code {
    width: 100%; padding: 12px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 13px; background: #0d1117; color: #c9d1d9;
    resize: vertical; line-height: 1.7; transition: border-color .18s;
}
.vlt-code:focus { border-color: #6366f1; }
.vlt-code-sm { font-size: 12px; }

.vlt-deadline-input-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.vlt-deadline-unit { font-size: 13px; font-weight: 600; color: #d97706; flex-shrink: 0; }
.vlt-deadline-preview {
    font-size: 12px; color: #92400e; background: #fff7ed;
    border: 1px solid #fed7aa; border-radius: 8px;
    padding: 8px 12px; margin-bottom: 10px;
}
.vlt-deadline-presets { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.vlt-preset {
    font-size: 11px; font-weight: 700; color: #d97706;
    background: #fff7ed; border: 1px solid #fed7aa;
    border-radius: 6px; padding: 4px 10px; cursor: pointer; transition: all .15s;
}
.vlt-preset:hover { background: #d97706; color: #fff; border-color: #d97706; }
.vlt-preset-clear { color: #dc2626; background: #fef2f2; border-color: #fecaca; }
.vlt-preset-clear:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

.vlt-existing-img {
    position: relative; border-radius: 10px; overflow: hidden;
    border: 1.5px solid #e2e8f0; background: #f8fafc;
    padding: 8px; text-align: center; margin-bottom: 8px;
    transition: opacity .2s;
}
.vlt-existing-img-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: rgba(220,38,38,.85);
    color: #fff; font-size: 11px; font-weight: 700;
    text-align: center; padding: 6px;
    opacity: 0; transition: opacity .18s;
    cursor: pointer;
}
.vlt-existing-img:hover .vlt-existing-img-overlay { opacity: 1; }

.vlt-upload-zone {
    border: 2px dashed #c4b5fd; border-radius: 10px;
    background: #fdf4ff; padding: 18px;
    text-align: center; cursor: pointer; transition: all .2s;
}
.vlt-upload-zone:hover { border-color: #9333ea; background: #f5f3ff; }

.vlt-submit-bar { display: flex; gap: 10px; }
.vlt-btn-cancel {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: #f1f5f9; color: #64748b;
    text-decoration: none; border: none; cursor: pointer; transition: background .18s;
}
.vlt-btn-cancel:hover { background: #e2e8f0; color: #475569; text-decoration: none; }
.vlt-btn-save {
    flex: 2; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #0057B8, #003b7d); color: #fff;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(0,87,184,.3); transition: all .18s;
}
.vlt-btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,87,184,.4); }

@media (max-width: 1024px) {
    .vlt-form-grid { grid-template-columns: 1fr; }
    .vlt-stats-chip-row { flex-wrap: wrap; }
}
</style>
