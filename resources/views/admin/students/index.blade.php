<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="students" :userName="auth()->user()->name"
        :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Data Mahasiswa" />
        <div class="container-fluid py-4">
            <!-- Modern Search Form -->
            <div class="row mb-5 mt-2">
                <div class="col-md-7 col-lg-5">
                    <form method="GET" action="{{ route('admin.students.index') }}">
                        <div class="d-flex align-items-center bg-white px-2 py-2 rounded-3 shadow-sm border" style="border-color: #e9ecef !important;">
                            <i class="material-icons text-muted ms-2 me-2">search</i>
                            <input type="text" name="search" class="form-control border-0 px-2" placeholder="Cari berdasarkan nama mahasiswa..." value="{{ request('search') }}" style="box-shadow: none; background: transparent;">
                            <button class="btn btn-primary mb-0 ms-2 rounded-2 px-4 py-2" type="submit" style="text-transform: none; font-weight: 600;">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card modern-card">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('importErrors'))
                            <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                                <p>Beberapa baris tidak dapat diimpor:</p>
                                <ul>
                                    @foreach(session('importErrors') as $error)
                                        <li>Baris {{ $error['row'] }}: {{ implode(', ', $error['errors']) }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">people</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 fw-semibold" style="letter-spacing:.4px">Data Mahasiswa</h6>
                                </div>
                                <div class="d-flex me-0">
                                    <a href="{{ route('admin.students.import') }}" class="btn btn-sm btn-light text-primary mb-0 d-flex align-items-center gap-1 shadow-sm" style="border-radius:8px; font-weight:600;">
                                        <i class="material-icons text-sm">upload_file</i>
                                        <span>Tambah/Import Excel</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-3 pb-2 w-100">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="modern-table-header">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Mahasiswa</th>
                                            <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Email</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Total Soal Dijawab</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Progress Keseluruhan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column justify-content-center px-2 py-1">
                                                        <h6 class="mb-0 text-sm fw-semibold" style="color:#344767">{{ $student->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $student->email }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span
                                                        class="text-xs font-weight-bold">{{ $student->total_answered_questions ?? 0 }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="progress" style="height: 8px; width: 80%; margin: 0 auto;">
                                                        <div class="progress-bar bg-gradient-info" role="progressbar"
                                                            style="width: {{ $student->overall_progress }}%"
                                                            aria-valuenow="{{ $student->overall_progress }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-xs font-weight-bold">{{ $student->overall_progress }}%</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('admin.students.progress', $student) }}"
                                                            class="btn btn-sm btn-info mb-0 d-flex align-items-center gap-1 shadow-sm" style="background: linear-gradient(135deg, #11cdef 0%, #1181ef 100%); border:none; border-radius: 8px;">
                                                            <i class="material-icons" style="font-size:14px">visibility</i>
                                                            <span>Detail</span>
                                                        </a>
                                                        <form action="{{ route('admin.students.destroy', $student) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger mb-0 d-flex align-items-center gap-1 shadow-sm"
                                                                style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); border:none; border-radius: 8px;"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')">
                                                                <i class="material-icons" style="font-size:14px">delete</i>
                                                                <span>Hapus</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <p class="text-sm mb-0">Belum ada data mahasiswa</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $students->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-admin.tutorial />

</x-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    /* ===== Card System ===== */
    .modern-card {
        border: none;
        box-shadow: 0 10px 30px 0 rgba(0,0,0,0.05);
        border-radius: 16px;
        background: #fff;
        overflow: visible;
        margin-top: 2.5rem !important;
    }
    .modern-header {
        background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%);
        box-shadow: 0 8px 25px -8px rgba(0,87,184,0.45);
        border-radius: 16px;
        transform: translateY(-20px);
    }

    /* ===== Tables ===== */
    .modern-table-header th {
        font-family: 'Inter', sans-serif;
        text-transform: uppercase;
        font-size: .63rem;
        font-weight: 700;
        letter-spacing: .5px;
        color: #8392ab;
        border-bottom: 2px solid #f0f2f5;
        white-space: nowrap;
    }
    .table tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
        padding: .85rem .75rem;
    }
    .table tbody tr {
        transition: background .15s ease;
    }
    .table tbody tr:hover {
        background: #f8faff;
    }
</style>