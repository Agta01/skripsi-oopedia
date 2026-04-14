<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />

    @push('head')
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                prefix: 'tw-',
                corePlugins: { preflight: false }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endpush

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Virtual Lab Koding" />

        <div class="container-fluid tw-py-8 tw-min-h-screen" style="font-family: 'Inter', sans-serif; background:#f8faff">

            {{-- ===== HERO HEADER ===== --}}
            <div class="tw-relative tw-overflow-hidden tw-rounded-2xl tw-mb-10"
                 style="background: linear-gradient(135deg, #0057B8 0%, #003b7d 100%); box-shadow: 0 12px 40px rgba(0,87,184,0.35)">
                {{-- Background blobs --}}
                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-64 tw-h-64 tw-rounded-full"
                     style="background:rgba(255,255,255,0.06);transform:translate(30%,-30%);filter:blur(20px)"></div>
                <div class="tw-absolute tw-bottom-0 tw-left-0 tw-w-48 tw-h-48 tw-rounded-full"
                     style="background:rgba(255,255,255,0.04);transform:translate(-30%,30%);filter:blur(16px)"></div>

                <div class="tw-relative tw-p-8 md:tw-p-10 tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-justify-between tw-gap-6">
                    <div class="tw-flex-1 tw-text-center md:tw-text-left">
                        <span class="tw-inline-block tw-text-xs tw-font-bold tw-tracking-widest tw-text-blue-200 tw-uppercase tw-mb-3"
                              style="letter-spacing:.15em">Mode Dosen</span>
                        <h1 class="tw-text-3xl md:tw-text-4xl tw-font-extrabold tw-text-white tw-mb-2" style="letter-spacing:-.5px">
                            Virtual Lab Koding
                        </h1>
                        <p class="tw-text-blue-100 tw-text-base tw-leading-relaxed tw-max-w-xl">
                            Tinjau tugas praktikum mahasiswa atau coba langsung di <strong>Sandbox Mode</strong> — ruang bebas bereksperimen kode Java.
                        </p>
                    </div>
                    <div class="tw-flex-shrink-0">
                        <a href="{{ route('virtual-lab.index', ['mode' => 'sandbox']) }}"
                           class="tw-group tw-inline-flex tw-items-center tw-gap-3 tw-bg-white tw-py-3.5 tw-px-7 tw-rounded-xl tw-font-bold tw-text-blue-700 tw-transition-all tw-duration-200 hover:tw-shadow-2xl hover:-tw-translate-y-0.5"
                           style="box-shadow:0 6px 20px rgba(255,255,255,0.2)">
                            <i class="material-icons" style="font-size:20px">code</i>
                            Mulai Sandbox Coding
                            <i class="material-icons tw-transform group-hover:tw-translate-x-1 tw-transition-transform" style="font-size:18px">arrow_forward</i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== STATS ROW ===== --}}
            @php
                $totalTasks = $materials->pluck('virtualLabTasks')->flatten()->count();
                $totalMats  = $materials->filter(fn($m) => $m->virtualLabTasks->count() > 0)->count();
            @endphp
            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 tw-gap-4 tw-mb-10">
                @php
                    $statCards = [
                        ['icon'=>'library_books',   'label'=>'Total Materi',   'value'=>$totalMats,  'color'=>'#0057B8','bg'=>'rgba(0,87,184,.1)'],
                        ['icon'=>'assignment',       'label'=>'Total Tugas',    'value'=>$totalTasks, 'color'=>'#2dce89','bg'=>'rgba(45,206,137,.1)'],
                        ['icon'=>'signal_cellular_alt','label'=>'Beginner',   'value'=>$materials->pluck('virtualLabTasks')->flatten()->where('difficulty','beginner')->count(), 'color'=>'#2dce89','bg'=>'rgba(45,206,137,.08)'],
                        ['icon'=>'bar_chart',        'label'=>'Intermediate+', 'value'=>$materials->pluck('virtualLabTasks')->flatten()->whereIn('difficulty',['intermediate','advanced'])->count(), 'color'=>'#fb6340','bg'=>'rgba(251,99,64,.1)'],
                    ];
                @endphp
                @foreach($statCards as $s)
                <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-flex tw-items-center tw-gap-4"
                     style="box-shadow:0 4px 16px rgba(0,0,0,0.06);border:1px solid rgba(0,0,0,0.04);transition:transform .2s,box-shadow .2s"
                     onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 28px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.06)'">
                    <div class="tw-flex-shrink-0 tw-w-12 tw-h-12 tw-rounded-xl tw-flex tw-items-center tw-justify-center"
                         style="background:{{ $s['bg'] }}">
                        <i class="material-icons" style="color:{{ $s['color'] }};font-size:22px">{{ $s['icon'] }}</i>
                    </div>
                    <div>
                        <p class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-mb-0.5" style="color:#8392ab;letter-spacing:.5px">{{ $s['label'] }}</p>
                        <p class="tw-text-2xl tw-font-bold tw-mb-0" style="color:#344767;line-height:1.1">{{ $s['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ===== TASK LIST ===== --}}
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                <div>
                    <h2 class="tw-text-xl tw-font-bold tw-mb-0.5" style="color:#344767">Daftar Tugas Praktikum</h2>
                    <p class="tw-text-sm tw-mb-0" style="color:#8392ab">Pilih tugas untuk ditinjau atau coba langsung</p>
                </div>
                <a href="{{ route('admin.virtual-lab-tasks.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-sm tw-font-semibold tw-text-white tw-py-2.5 tw-px-5 tw-rounded-xl tw-transition-all tw-duration-200"
                   style="background:linear-gradient(135deg,#0057B8,#003b7d);box-shadow:0 4px 12px rgba(0,87,184,.3)"
                   onmouseover="this.style.boxShadow='0 8px 20px rgba(0,87,184,.45)';this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.boxShadow='0 4px 12px rgba(0,87,184,.3)';this.style.transform=''">
                    <i class="material-icons" style="font-size:16px">add</i> Tambah Tugas
                </a>
            </div>

            @forelse($materials as $material)
                @if($material->virtualLabTasks->count() > 0)
                {{-- Material Group Header --}}
                <div class="tw-flex tw-items-center tw-gap-3 tw-mb-5 tw-mt-8 tw-first:tw-mt-0">
                    <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-flex-shrink-0"
                         style="background:linear-gradient(135deg,#0057B8,#003b7d)">
                        <i class="material-icons tw-text-white" style="font-size:18px">library_books</i>
                    </div>
                    <h3 class="tw-text-lg tw-font-bold tw-mb-0" style="color:#344767">{{ $material->title }}</h3>
                    <div class="tw-flex-1 tw-h-px" style="background:linear-gradient(90deg,rgba(0,87,184,.2),transparent)"></div>
                    <span class="tw-text-xs tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full"
                          style="background:rgba(0,87,184,.08);color:#0057B8">
                        {{ $material->virtualLabTasks->count() }} tugas
                    </span>
                </div>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-5 tw-mb-6">
                    @foreach($material->virtualLabTasks as $task)
                    @php
                        $diffMap = [
                            'beginner'     => ['label'=>'Beginner',     'color'=>'#1a9e63','bg'=>'rgba(45,206,137,.1)' ,'dot'=>'#2dce89'],
                            'intermediate' => ['label'=>'Intermediate', 'color'=>'#d94a28','bg'=>'rgba(251,99,64,.1)'  ,'dot'=>'#fb6340'],
                            'advanced'     => ['label'=>'Advanced',     'color'=>'#b41a3a','bg'=>'rgba(245,54,92,.1)'  ,'dot'=>'#f5365c'],
                        ];
                        $d = $diffMap[$task->difficulty] ?? $diffMap['beginner'];
                    @endphp
                    <div class="tw-group tw-bg-white tw-rounded-2xl tw-flex tw-flex-col tw-overflow-hidden tw-transition-all tw-duration-250"
                         style="box-shadow:0 4px 16px rgba(0,0,0,0.06);border:1px solid rgba(0,0,0,0.05)"
                         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(0,87,184,.12)'"
                         onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.06)'">
                        {{-- Card top accent --}}
                        <div class="tw-h-1 tw-w-full" style="background:linear-gradient(90deg,#0057B8,#003b7d)"></div>

                        <div class="tw-p-6 tw-flex-1">
                            {{-- Difficulty + Edit --}}
                            <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-bold tw-px-2.5 tw-py-1 tw-rounded-lg"
                                      style="background:{{ $d['bg'] }};color:{{ $d['color'] }}">
                                    <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-flex-shrink-0" style="background:{{ $d['dot'] }}"></span>
                                    {{ $d['label'] }}
                                </span>
                                <a href="{{ route('admin.virtual-lab-tasks.edit', $task->id) }}"
                                   class="tw-flex tw-items-center tw-gap-1 tw-text-xs tw-font-semibold tw-px-2.5 tw-py-1 tw-rounded-lg tw-transition-colors"
                                   style="background:rgba(0,87,184,.07);color:#0057B8"
                                   onmouseover="this.style.background='#0057B8';this.style.color='#fff'"
                                   onmouseout="this.style.background='rgba(0,87,184,.07)';this.style.color='#0057B8'">
                                    <i class="material-icons" style="font-size:13px">edit</i> Edit
                                </a>
                            </div>

                            {{-- Title --}}
                            <h5 class="tw-font-bold tw-text-base tw-mb-2 tw-leading-snug tw-transition-colors"
                                style="color:#344767">{{ $task->title }}</h5>

                            {{-- Description --}}
                            <p class="tw-text-sm tw-leading-relaxed tw-line-clamp-2 tw-mb-0" style="color:#8392ab">
                                {{ Str::limit(strip_tags($task->description), 80) }}
                            </p>
                        </div>

                        {{-- Card Footer --}}
                        <div class="tw-px-6 tw-py-4" style="border-top:1px solid rgba(0,0,0,0.05)">
                            <a href="{{ route('virtual-lab.index', ['task' => $task->id]) }}"
                               class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-w-full tw-py-2.5 tw-rounded-xl tw-font-semibold tw-text-sm tw-transition-all tw-duration-200"
                               style="background:rgba(0,87,184,.07);color:#0057B8;border:1px solid rgba(0,87,184,.15)"
                               onmouseover="this.style.background='#0057B8';this.style.color='#fff';this.style.border='1px solid #0057B8'"
                               onmouseout="this.style.background='rgba(0,87,184,.07)';this.style.color='#0057B8';this.style.border='1px solid rgba(0,87,184,.15)'">
                                <i class="material-icons" style="font-size:16px">play_circle</i>
                                Preview / Coba
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            @empty
                <div class="tw-text-center tw-py-20 tw-bg-white tw-rounded-2xl" style="box-shadow:0 4px 16px rgba(0,0,0,0.06)">
                    <div class="tw-w-20 tw-h-20 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-5"
                         style="background:rgba(0,87,184,.07)">
                        <i class="material-icons" style="font-size:40px;color:#0057B8">assignment_late</i>
                    </div>
                    <h3 class="tw-text-lg tw-font-bold tw-mb-1" style="color:#344767">Belum Ada Tugas Praktikum</h3>
                    <p class="tw-text-sm tw-mb-5" style="color:#8392ab">Buat tugas pertama untuk mulai menggunakan Virtual Lab.</p>
                    <a href="{{ route('admin.virtual-lab-tasks.create') }}"
                       class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-semibold tw-text-white tw-py-2.5 tw-px-6 tw-rounded-xl"
                       style="background:linear-gradient(135deg,#0057B8,#003b7d)">
                        <i class="material-icons" style="font-size:16px">add</i> Buat Tugas Baru
                    </a>
                </div>
            @endforelse

        </div>
    </main>
</x-layout>
