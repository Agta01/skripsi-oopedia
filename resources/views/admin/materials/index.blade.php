<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="materials" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Materi" />
        <div class="container-fluid py-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.materials.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="input-group input-group-outline bg-white rounded-pill shadow-sm d-flex align-items-center modern-search-bar" style="padding: 4px 8px;">
                            <span class="input-group-text border-0 bg-transparent pe-1 ps-3" style="color: #0057B8;">
                                <i class="material-icons text-md">search</i>
                            </span>
                            <div class="d-flex flex-grow-1 align-items-center position-relative">
                                <label class="form-label mb-0 text-muted d-none">Cari materi...</label>
                                <input type="text" name="search" class="form-control border-0 px-2 flex-grow-1" placeholder="Cari berdasarkan judul atau konten..." value="{{ request('search') }}" style="box-shadow: none; outline: none;">
                            </div>
                            <button class="btn btn-primary rounded-pill mb-0 px-4 py-2" type="submit" style="background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%); letter-spacing: 0.5px;">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4 modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center px-4">
                                    <div class="icon icon-shape bg-white text-center border-radius-md shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="material-icons opacity-10" style="font-size: 24px; color: #0057B8 !important;">menu_book</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 modern-title" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px;">Daftar Materi</h6>
                                </div>
                                <a href="{{ route('admin.materials.create') }}" class="btn btn-light rounded-pill px-4 me-4 modern-btn-add py-2 text-primary font-weight-bold d-flex align-items-center gap-1">
                                    <i class="material-icons text-sm">add</i> Tambah Materi
                                </a>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0 modern-table-container">
                                <table class="table align-items-center mb-0 modern-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-4">Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-4">Cover Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">Dibuat Oleh</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($materials as $material)
                                        <tr class="modern-row">
                                            
                                            <td class="ps-4">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-1 text-md modern-text-primary fw-bold">{{ $material->title }}</h6>
                                                        <p class="text-sm text-secondary mb-0 modern-text-description">
                                                            {{ Str::limit(strip_tags($material->content), 60) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-4">
                                                @if($material->media && $material->media->isNotEmpty())
                                                    <div class="material-thumbnail-container modern-thumbnail shadow-sm">
                                                        <img src="{{ asset($material->media->first()->media_url) }}" 
                                                             alt="{{ $material->title }}" 
                                                             class="material-cover-thumbnail">
                                                    </div>
                                                @else
                                                    <div class="no-image-placeholder modern-no-image shadow-sm">
                                                        <i class="material-icons opacity-6" style="font-size: 28px;">image</i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-gradient-info me-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;">
                                                        {{ strtoupper(substr($material->creator ? $material->creator->name : 'Admin', 0, 1)) }}
                                                    </div>
                                                    <p class="text-sm font-weight-bold mb-0 text-dark">
                                                        {{ $material->creator ? $material->creator->name : 'Admin' }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm modern-badge-light-primary text-xs w-75 py-2">
                                                    <i class="material-icons text-xxs me-1">calendar_today</i>
                                                    {{ $material->created_at->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-icon-only btn-rounded btn-outline-info mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Materi">
                                                        <i class="material-icons text-sm m-0">edit</i>
                                                    </a>
                                                    <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" class="d-inline m-0 p-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Materi" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                            <i class="material-icons text-sm m-0">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center justify-content-center opacity-8">
                                                    <div class="icon-shape bg-light rounded-circle mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                        <i class="material-icons text-secondary" style="font-size: 40px;">menu_book</i>
                                                    </div>
                                                    <h6 class="text-dark mb-1">Belum ada materi</h6>
                                                    <p class="text-secondary text-sm mb-4">Materi yang Anda tambahkan akan muncul di sini.</p>
                                                    <a href="{{ route('admin.materials.create') }}" class="btn btn-primary rounded-pill px-4">
                                                        <i class="material-icons text-sm me-1">add</i> Tambah Materi Baru
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-admin.tutorial />

</x-layout>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
<style>
    /* Modern UI Refinements */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .main-content {
        font-family: 'Inter', sans-serif;
    }

    /* Cards */
    .modern-card {
        border: none;
        box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        background: #ffffff;
        overflow: visible;
        margin-top: 3rem !important; /* memberi ruang untuk floating header */
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

    /* Search Bar */
    .modern-search-bar {
        border: 1px solid #e0e6ed;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .modern-search-bar:focus-within {
        border-color: #0057B8;
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
        vertical-align: middle;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #f0f2f5;
    }

    .modern-row {
        transition: all 0.2s ease;
    }

    .modern-row:hover {
        background-color: #f8faff;
        box-shadow: inset 2px 0 0 0 #0057b8;
    }

    .modern-text-primary {
        color: #344767;
        font-family: 'Inter', sans-serif;
    }

    .modern-text-description {
        color: #67748e;
        line-height: 1.5;
        font-weight: 400;
    }

    /* Status Badges */
    .modern-badge-light-primary {
        background-color: rgba(0, 87, 184, 0.1);
        color: #0057B8;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Action Buttons */
    .modern-action-btn {
        width: 36px;
        height: 36px;
        border-width: 1.5px;
        transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        background: transparent;
    }

    .modern-action-btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-outline-info.modern-action-btn {
        color: #0057b8;
        border-color: #0057b8;
    }
    .btn-outline-info.modern-action-btn:hover {
        background-color: #0057b8;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 87, 184, 0.3);
    }

    .btn-outline-danger.modern-action-btn {
        color: #f5365c;
        border-color: #f5365c;
    }
    .btn-outline-danger.modern-action-btn:hover {
        background-color: #f5365c;
        color: #fff;
        box-shadow: 0 4px 10px rgba(245, 54, 92, 0.3);
    }

    /* Thumbnails */
    .material-cover-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    
    .modern-row:hover .material-cover-thumbnail {
        transform: scale(1.05);
    }
    
    .modern-thumbnail {
        width: 130px;
        height: 85px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 10px;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08) !important;
    }
    
    .modern-no-image {
        width: 130px;
        height: 85px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e0e6ed 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0057B8;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08) !important;
    }
</style>
