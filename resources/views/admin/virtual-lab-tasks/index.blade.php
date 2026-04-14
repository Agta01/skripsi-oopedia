<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name"
        :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Virtual Lab Tasks" />
        <div class="container-fluid py-4">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.virtual-lab-tasks.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group input-group-outline my-3">
                            <select name="material_id" class="form-select px-3 py-2 border rounded-3 shadow-sm bg-white" style="border-color: #e9ecef !important; outline: none; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important;" onchange="this.form.submit()">
                                <option value="">Semua Materi</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-12">
                    <div class="card modern-card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="border-radius-xl pt-4 pb-3 px-4 d-flex justify-content-between align-items-center modern-header">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;border-radius:10px;">
                                        <i class="material-icons" style="font-size:22px;color:#0057B8">science</i>
                                    </div>
                                    <h6 class="text-white text-capitalize mb-0 fw-semibold" style="letter-spacing:.4px">Daftar Tugas Virtual Lab</h6>
                                </div>
                                <div class="d-flex me-0">
                                    <a href="{{ route('admin.virtual-lab-tasks.create') }}" class="btn btn-sm btn-light text-primary mb-0 d-flex align-items-center gap-1 shadow-sm" style="border-radius:8px; font-weight:600;">
                                        <i class="material-icons text-sm">add</i>
                                        <span>Tambah Tugas</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="modern-table-header">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Judul Tugas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="padding: 1rem .75rem;">Kesulitan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 1rem .75rem;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column justify-content-center px-2 py-1">
                                                        <h6 class="mb-0 text-sm fw-semibold" style="color:#344767">{{ $task->title }}</h6>
                                                        <p class="text-xs text-secondary mb-0 mt-1">
                                                            {{ Str::limit(strip_tags($task->description), 50) }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0 text-secondary">{{ $task->material->title }}</p>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm px-3 py-2" style="border-radius: 8px; font-weight: 600; {{ $task->difficulty == 'beginner' ? 'background-color: rgba(45,206,137,0.1); color: #1a9e63;' : ($task->difficulty == 'intermediate' ? 'background-color: rgba(251,99,64,0.1); color: #d94a28;' : 'background-color: rgba(245,54,92,0.1); color: #b41a3a;') }}">
                                                        {{ ucfirst($task->difficulty) }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('admin.virtual-lab-tasks.edit', $task->id) }}"
                                                            class="btn btn-sm btn-info mb-0 d-flex align-items-center gap-1 shadow-sm" style="background: linear-gradient(135deg, #11cdef 0%, #1181ef 100%); border:none; border-radius: 8px; padding: 0.4rem 0.8rem;">
                                                            <i class="material-icons" style="font-size:14px">edit</i>
                                                            <span class="d-none d-sm-inline">Edit</span>
                                                        </a>
                                                        <form action="{{ route('admin.virtual-lab-tasks.destroy', $task->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger mb-0 d-flex align-items-center gap-1 shadow-sm"
                                                                style="background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); border:none; border-radius: 8px; padding: 0.4rem 0.8rem;"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                                <i class="material-icons" style="font-size:14px">delete</i>
                                                                <span class="d-none d-sm-inline">Hapus</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-secondary">
                                                    Belum ada tugas virtual lab.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 py-2">
                                {{ $tasks->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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