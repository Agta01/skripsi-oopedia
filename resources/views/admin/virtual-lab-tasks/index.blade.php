<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Virtual Lab Tasks" />
        <div class="container-fluid py-4">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.virtual-lab-tasks.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group input-group-outline my-3">
                            <select name="material_id" class="form-control" onchange="this.form.submit()">
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
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3">Daftar Tugas Virtual Lab</h6>
                                <a href="{{ route('admin.virtual-lab-tasks.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-icons text-sm">add</i> Tambah Tugas
                                </a>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul Tugas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Materi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kesulitan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $task->title }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ Str::limit(strip_tags($task->description), 50) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $task->material->title }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $task->difficulty == 'beginner' ? 'success' : ($task->difficulty == 'intermediate' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($task->difficulty) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('admin.virtual-lab-tasks.edit', $task->id) }}" class="btn btn-sm btn-info">
                                                    <i class="material-icons text-sm">edit</i>
                                                </a>
                                                <form action="{{ route('admin.virtual-lab-tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                        <i class="material-icons text-sm">delete</i>
                                                    </button>
                                                </form>
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
    </main>
</x-layout>
