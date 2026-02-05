<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    
    @push('head')
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                prefix: 'tw-',
                corePlugins: {
                    preflight: false, 
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            .font-inter { font-family: 'Inter', sans-serif; }
        </style>
    @endpush

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg font-inter">
        <x-navbars.navs.auth titlePage="Virtual Lab Koding" />
        
        <div class="container-fluid py-4 tw-min-h-screen">
            <!-- Header -->
            <div class="tw-mb-8 tw-text-center">
                <h1 class="tw-text-3xl md:tw-text-4xl tw-font-bold tw-text-gray-900">Virtual Lab Koding</h1>
                <p class="tw-text-gray-600 tw-text-lg tw-mt-2">
                    (Mode Dosen) Pilih tugas untuk ditinjau atau gunakan mode bebas.
                </p>
            </div>

            <!-- Sandbox Mode Card -->
            <div class="tw-max-w-4xl tw-mx-auto tw-mb-10">
                <div class="tw-bg-gradient-to-r tw-from-gray-800 tw-to-gray-900 tw-rounded-xl tw-shadow-lg tw-p-6 tw-text-white tw-flex tw-items-center tw-justify-between">
                    <div>
                        <h2 class="tw-text-2xl tw-font-bold tw-mb-2">Sandbox Mode (Mode Bebas)</h2>
                        <p class="tw-text-gray-300">Bereksperimen dengan kode Java secara bebas.</p>
                    </div>
                    <a href="{{ route('virtual-lab.index', ['mode' => 'sandbox']) }}" class="tw-bg-white tw-text-gray-900 hover:tw-bg-gray-100 tw-font-bold tw-py-3 tw-px-6 tw-rounded-lg tw-transition-colors tw-shadow-sm">
                        Mulai Coding <i class="fas fa-arrow-right tw-ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Task List -->
            <div class="tw-max-w-5xl tw-mx-auto">
                <h3 class="tw-text-xl tw-font-bold tw-text-gray-800 tw-mb-6 tw-border-b tw-pb-2">Daftar Tugas Praktikum</h3>
                
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6 tw-mb-12">
                    @forelse($materials as $material)
                        @if($material->virtualLabTasks->count() > 0)
                            <!-- Material Section -->
                            <div class="tw-col-span-full tw-mt-4 tw-mb-2">
                                <h4 class="tw-text-lg tw-font-bold tw-text-gray-700 tw-flex tw-items-center">
                                    <i class="material-icons tw-mr-2 tw-text-blue-500">library_books</i>
                                    {{ $material->title }}
                                </h4>
                            </div>

                            @foreach($material->virtualLabTasks as $task)
                            <div class="tw-bg-white tw-rounded-xl tw-shadow-md hover:tw-shadow-xl tw-transition-all tw-border tw-border-gray-100 tw-flex tw-flex-col tw-h-full tw-group">
                                <div class="tw-p-5 tw-flex-1">
                                    <div class="tw-flex tw-justify-between tw-items-start tw-mb-3">
                                        <span class="tw-px-2 tw-py-1 tw-text-xs tw-font-semibold tw-rounded 
                                            {{ $task->difficulty == 'beginner' ? 'tw-bg-green-100 tw-text-green-800' : 
                                               ($task->difficulty == 'intermediate' ? 'tw-bg-yellow-100 tw-text-yellow-800' : 
                                               'tw-bg-red-100 tw-text-red-800') }}">
                                            {{ ucfirst($task->difficulty) }}
                                        </span>
                                    </div>
                                    <h5 class="tw-text-lg tw-font-bold tw-text-gray-900 tw-mb-2 group-hover:tw-text-blue-600 tw-transition-colors">
                                        {{ $task->title }}
                                    </h5>
                                    <div class="tw-flex tw-gap-2 tw-mt-2">
                                        <a href="{{ route('admin.virtual-lab-tasks.edit', $task->id) }}" class="tw-text-xs tw-bg-yellow-100 tw-text-yellow-800 tw-px-2 tw-py-1 tw-rounded hover:tw-bg-yellow-200">
                                            Edit Soal
                                        </a>
                                    </div>
                                </div>
                                <div class="tw-p-5 tw-pt-0 tw-mt-auto">
                                    <a href="{{ route('virtual-lab.index', ['task' => $task->id]) }}" class="tw-block tw-w-full tw-text-center tw-bg-gray-50 hover:tw-bg-blue-600 hover:tw-text-white tw-text-gray-700 tw-font-medium tw-py-2 tw-px-4 tw-rounded-lg tw-border tw-border-gray-200 hover:tw-border-blue-600 tw-transition-all">
                                        Preview / Coba
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @empty
                        <div class="tw-col-span-full tw-text-center tw-py-12 tw-bg-gray-50 tw-rounded-xl">
                            <i class="material-icons tw-text-6xl tw-text-gray-300 tw-mb-4">assignment_late</i>
                            <p class="tw-text-gray-500 tw-text-lg">Belum ada tugas praktikum yang tersedia saat ini.</p>
                            <a href="{{ route('admin.virtual-lab-tasks.create') }}" class="btn btn-primary mt-3">Buat Tugas Baru</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</x-layout>
