<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="question-banks" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Bank Soal" />
        <div class="container-fluid py-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.question-banks.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="input-group input-group-outline bg-white rounded-pill shadow-sm d-flex align-items-center modern-search-bar" style="padding: 4px 8px;">
                            <span class="input-group-text border-0 bg-transparent pe-1 ps-3" style="color: #0057B8;">
                                <i class="material-icons text-md">search</i>
                            </span>
                            <div class="d-flex flex-grow-1 align-items-center position-relative">
                                <label class="form-label mb-0 text-muted d-none">Cari berdasarkan nama...</label>
                                <input type="text" name="search" class="form-control border-0 px-2 flex-grow-1" placeholder="Cari berdasarkan nama bank soal..." value="{{ request('search') }}" style="box-shadow: none; outline: none;">
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
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible text-white fade show mx-4 mt-3 modern-alert" role="alert">
                                <span class="alert-icon align-middle"><i class="material-icons text-md">check_circle</i></span>
                                <span class="alert-text ms-1">{{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible text-white fade show mx-4 mt-3 modern-alert" role="alert">
                                <span class="alert-icon align-middle"><i class="material-icons text-md">error</i></span>
                                <span class="alert-text ms-1">{{ session('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center px-4">
                                    <div class="icon icon-shape bg-white text-center border-radius-md shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="material-icons opacity-10" style="font-size: 24px; color: #0057B8 !important;">folder_special</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 modern-title" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px;">Daftar Bank Soal</h6>
                                </div>
                                <a href="{{ route('admin.question-banks.create') }}" class="btn btn-light rounded-pill px-4 me-4 modern-btn-add py-2 text-primary font-weight-bold d-flex align-items-center gap-1">
                                    <i class="material-icons text-sm">add</i> Tambah Bank Soal
                                </a>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0 modern-table-container">
                                <table class="table align-items-center mb-0 modern-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-4 w-30">Nama Bank Soal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">Dibuat Oleh</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">Tanggal Dibuat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($questionBanks as $bank)
                                        <tr class="modern-row">
                                            <td class="ps-4">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-1 text-md modern-text-primary fw-bold">{{ $bank->name }}</h6>
                                                    <p class="text-sm text-secondary mb-0 modern-text-description">{{ Str::limit($bank->description, 60) }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm modern-badge-light-primary text-xs" style="color: #67748e; background: #e9ecef; border: 1px solid #dee2e6;">
                                                    <i class="material-icons text-xxs me-1 align-middle">menu_book</i> {{ $bank->material->title ?? 'Tidak ada materi' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs bg-gradient-info me-2 rounded-circle shadow-sm" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                        {{ strtoupper(substr($bank->creator ? $bank->creator->name : 'Unk', 0, 1)) }}
                                                    </div>
                                                    <p class="text-sm font-weight-bold mb-0 text-dark">{{ $bank->creator ? $bank->creator->name : 'Unknown' }}</p>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-bold">
                                                    {{ $bank->created_at->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center pe-4">
                                                <div class="d-flex justify-content-center gap-2 flex-wrap" style="max-width: 250px; margin: 0 auto;">
                                                    <a href="{{ route('admin.question-banks.show', $bank) }}" class="btn btn-icon-only btn-rounded btn-outline-info mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                                        <i class="material-icons text-sm m-0">visibility</i>
                                                    </a>
                                                    <a href="{{ route('admin.question-banks.manage-questions', $bank) }}" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn text-success border-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Kelola Soal">
                                                        <i class="material-icons text-sm m-0">question_answer</i>
                                                    </a>
                                                    <a href="{{ route('admin.question-banks.configure', $bank) }}" class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn text-warning border-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Konfigurasi">
                                                        <i class="material-icons text-sm m-0">settings</i>
                                                    </a>
                                                    <a href="{{ route('admin.question-banks.edit', $bank) }}" class="btn btn-icon-only btn-rounded btn-outline-primary mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="material-icons text-sm m-0">edit</i>
                                                    </a>
                                                    <form action="{{ route('admin.question-banks.destroy', $bank) }}" method="POST" class="d-inline m-0 p-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 d-flex align-items-center justify-content-center p-2 modern-action-btn btn-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus bank soal ini?')">
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
                                                        <i class="material-icons text-secondary" style="font-size: 40px;">folder_special</i>
                                                    </div>
                                                    <h6 class="text-dark mb-1">Belum ada Bank Soal</h6>
                                                    <p class="text-secondary text-sm mb-4">Buat bank soal untuk mengelompokkan pertanyaan Anda.</p>
                                                    <a href="{{ route('admin.question-banks.create') }}" class="btn btn-primary rounded-pill px-4">
                                                        <i class="material-icons text-sm me-1">add</i> Tambah Bank Soal
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $questionBanks->links() }}
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
        border-color: #0057B8 !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 87, 184, 0.15) !important;
    }

    /* Alert */
    .modern-alert {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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

    /* Action Buttons */
    .modern-action-btn {
        width: 32px;
        height: 32px;
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

    .btn-outline-success.modern-action-btn:hover {
        background-color: #28a745;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }
    
    .btn-outline-warning.modern-action-btn:hover {
        background-color: #ffc107;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    }
    
    .btn-outline-primary.modern-action-btn {
        color: #e91e63;
        border-color: #e91e63;
    }
    .btn-outline-primary.modern-action-btn:hover {
        background-color: #e91e63;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3);
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
</style>