@extends('mahasiswa.layouts.app')

@section('title', 'Daftar Tugas Virtual Lab')

@push('css')
    <!-- Inject Tailwind (Priority High) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            corePlugins: {
                preflight: false, // DISABLE Preflight to protect Bootstrap Sidebar
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .modern-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
    </style>
@endpush

@section('content')
<div class="font-jakarta tw-min-h-screen tw-bg-gray-50/50">
    <div class="container-fluid tw-py-8">
        <!-- Header Section -->
        <div class="tw-max-w-6xl tw-mx-auto tw-mb-12 tw-text-center">
            <span class="tw-inline-block tw-py-1 tw-px-3 tw-rounded-full tw-bg-blue-50 tw-text-blue-600 tw-text-xs tw-font-bold tw-tracking-wide tw-mb-4 tw-border tw-border-blue-100">
                VIRTUAL LABORATORY
            </span>
            <h1 class="tw-text-4xl md:tw-text-5xl tw-font-bold tw-text-gray-900 tw-tracking-tight tw-mb-4">
                Asah Skill Codingmu
            </h1>
            <p class="tw-text-gray-500 tw-text-lg tw-max-w-2xl tw-mx-auto">
                Pilih tugas praktikum yang tersedia atau eksplorasi ide-idemu secara bebas di Sandbox Mode.
            </p>
        </div>

        <!-- Sandbox Mode Banner -->
        <div class="tw-max-w-6xl tw-mx-auto tw-mb-16">
            <div class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-bg-gradient-to-r tw-from-blue-600 tw-to-indigo-600 tw-shadow-xl tw-shadow-blue-200">
                <!-- Decorative Elements -->
                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-64 tw-h-64 tw-bg-white/10 tw-rounded-full tw-blur-3xl tw-translate-x-1/2 tw--translate-y-1/2"></div>
                <div class="tw-absolute tw-bottom-0 tw-left-0 tw-w-48 tw-h-48 tw-bg-black/5 tw-rounded-full tw-blur-2xl tw--translate-x-1/3 tw-translate-y-1/3"></div>

                <div class="tw-relative tw-p-8 md:tw-p-10 tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-justify-between tw-gap-6">
                    <div class="tw-flex-1 tw-text-center md:tw-text-left">
                        <div class="tw-flex tw-items-center tw-justify-center md:tw-justify-start tw-gap-3 tw-mb-3">
                            <div class="tw-p-2 tw-bg-white/10 tw-rounded-lg tw-backdrop-blur-sm">
                                <i class="fas fa-code tw-text-white tw-text-xl"></i>
                            </div>
                            <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-white">Sandbox Mode</h2>
                        </div>
                        <p class="tw-text-blue-100 tw-text-lg tw-leading-relaxed">
                            Ruang eksperimen bebas tanpa batasan soal. Tulis, jalankan, dan uji kode Java-mu sendiri.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('virtual-lab.index', ['mode' => 'sandbox']) }}" 
                           class="tw-group tw-inline-flex tw-items-center tw-justify-center tw-bg-white tw-text-blue-600 hover:tw-text-blue-700 tw-font-bold tw-py-4 tw-px-8 tw-rounded-xl tw-transition-all tw-duration-300 tw-shadow-lg hover:tw-shadow-white/20 hover:tw-scale-105">
                            <span>Mulai Coding</span>
                            <i class="fas fa-arrow-right tw-ml-2 tw-transform group-hover:tw-translate-x-1 tw-transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task List Section -->
        <div class="tw-max-w-6xl tw-mx-auto">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-8">
                <h3 class="tw-text-2xl tw-font-bold tw-text-gray-900">Daftar Tugas</h3>
                <span class="tw-text-sm tw-text-gray-500 tw-bg-white tw-px-3 tw-py-1 tw-rounded-full tw-border tw-border-gray-200 shadow-sm">
                    Total: {{ $materials->pluck('virtualLabTasks')->flatten()->count() }} Tugas
                </span>
            </div>
            
            <div class="tw-space-y-12">
                @forelse($materials as $material)
                    @if($material->virtualLabTasks->count() > 0)
                        <!-- Material Group -->
                        <div class="tw-relative">
                            <div class="tw-flex tw-items-center tw-gap-3 tw-mb-6">
                                <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-blue-100 tw-flex tw-items-center tw-justify-center">
                                    <span class="tw-text-blue-600 tw-font-bold tw-text-lg">{{ $loop->iteration }}</span>
                                </div>
                                <h4 class="tw-text-xl tw-font-bold tw-text-gray-800">{{ $material->title }}</h4>
                                <div class="tw-h-px tw-flex-1 tw-bg-gray-200 tw-ml-4"></div>
                            </div>

                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6 tw-pl-4 md:tw-pl-14">
                                @foreach($material->virtualLabTasks as $task)
                                <div class="tw-group tw-relative tw-bg-white tw-rounded-2xl tw-border tw-border-gray-100 tw-transition-all tw-duration-300 hover:tw-shadow-xl hover:tw-shadow-blue-50 hover:tw-border-blue-100 hover:tw--translate-y-1">
                                    <div class="tw-p-6">
                                        <!-- Difficulty Badge -->
                                        <div class="tw-flex tw-justify-between tw-items-start tw-mb-4">
                                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium
                                                {{ $task->difficulty == 'beginner' ? 'tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-100' : 
                                                   ($task->difficulty == 'intermediate' ? 'tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-100' : 
                                                   'tw-bg-red-50 tw-text-red-700 tw-border tw-border-red-100') }}">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-mr-1.5
                                                    {{ $task->difficulty == 'beginner' ? 'tw-bg-green-500' : 
                                                       ($task->difficulty == 'intermediate' ? 'tw-bg-yellow-500' : 
                                                       'tw-bg-red-500') }}"></span>
                                                {{ ucfirst($task->difficulty) }}
                                            </span>
                                        </div>

                                        <h5 class="tw-text-lg tw-font-bold tw-text-gray-900 tw-mb-3 tw-line-clamp-1 group-hover:tw-text-blue-600 tw-transition-colors">
                                            {{ $task->title }}
                                        </h5>
                                        
                                        <p class="tw-text-gray-500 tw-text-sm tw-line-clamp-2 tw-mb-6 tw-leading-relaxed">
                                            {{ strip_tags($task->description) }}
                                        </p>

                                        @php
                                            $isDone = isset($completedTaskIds) && in_array($task->id, $completedTaskIds);
                                        @endphp
                                        <a href="{{ route('virtual-lab.index', ['task' => $task->id]) }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-full tw-py-2.5 tw-font-semibold tw-rounded-xl tw-border tw-transition-all tw-duration-200
                                               {{ $isDone
                                                   ? 'tw-bg-green-50 tw-text-green-700 tw-border-green-200 hover:tw-bg-green-600 hover:tw-text-white hover:tw-border-green-600'
                                                   : 'tw-bg-gray-50 tw-text-gray-700 tw-border-gray-200 hover:tw-bg-blue-600 hover:tw-text-white hover:tw-border-blue-600' }}">
                                            @if($isDone)
                                                <i class="fas fa-eye tw-mr-2 tw-text-xs"></i>
                                                <span>Review</span>
                                            @else
                                                <span>Kerjakan</span>
                                                <i class="fas fa-chevron-right tw-ml-2 tw-text-xs"></i>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="tw-text-center tw-py-20">
                        <div class="tw-w-24 tw-h-24 tw-bg-gray-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-6">
                            <i class="material-icons tw-text-4xl tw-text-gray-400">assignment_late</i>
                        </div>
                        <h3 class="tw-text-lg tw-font-bold tw-text-gray-900">Belum Ada Tugas</h3>
                        <p class="tw-text-gray-500">Tugas praktikum akan muncul di sini setelah ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
<style>
    /* Custom Tour Styles */
    .introjs-tooltip {
        min-width: 200px;
        max-width: 300px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(59, 130, 246, 0.15); /* Blue glow shadow */
        border: 2px solid #e0e7ff; /* Soft border */
        background: rgba(255, 255, 255, 0.98);
        padding: 0;
        overflow: visible !important;
    }
    
    /* Speech Bubble Arrow pointing to Robot */
    .introjs-tooltip::after {
        content: '';
        position: absolute;
        bottom: -10px; /* Adjust based on robot position */
        left: 50%;
        margin-left: -10px;
        border-width: 10px;
        border-style: solid;
        border-color: #fff transparent transparent transparent;
        z-index: 999;
    }
    
    .introjs-tooltiptext {
        font-size: 14px;
        line-height: 1.6;
        padding: 20px 24px;
        color: #475569;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    

    
    /* Modern Buttons */
    .introjs-button {
        border-radius: 20px; /* Pill shape */
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 700;
        text-shadow: none;
        border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        text-transform: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        margin: 4px;
    }
    
    .introjs-nextbutton {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .introjs-nextbutton:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
    }

    .introjs-prevbutton {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .introjs-prevbutton:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .introjs-skipbutton {
        color: #cbd5e1;
        font-size: 24px;
        font-weight: bold;
        top: 10px;
        right: 15px;
        transition: color 0.2s;
    }
    
    .introjs-skipbutton:hover {
        color: #ef4444; /* Red on hover */
    }
    
    .introjs-helperLayer {
        background-color: rgba(15, 23, 42, 0.75); /* Darker focus */
        backdrop-filter: blur(4px); /* Blur effect */
        border-radius: 16px;
        border: 2px solid rgba(59, 130, 246, 0.5);
    }
    
    /* Progress Dots */
    .introjs-bullets ul li a {
        background: #e2e8f0;
        width: 8px;
        height: 8px;
        transition: all 0.3s;
    }
    
    .introjs-bullets ul li a.active {
        background: #3b82f6;
        width: 20px; /* Elongated active dot */
        border-radius: 4px;
    }
    
    /* Remove default header background */
    .introjs-tooltip-header {
        display: none;
    }
</style>

<script>
    // Tour Guide Script
    document.addEventListener('DOMContentLoaded', function() {
        // Only run if user hasn't seen the tour
        @if(!auth()->user()->has_seen_virtual_lab_tour)
            setTimeout(startVirtualLabTour, 800);
        @endif
    });

    function startVirtualLabTour() {
        const tour = introJs().setOptions({
            steps: [
                {
                    intro: `
                        <div style="padding: 5px 0;">
                            <div class="tw-flex tw-items-center tw-mb-3">
                                <span class="tw-text-2xl tw-mr-2">👋</span>
                                <h3 class="tw-text-lg tw-font-bold tw-text-gray-900">Halo, Sobat!</h3>
                            </div>
                            <p class="tw-text-sm tw-text-gray-600 tw-leading-relaxed">
                                Saya <strong>Robo-Oopedia</strong>! Siap menemani Anda menjelajahi fitur keren di sini?
                            </p>
                        </div>
                    `,
                },
                {
                    element: document.querySelector('.tw-bg-gradient-to-r.tw-from-blue-600'),
                    intro: `
                        <div>
                            <div class="tw-flex tw-items-center tw-mb-2">
                                <span class="tw-bg-blue-100 tw-text-blue-600 tw-p-1 tw-rounded tw-mr-2">
                                    <i class="fas fa-code"></i>
                                </span>
                                <h4 class="tw-font-bold tw-text-gray-900 tw-text-sm">Sandbox Mode</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600">
                                Ruang bebas eksperimen! Coding Java tanpa batasan soal.
                            </p>
                        </div>
                    `,
                    position: 'bottom'
                },
                {
                    element: document.querySelector('.tw-text-2xl.tw-font-bold.tw-text-gray-900'),
                    intro: `
                        <div>
                            <div class="tw-flex tw-items-center tw-mb-2">
                                <span class="tw-bg-green-100 tw-text-green-600 tw-p-1 tw-rounded tw-mr-2">
                                    <i class="fas fa-book"></i>
                                </span>
                                <h4 class="tw-font-bold tw-text-gray-900 tw-text-sm">Tugas Praktikum</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600">
                                Kumpulan tantangan coding yang sudah disesuaikan dengan materi Anda.
                            </p>
                        </div>
                    `,
                    position: 'bottom'
                },
                {
                    element: document.querySelector('.tw-group.tw-relative.tw-bg-white'),
                    intro: `
                        <div>
                            <div class="tw-flex tw-items-center tw-mb-2">
                                <span class="tw-bg-yellow-100 tw-text-yellow-600 tw-p-1 tw-rounded tw-mr-2">
                                    <i class="fas fa-star"></i>
                                </span>
                                <h4 class="tw-font-bold tw-text-gray-900 tw-text-sm">Pilih Tantangan</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600 tw-mb-2">
                                Cek level kesulitannya dulu ya!
                            </p>
                            <div class="tw-inline-block tw-px-2 tw-py-1 tw-bg-blue-50 tw-text-blue-600 tw-rounded tw-text-xs tw-font-bold">
                                Usahakan urut dari level Beginner!
                            </div>
                        </div>
                    `,
                    position: 'right'
                },
                {
                    intro: `
                        <div style="text-align: center; padding: 10px 0;">
                            <div class="tw-w-12 tw-h-12 tw-bg-blue-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-3">
                                <i class="fas fa-rocket tw-text-blue-600 tw-text-xl"></i>
                            </div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-gray-900 tw-mb-1">Siap Coding?</h3>
                            <p class="tw-text-sm tw-text-gray-500">
                                Jangan takut salah, ayo mulai perjalanan coding-mu!
                            </p>
                        </div>
                    `
                }
            ],
            showProgress: true,
            exitOnOverlayClick: false,
            showBullets: true,
            scrollToElement: true,
            nextLabel: 'Lanjut',
            prevLabel: 'Kembali',
            skipLabel: '×',
            doneLabel: 'Gass!',
            hidePrev: false,
            exitOnEsc: true
        });
        

        // Bind event handlers
        tour.oncomplete(function() {
            markTourAsComplete();
        });
        
        tour.onexit(function() {
            markTourAsComplete();
        });
        
        // Start the tour
        tour.start();
    }

    function markTourAsComplete() {
        fetch("{{ route('virtual-lab.tour.complete') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        }).catch(error => console.error('Error marking tour:', error));
    }
</script>
@endpush
