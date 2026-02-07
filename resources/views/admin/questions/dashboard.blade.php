<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="questions-dashboard" :userName="$userName ?? auth()->user()->name" :userRole="auth()->user()->role->role_name ?? 'Admin'" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Kelola Soal" />
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3">Pilih Materi untuk Kelola Soal</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="container">
                                <div class="row">
                                    @forelse($materials as $material)
                                    <div class="col-xl-4 col-md-6 mb-4">
                                        <div class="card h-100 shadow-sm border">
                                            <div class="card-header p-3 pt-2">
                                                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                                    <i class="material-icons opacity-10">library_books</i>
                                                </div>
                                                <div class="text-end pt-1">
                                                    <p class="text-sm mb-0 text-capitalize text-secondary">Total Soal</p>
                                                    <h4 class="mb-0">{{ $material->questions_count }}</h4>
                                                </div>
                                            </div>
                                            <hr class="dark horizontal my-0">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1 text-dark">{{ $material->title }}</h6>
                                                <p class="text-sm text-secondary mb-0 text-truncate" style="max-width: 250px;">
                                                    {{ strip_tags($material->content) }}
                                                </p>
                                            </div>
                                            <div class="card-footer p-3">
                                                <a href="{{ route('admin.materials.questions.index', $material->id) }}" class="btn btn-primary btn-sm w-100 mb-0">
                                                    <i class="material-icons text-sm me-2">edit</i> Kelola Soal
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="material-icons text-secondary" style="font-size: 64px;">library_books</i>
                                            <h5 class="mt-3 text-secondary">Belum ada materi</h5>
                                            <p class="text-secondary text-sm">Silakan tambahkan materi terlebih dahulu di menu Kelola Materi.</p>
                                            <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-primary mt-2">
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
