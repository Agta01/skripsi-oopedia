<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="question-banks" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Tambah Bank Soal" />
        <div class="container-fluid py-4 px-4">

            {{-- Page header --}}
            <div class="qb-page-header">
                <div class="qb-page-header-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h4 class="qb-page-title">Tambah Bank Soal Baru</h4>
                    <div class="qb-page-sub">
                        <a href="{{ route('admin.question-banks.index') }}" class="qb-breadcrumb-link">Bank Soal</a>
                        <i class="fas fa-chevron-right" style="font-size:9px;"></i>
                        <span>Tambah Baru</span>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="qb-alert qb-alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="qb-form-grid">

                {{-- ═══ MAIN COLUMN ═══ --}}
                <div class="qb-col-main">

                    <div class="qb-card">
                        <div class="qb-card-header">
                            <div class="qb-card-icon" style="background:#eff6ff;color:#0057B8"><i class="fas fa-info-circle"></i></div>
                            <h6 class="qb-card-title">Informasi Bank Soal</h6>
                        </div>
                        <div class="qb-card-body">
                            <form method="POST" action="{{ route('admin.question-banks.store') }}" id="qbForm">
                                @csrf

                                <div class="qb-field">
                                    <label class="qb-label">Nama Bank Soal <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="qb-input" placeholder="Contoh: Bank Soal OOP — Semester 3"
                                           value="{{ old('name') }}" required id="nameInput" oninput="updatePreview()">
                                    @error('name') <span class="qb-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="qb-field">
                                    <label class="qb-label">Materi Terkait <span class="text-danger">*</span></label>
                                    <select name="material_id" class="qb-input" required id="materialSelect" onchange="updatePreview()">
                                        <option value="">— Pilih Materi —</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                                {{ $material->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id') <span class="qb-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="qb-field">
                                    <label class="qb-label">Deskripsi</label>
                                    <textarea name="description" class="qb-input qb-textarea" rows="4"
                                              placeholder="Deskripsi singkat tentang bank soal ini (opsional)" id="descInput">{{ old('description') }}</textarea>
                                    @error('description') <span class="qb-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="qb-submit-bar">
                                    <a href="{{ route('admin.question-banks.index') }}" class="qb-btn-cancel">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="qb-btn-save">
                                        <i class="fas fa-save"></i> Simpan Bank Soal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ═══ SIDE COLUMN ═══ --}}
                <div class="qb-col-side">

                    {{-- Preview card --}}
                    <div class="qb-card">
                        <div class="qb-card-header">
                            <div class="qb-card-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fas fa-eye"></i></div>
                            <h6 class="qb-card-title">Preview Kartu</h6>
                        </div>
                        <div class="qb-card-body">
                            <div class="qb-preview-card">
                                <div class="qb-preview-card-top">
                                    <div class="qb-preview-icon"><i class="fas fa-layer-group"></i></div>
                                    <div>
                                        <div class="qb-preview-name" id="previewName">Nama bank soal…</div>
                                        <div class="qb-preview-material" id="previewMaterial">Pilih materi</div>
                                    </div>
                                </div>
                                <div class="qb-preview-desc" id="previewDesc">Deskripsi akan tampil di sini.</div>
                                <div class="qb-preview-foot">
                                    <span class="qb-preview-badge">0 Soal</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tips card --}}
                    <div class="qb-card qb-card-tip">
                        <div class="qb-card-header">
                            <div class="qb-card-icon" style="background:#fef3c7;color:#d97706"><i class="fas fa-lightbulb"></i></div>
                            <h6 class="qb-card-title">Tips</h6>
                        </div>
                        <div class="qb-card-body" style="padding-top:12px;">
                            <ul class="qb-tips-list">
                                <li>Beri nama yang deskriptif agar mudah dicari</li>
                                <li>Satu bank soal bisa berisi banyak tipe soal</li>
                                <li>Setelah membuat bank soal, Anda bisa menambahkan soal-soal ke dalamnya</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-admin.tutorial />
</x-layout>

<script>
function updatePreview() {
    const name = document.getElementById('nameInput').value || 'Nama bank soal…';
    const desc = document.getElementById('descInput').value || 'Deskripsi akan tampil di sini.';
    const sel  = document.getElementById('materialSelect');
    const mat  = sel.options[sel.selectedIndex]?.text || 'Pilih materi';

    document.getElementById('previewName').textContent     = name;
    document.getElementById('previewDesc').textContent     = desc;
    document.getElementById('previewMaterial').textContent = mat !== '— Pilih Materi —' ? mat : 'Pilih materi';
}
document.getElementById('descInput').addEventListener('input', updatePreview);
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

/* Page header */
.qb-page-header {
    display: flex; align-items: center; gap: 16px; margin-bottom: 24px;
}
.qb-page-header-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: linear-gradient(135deg, #0057B8, #003b7d);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(0,87,184,.3);
}
.qb-page-title { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px; }
.qb-page-sub   { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; }
.qb-breadcrumb-link { color: #0057B8; text-decoration: none; font-weight: 600; }
.qb-breadcrumb-link:hover { text-decoration: underline; }

/* Alert */
.qb-alert { display: flex; align-items: flex-start; gap: 12px; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 600; }
.qb-alert-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }

/* Grid */
.qb-form-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }
.qb-col-main, .qb-col-side { display: flex; flex-direction: column; gap: 16px; }

/* Card */
.qb-card { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
.qb-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #f8fafc; background: #fafbfc; }
.qb-card-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.qb-card-title { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; }
.qb-card-body { padding: 20px; }

/* Fields */
.qb-field { margin-bottom: 18px; }
.qb-label { display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.qb-input {
    width: 100%; padding: 11px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-size: 13px; color: #1e293b; font-family: 'Inter', sans-serif;
    transition: border-color .18s, box-shadow .18s; background: #fff;
}
.qb-input:focus { border-color: #0057B8; box-shadow: 0 0 0 3px rgba(0,87,184,.08); }
.qb-textarea { resize: vertical; line-height: 1.6; }
.qb-error { font-size: 11px; color: #dc2626; margin-top: 4px; display: block; }

/* Submit bar */
.qb-submit-bar { display: flex; gap: 10px; margin-top: 8px; }
.qb-btn-cancel {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: #f1f5f9; color: #64748b; text-decoration: none; border: none; cursor: pointer;
    transition: background .18s;
}
.qb-btn-cancel:hover { background: #e2e8f0; color: #475569; text-decoration: none; }
.qb-btn-save {
    flex: 2; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #0057B8, #003b7d); color: #fff;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(0,87,184,.3); transition: all .18s;
}
.qb-btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,87,184,.4); }

/* Preview card */
.qb-preview-card {
    border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden;
}
.qb-preview-card-top {
    display: flex; align-items: center; gap: 12px;
    padding: 14px; background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
}
.qb-preview-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(255,255,255,.2); display: flex; align-items: center;
    justify-content: center; font-size: 18px; color: #fff; flex-shrink: 0;
}
.qb-preview-name { font-size: 14px; font-weight: 700; color: #fff; }
.qb-preview-material { font-size: 11px; color: rgba(255,255,255,.75); margin-top: 2px; }
.qb-preview-desc {
    padding: 12px 14px; font-size: 12px; color: #64748b; line-height: 1.5;
    background: #f8fafc; border-bottom: 1px solid #f1f5f9;
    min-height: 48px;
}
.qb-preview-foot { padding: 10px 14px; }
.qb-preview-badge {
    display: inline-block; font-size: 11px; font-weight: 700; color: #0057B8;
    background: #eff6ff; border-radius: 6px; padding: 3px 10px;
}

/* Tips */
.qb-tips-list { padding-left: 18px; margin: 0; }
.qb-tips-list li { font-size: 12px; color: #64748b; margin-bottom: 8px; line-height: 1.5; }

@media (max-width: 900px) {
    .qb-form-grid { grid-template-columns: 1fr; }
}
</style>