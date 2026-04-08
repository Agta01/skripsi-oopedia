<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="questions-dashboard" :userName="$userName ?? auth()->user()->name" :userRole="auth()->user()->role->role_name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Kelola Soal" />
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card my-4 modern-card">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center px-4">
                                    <div class="icon icon-shape bg-white text-center border-radius-md shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="material-icons opacity-10" style="font-size: 24px; color: #0057B8 !important;">category</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 modern-title" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px;">Pilih Materi untuk Kelola Soal</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="container">
                                <div class="row">
                                    @forelse($materials as $material)
                                    <div class="col-xl-4 col-md-6 mb-4">
                                        <div class="card h-100 modern-material-card border-0">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-4">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);">
                                                        <i class="material-icons text-white fs-4" style="line-height: 1; margin: 0;">menu_book</i>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge px-3 py-2 rounded-pill shadow-sm" style="background-color: #f8faff; color: #0057B8; border: 1px solid #c2d4ec; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                                            {{ $material->questions_count }} Soal Tersedia
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="text-dark fw-bold mb-2 modern-title" style="font-size: 1.15rem;">{{ $material->title }}</h5>
                                                <p class="text-sm text-secondary mb-0 line-clamp-2" style="line-height: 1.6;">
                                                    {{ strip_tags($material->content) }}
                                                </p>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 p-4 pt-1 mt-auto">
                                                <a href="{{ route('admin.materials.questions.index', $material->id) }}" class="btn w-100 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2 mb-0 py-2 modern-btn-action">
                                                    <span style="font-weight: 600; letter-spacing: 0.5px;">Kelola Soal</span>
                                                    <i class="material-icons text-sm">arrow_forward</i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center opacity-8">
                                            <div class="icon-shape bg-light rounded-circle mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                                <i class="material-icons text-secondary" style="font-size: 40px;">library_books</i>
                                            </div>
                                            <h5 class="text-dark mb-1 font-weight-bolder">Belum ada materi</h5>
                                            <p class="text-secondary text-sm mb-4">Silakan tambahkan materi terlebih dahulu di menu Kelola Materi.</p>
                                            <a href="{{ route('admin.materials.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2">
                                                Ke Kelola Materi
                                            </a>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>

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
        margin-top: 3rem !important;
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

    /* Material Grid Cards */
    .modern-material-card {
        background-color: #f8faff;
        border: 1px solid #e0e6ed !important;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    
    .modern-material-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 87, 184, 0.1);
        border-color: #c2d4ec !important;
    }
    
    .modern-card-icon {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        width: 54px;
        height: 54px;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .modern-text-description {
        color: #67748e;
    }
    
    .modern-btn-action {
        background-color: #ffffff;
        color: #0057B8;
        border: 2px solid #0057B8;
        box-shadow: none;
        letter-spacing: 0.5px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .modern-btn-action:hover {
        background-color: #0057B8;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(0, 87, 184, 0.3);
        transform: translateY(-2px);
    }
</style>

