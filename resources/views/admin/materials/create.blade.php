<x-layout bodyClass="g-sidenav-show bg-gray-200">
    @push('head')
        <x-head.tinymce-config />
    @endpush

    <x-navbars.sidebar activePage="materials" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Tambah Materi" />
        <div class="container-fluid py-4 px-4">

            {{-- Page header --}}
            <div class="mat-page-header">
                <div class="mat-page-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h4 class="mat-page-title">Tambah Materi Baru</h4>
                    <div class="mat-page-sub">
                        <a href="{{ route('admin.materials.index') }}" class="mat-link">Kelola Materi</a>
                        <i class="fas fa-chevron-right" style="font-size:9px;color:#cbd5e1;"></i>
                        <span style="color:#94a3b8;">Tambah Baru</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.materials.store') }}" id="materialForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="created_by" value="{{ auth()->id() }}">

                <div class="mat-grid">

                    {{-- ═══ MAIN COLUMN ═══ --}}
                    <div class="mat-col-main">

                        {{-- Basic Info --}}
                        <div class="mat-card">
                            <div class="mat-card-hdr">
                                <div class="mat-card-icon" style="background:#eff6ff;color:#0057B8;"><i class="fas fa-tag"></i></div>
                                <h6 class="mat-card-title">Informasi Dasar</h6>
                            </div>
                            <div class="mat-card-body">
                                <label class="mat-label">Judul Materi <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="mat-input @error('title') mat-input-err @enderror"
                                       placeholder="Contoh: Class dan Object dalam Java"
                                       value="{{ old('title') }}" required>
                                @error('title') <span class="mat-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="mat-card">
                            <div class="mat-card-hdr">
                                <div class="mat-card-icon" style="background:#f0fdf4;color:#059669;"><i class="fas fa-align-left"></i></div>
                                <h6 class="mat-card-title">Isi Materi</h6>
                                <span class="mat-badge">Gunakan editor di bawah</span>
                            </div>
                            <div class="mat-card-body">
                                <textarea id="content-editor" name="content" class="@error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                                @error('content') <span class="mat-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Video --}}
                        <div class="mat-card">
                            <div class="mat-card-hdr" style="cursor:pointer;" onclick="toggleVideo()">
                                <div class="mat-card-icon" style="background:#fdf4ff;color:#9333ea;"><i class="fab fa-youtube"></i></div>
                                <h6 class="mat-card-title">Video Pembelajaran</h6>
                                <span class="mat-badge mat-badge-purple">Opsional</span>
                                <i class="fas fa-chevron-down ms-auto" id="videoChevron" style="color:#94a3b8;font-size:13px;transition:transform .2s;"></i>
                            </div>
                            <div id="videoBody" class="mat-card-body" style="display:none;">
                                <div class="mat-field">
                                    <label class="mat-label">URL Video (YouTube / MP4)</label>
                                    <div class="mat-input-icon-wrap">
                                        <i class="fab fa-youtube mat-input-icon" style="color:#dc2626;"></i>
                                        <input type="url" name="video_url" class="mat-input mat-input-has-icon @error('video_url') mat-input-err @enderror"
                                               placeholder="https://youtube.com/watch?v=..."
                                               value="{{ old('video_url') }}" oninput="updateVideoPreview(this.value)">
                                    </div>
                                    @error('video_url') <span class="mat-err">{{ $message }}</span> @enderror
                                </div>
                                <div class="mat-field">
                                    <label class="mat-label">Deskripsi Video</label>
                                    <input type="text" name="video_description" class="mat-input"
                                           placeholder="Deskripsi singkat tentang video..."
                                           value="{{ old('video_description') }}">
                                </div>
                                <div id="videoPreviewBox" style="display:none;background:#0d1117;border-radius:12px;overflow:hidden;margin-top:8px;">
                                    <iframe id="videoIframe" width="100%" height="200" src="" frameborder="0" allowfullscreen style="display:block;"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /main col --}}

                    {{-- ═══ SIDE COLUMN ═══ --}}
                    <div class="mat-col-side">

                        {{-- Cover Image --}}
                        <div class="mat-card">
                            <div class="mat-card-hdr">
                                <div class="mat-card-icon" style="background:#fff7ed;color:#d97706;"><i class="fas fa-image"></i></div>
                                <h6 class="mat-card-title">Gambar Cover</h6>
                            </div>
                            <div class="mat-card-body">
                                <div class="mat-upload-zone" id="uploadZone" onclick="document.getElementById('cover_image').click()">
                                    <input type="file" name="cover_image" id="cover_image" class="d-none" accept="image/*"
                                           onchange="previewImage(event)">
                                    <div id="uploadPlaceholder">
                                        <i class="fas fa-cloud-upload-alt" style="font-size:32px;color:#fbbf24;margin-bottom:10px;"></i>
                                        <div style="font-size:13px;font-weight:700;color:#d97706;">Klik untuk upload gambar</div>
                                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">JPG, PNG, GIF — Maks 2MB</div>
                                    </div>
                                    <div id="imagePreviewWrap" style="display:none;text-align:center;">
                                        <img id="imagePreviewImg" src="" alt="Preview"
                                             style="max-height:180px;border-radius:10px;object-fit:contain;width:100%;">
                                        <div style="font-size:11px;color:#94a3b8;margin-top:8px;">Klik untuk ganti</div>
                                    </div>
                                </div>
                                <div class="mat-img-guide">
                                    <div class="mat-img-guide-row"><i class="fas fa-check-circle" style="color:#059669;font-size:11px;"></i> Rasio 16:9 atau 4:3</div>
                                    <div class="mat-img-guide-row"><i class="fas fa-check-circle" style="color:#059669;font-size:11px;"></i> Min 640×360px</div>
                                    <div class="mat-img-guide-row"><i class="fas fa-check-circle" style="color:#059669;font-size:11px;"></i> Format JPG, PNG, GIF</div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="mat-submit-bar">
                            <a href="{{ route('admin.materials.index') }}" class="mat-btn-cancel">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="mat-btn-save">
                                <i class="fas fa-save"></i> Simpan Materi
                            </button>
                        </div>

                        {{-- Tips --}}
                        <div class="mat-card" style="border-color:#fef3c7;">
                            <div class="mat-card-hdr" style="background:#fffbf5;border-bottom-color:#fef3c7;">
                                <div class="mat-card-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-lightbulb"></i></div>
                                <h6 class="mat-card-title">Tips Materi Baik</h6>
                            </div>
                            <div class="mat-card-body" style="padding-top:12px;">
                                <ul class="mat-tips">
                                    <li>Gunakan judul yang singkat namun deskriptif</li>
                                    <li>Sertakan contoh kode nyata dalam isi materi</li>
                                    <li>Gambar cover membantu mahasiswa mengenali materi</li>
                                    <li>Video mendukung gaya belajar visual</li>
                                </ul>
                            </div>
                        </div>

                    </div>{{-- /side col --}}
                </div>
            </form>
        </div>
    </main>
    <x-admin.tutorial />
</x-layout>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display    = 'none';
        document.getElementById('imagePreviewWrap').style.display     = 'block';
        document.getElementById('imagePreviewImg').src               = e.target.result;
    };
    reader.readAsDataURL(file);
}

function toggleVideo() {
    const body = document.getElementById('videoBody');
    const chev = document.getElementById('videoChevron');
    const hidden = body.style.display === 'none';
    body.style.display = hidden ? 'block' : 'none';
    chev.style.transform = hidden ? 'rotate(180deg)' : '';
}

function updateVideoPreview(url) {
    const box = document.getElementById('videoPreviewBox');
    const iframe = document.getElementById('videoIframe');
    const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/watch\?v=|youtube\.com\/embed\/)([A-Za-z0-9_-]{11})/);
    if (ytMatch) {
        iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1];
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
        iframe.src = '';
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.container-fluid { font-family: 'Inter', sans-serif; }

.mat-page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
.mat-page-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: linear-gradient(135deg, #059669, #047857);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(5,150,105,.3);
}
.mat-page-title { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px; }
.mat-page-sub   { display: flex; align-items: center; gap: 6px; font-size: 12px; }
.mat-link { color: #059669; text-decoration: none; font-weight: 600; }
.mat-link:hover { text-decoration: underline; }

.mat-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }
.mat-col-main, .mat-col-side { display: flex; flex-direction: column; gap: 16px; }

.mat-card { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
.mat-card-hdr { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #f8fafc; background: #fafbfc; }
.mat-card-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.mat-card-title { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; }
.mat-badge { font-size: 11px; font-weight: 600; color: #64748b; background: #f1f5f9; border-radius: 6px; padding: 2px 8px; }
.mat-badge-purple { color: #7c3aed; background: #f5f3ff; }
.mat-card-body { padding: 18px; }

.mat-field { margin-bottom: 14px; }
.mat-label { display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.mat-input {
    width: 100%; padding: 11px 14px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; outline: none;
    font-size: 13px; color: #1e293b; font-family: 'Inter', sans-serif;
    transition: border-color .18s, box-shadow .18s; background: #fff;
}
.mat-input:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.08); }
.mat-input-err { border-color: #fca5a5 !important; }
.mat-err { font-size: 11px; color: #dc2626; margin-top: 4px; display: block; }
.mat-input-icon-wrap { position: relative; }
.mat-input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px; }
.mat-input-has-icon { padding-left: 36px; }

.mat-upload-zone {
    border: 2px dashed #fed7aa; border-radius: 12px;
    background: #fffbf5; padding: 28px 20px;
    text-align: center; cursor: pointer;
    transition: all .2s; margin-bottom: 12px;
}
.mat-upload-zone:hover { border-color: #d97706; background: #fff7ed; }

.mat-img-guide { display: flex; flex-direction: column; gap: 5px; }
.mat-img-guide-row { display: flex; align-items: center; gap: 7px; font-size: 12px; color: #64748b; }

.mat-submit-bar { display: flex; gap: 10px; }
.mat-btn-cancel {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: #f1f5f9; color: #64748b; text-decoration: none; border: none; cursor: pointer;
    transition: background .18s;
}
.mat-btn-cancel:hover { background: #e2e8f0; color: #475569; text-decoration: none; }
.mat-btn-save {
    flex: 2; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #059669, #047857); color: #fff;
    border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(5,150,105,.3); transition: all .18s;
}
.mat-btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,.4); }

.mat-tips { padding-left: 18px; margin: 0; }
.mat-tips li { font-size: 12px; color: #64748b; margin-bottom: 8px; line-height: 1.5; }

@media (max-width: 1024px) {
    .mat-grid { grid-template-columns: 1fr; }
}
</style>
