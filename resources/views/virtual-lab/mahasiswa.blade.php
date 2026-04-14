@extends('mahasiswa.layouts.app')

@section('title', 'Virtual Lab Koding')

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
        
        /* Custom Scrollbar for Terminal */
        .terminal-scroll::-webkit-scrollbar { width: 8px; }
        .terminal-scroll::-webkit-scrollbar-track { background: #1f2937; }
        .terminal-scroll::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        
        /* Fix Grid Layout since we disabled preflight */
        .tw-grid { display: grid; }
        
        /* CodeMirror Custom Fixes */
        .CodeMirror { height: 100% !important; font-family: 'Courier New', Courier, monospace !important; border-top: 1px solid #2d3748; }
        .CodeMirror-hints { z-index: 9999 !important; font-family: 'Courier New', Courier, monospace !important; }
    </style>
    <!-- CodeMirror Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/show-hint.min.css">
@endpush

@section('content')
<div class="font-jakarta tw-min-h-screen tw-bg-gray-50/50 tw-pb-8">
    <div class="container-fluid tw-px-4 md:tw-px-8 tw-py-6">
        <!-- Header -->
        <div class="tw-max-w-7xl tw-mx-auto tw-mb-6 tw-flex tw-flex-col md:tw-flex-row md:tw-items-center md:tw-justify-between tw-gap-4">
            <div>
                <div class="tw-flex tw-items-center tw-gap-3">
                    <a href="{{ route('virtual-lab.index') }}" class="tw-group tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-bg-white tw-border tw-border-gray-200 tw-rounded-xl tw-text-gray-500 hover:tw-text-blue-600 hover:tw-border-blue-200 hover:tw-shadow-md tw-transition-all">
                        <i class="fas fa-arrow-left tw-transform group-hover:tw--translate-x-1 tw-transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-tracking-tight">
                            {{ $activeTask ? $activeTask->title : 'Sandbox Mode' }}
                        </h1>
                        <p class="tw-text-gray-500 tw-text-sm">
                            {{ $activeTask ? $activeTask->material->title : 'Eksperimen Bebas' }}
                        </p>
                    </div>
                </div>
            </div>
            
            @if($activeTask)
            <div class="tw-flex tw-items-center tw-gap-3 tw-flex-wrap">
                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-lg tw-text-xs tw-font-bold
                    {{ $activeTask->difficulty == 'beginner' ? 'tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-100' : 
                       ($activeTask->difficulty == 'intermediate' ? 'tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-100' : 
                       'tw-bg-red-50 tw-text-red-700 tw-border tw-border-red-100') }}">
                    {{ ucfirst($activeTask->difficulty) }}
                </span>

                @if(isset($tbutSession) && $tbutSession)

                @if($tbutSession->is_completed)
                {{-- ✅ Completed Banner (read-only mode) --}}
                <div class="tw-flex tw-items-center tw-gap-4 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-xl tw-px-5 tw-py-2.5 tw-shadow-sm">
                    <div class="tw-flex tw-items-center tw-gap-2" title="Task Completion Time">
                        <i class="fas fa-stopwatch tw-text-green-600"></i>
                        <span class="tw-text-green-800 tw-text-xs tw-font-semibold tw-hidden sm:tw-inline">Waktu:</span>
                        <span class="tw-font-mono tw-font-bold tw-text-green-900 tw-text-sm">{{ $tbutSession->formattedDuration() }}</span>
                    </div>
                    <div class="tw-w-px tw-h-5 tw-bg-green-200"></div>

                    <div class="tw-flex tw-items-center tw-gap-2" title="Number of Steps (Execution/Save)">
                        <i class="fas fa-running tw-text-green-600"></i>
                        <span class="tw-text-green-800 tw-text-xs tw-font-semibold tw-hidden sm:tw-inline">Aksi:</span>
                        <span class="tw-font-bold tw-text-green-900 tw-text-sm">{{ $tbutSession->run_count }}x</span>
                    </div>
                    <div class="tw-w-px tw-h-5 tw-bg-green-200"></div>

                    <div class="tw-flex tw-items-center tw-gap-2" title="Task Success Rate">
                        <i class="fas fa-check-circle tw-text-green-600"></i>
                        <span class="tw-text-green-700 tw-text-xs tw-font-bold">Berhasil Selesai</span>
                    </div>
                </div>

                @else
                {{-- 📊 Active TBUT Panel (Skripsi Metrics) --}}
                <div class="tw-flex tw-items-center tw-gap-4 tw-bg-white tw-border tw-border-blue-100 tw-rounded-xl tw-px-5 tw-py-2.5 tw-shadow-sm">
                    <!-- Task Completion Time -->
                    <div class="tw-flex tw-items-center tw-gap-2" title="Task Completion Time">
                        <i class="fas fa-stopwatch tw-text-blue-500"></i>
                        <span class="tw-text-gray-500 tw-text-xs tw-font-semibold tw-hidden sm:tw-inline">Waktu:</span>
                        <span id="tbut-timer" class="tw-font-mono tw-font-bold tw-text-gray-800 tw-text-sm" style="min-width: 45px;">00:00</span>
                    </div>
                    <div class="tw-w-px tw-h-5 tw-bg-gray-200"></div>

                    <!-- Number of Steps -->
                    <div class="tw-flex tw-items-center tw-gap-2" title="Number of Steps (Execution/Save)">
                        <i class="fas fa-running tw-text-amber-500"></i>
                        <span class="tw-text-gray-500 tw-text-xs tw-font-semibold tw-hidden sm:tw-inline">Aksi:</span>
                        <span class="tw-font-bold tw-text-gray-800 tw-text-sm"><span id="tbut-run-count">{{ $tbutSession->run_count }}</span>x</span>
                    </div>
                    <div class="tw-w-px tw-h-5 tw-bg-gray-200"></div>

                    <!-- Task Success Rate (Status) -->
                    <div class="tw-flex tw-items-center tw-gap-2" title="Status Evaluasi Output">
                        @if($tbutSession->is_success)
                            <i class="fas fa-check-circle tw-text-green-500"></i>
                            <span class="tw-text-green-600 tw-text-xs tw-font-bold">Output Tepat!</span>
                        @else
                            <i class="fas fa-spinner fa-spin tw-text-indigo-500"></i>
                            <span class="tw-text-indigo-600 tw-text-xs tw-font-bold">Belum Tepat</span>
                        @endif
                    </div>
                </div>

                {{-- 💾 Save Code Button --}}
                <button type="button" id="btn-save-code"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white tw-border tw-border-blue-200 tw-text-blue-600 hover:tw-bg-blue-50 tw-font-semibold tw-py-2 tw-px-4 tw-rounded-xl tw-text-sm tw-transition-all">
                    <i class="fas fa-save"></i> Simpan
                </button>

                {{-- ✅ Submit Task Button --}}
                <button type="button" id="btn-submit-task"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-bold tw-py-2 tw-px-5 tw-rounded-xl tw-shadow tw-shadow-green-200 tw-text-sm tw-transition-all">
                    <i class="fas fa-check-circle"></i> Submit & Selesai
                </button>
                @endif

                @endif
            </div>
            @endif
        </div>

        <div class="tw-max-w-7xl tw-mx-auto tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6 tw-h-auto lg:tw-h-[calc(100vh-180px)] tw-min-h-[600px]">
            <!-- Instruction Panel (Left Side - 4 Columns) -->
            <div class="lg:tw-col-span-4 tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-200 tw-flex tw-flex-col tw-overflow-hidden tw-h-[500px] lg:tw-h-full">
                <div class="tw-p-4 tw-bg-gray-50/50 tw-border-b tw-border-gray-100 tw-flex tw-items-center tw-justify-between">
                    <h2 class="tw-font-bold tw-text-gray-700 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-book-open tw-text-blue-500"></i> Instruksi
                    </h2>
                </div>
                
                <div class="tw-p-6 tw-flex-1 tw-overflow-y-auto terminal-scroll">
                    @if($activeTask)
                        <div class="prose prose-sm max-w-none tw-text-gray-600">
                            {!! $activeTask->description !!}
                        </div>
                    @else
                        <div class="tw-text-center tw-py-8">
                            <div class="tw-w-16 tw-h-16 tw-bg-blue-50 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                                <i class="fas fa-code tw-text-2xl tw-text-blue-500"></i>
                            </div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-gray-900 tw-mb-2">Sandbox Mode</h3>
                            <p class="tw-text-gray-500 tw-mb-6">
                                Area ini bebas untuk eksperimen kode Java. Silakan buat class dan method sesuka hati.
                            </p>
                            <div class="tw-bg-blue-50 tw-rounded-xl tw-p-4 tw-text-left">
                                <h4 class="tw-font-bold tw-text-blue-700 tw-text-sm tw-mb-2">⚠️ Aturan Main:</h4>
                                <ul class="tw-space-y-2 tw-text-sm tw-text-blue-800">
                                    <li class="tw-flex tw-items-start tw-gap-2">
                                        <i class="fas fa-check-circle tw-mt-1"></i>
                                        <span>Class utama harus bernama <code>Main</code></span>
                                    </li>
                                    <li class="tw-flex tw-items-start tw-gap-2">
                                        <i class="fas fa-check-circle tw-mt-1"></i>
                                        <span>Wajib ada method <code>public static void main</code></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Code Editor (Right Side - 8 Columns) -->
            <form method="POST" action="{{ route('virtual-lab.execute') }}" id="codeForm" class="lg:tw-col-span-8 tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-200 tw-flex tw-flex-col tw-overflow-hidden tw-h-[600px] lg:tw-h-full">
                @csrf
                @if($activeTask)
                    <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
                    <input type="hidden" name="elapsed" id="execute-elapsed">
                @endif
                
                <!-- Editor Toolbar -->
                <div class="tw-bg-gray-50/80 tw-backdrop-blur-sm tw-border-b tw-border-gray-200 tw-p-2 tw-flex tw-justify-between tw-items-end">
                    <!-- Tabs Container (Single Tab - Workspace Mode) -->
                    <div class="tw-flex tw-items-end tw-gap-1 tw-overflow-x-auto no-scrollbar" id="tabs-header">
                        @php
                            $mainFile = (isset($files) && count($files) > 0) ? $files[0] : ['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        // Tulis kode di sini\n    }\n}"];
                            $currentId = 'file-0';
                        @endphp

                        <div class="tab-btn tw-relative tw-px-5 tw-py-2.5 tw-text-sm tw-font-semibold tw-rounded-t-lg tw-flex tw-items-center tw-gap-2 tw-bg-white tw-text-blue-600 tw-shadow-sm tw-border-t-2 tw-border-blue-500 cursor-default">
                            <i class="fab fa-java tw-text-blue-500"></i>
                            <span>{{ $mainFile['filename'] }}</span>
                            <!-- Hidden input required for backend processing -->
                            <input type="hidden" name="files[{{ $currentId }}][filename]" value="{{ $mainFile['filename'] }}">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="tw-mb-1 tw-mr-2">
                        <button type="submit" name="action" value="run" class="tw-inline-flex tw-items-center tw-justify-center tw-bg-blue-600 tw-text-white hover:tw-bg-blue-700 tw-font-bold tw-py-2 tw-px-6 tw-rounded-xl tw-shadow-lg tw-shadow-blue-200 tw-transition-all tw-transform hover:tw-scale-105 active:tw-scale-95">
                            <i class="fas fa-play tw-mr-2 tw-text-xs"></i> Run Code
                        </button>
                    </div>
                </div>

                <!-- Editor Area (Single Frame) -->
                <div class="tw-flex-1 tw-relative tw-bg-[#1e1e1e]" id="editors-container">
                    <div id="{{ $currentId }}" class="editor-pane tw-absolute tw-inset-0 tw-w-full tw-h-full">
                        <textarea id="code-editor-textarea" name="files[{{ $currentId }}][content]"
                                  class="tw-w-full tw-h-full tw-p-6 tw-font-mono tw-text-sm tw-bg-[#1e1e1e] tw-text-gray-200 tw-resize-none focus:tw-outline-none tw-leading-relaxed"
                                  spellcheck="false"
                                  placeholder="// Tulis instruksi kode Java Anda di sini..."
                                  @if(isset($tbutSession) && $tbutSession && $tbutSession->is_completed) readonly @endif
                                  >{{ $mainFile['content'] }}</textarea>
                    </div>
                </div>

                <!-- Terminal: stdin + output -->
                <div class="tw-h-1/3 tw-min-h-[220px] tw-bg-[#0f1115] tw-border-t tw-border-gray-800 tw-flex tw-flex-col">
                    <!-- Stdin panel -->
                    <div class="tw-px-4 tw-pt-2 tw-pb-1 tw-bg-[#1a1c23] tw-border-b tw-border-gray-800 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-keyboard tw-text-yellow-400 tw-text-xs"></i>
                        <span class="tw-text-xs tw-font-mono tw-uppercase tw-tracking-widest tw-text-yellow-400">Program Input (stdin)</span>
                        <span class="tw-text-xs tw-text-gray-500 tw-ml-1">— isi jika kode memakai Scanner</span>
                    </div>
                    <textarea id="stdin-input" name="stdin"
                        rows="2"
                        class="tw-w-full tw-bg-[#131620] tw-text-yellow-200 tw-font-mono tw-text-sm tw-px-4 tw-py-2 tw-resize-none focus:tw-outline-none tw-border-b tw-border-gray-800"
                        placeholder="Contoh: 5&#10;3"
                        spellcheck="false">{{ $stdin ?? '' }}</textarea>

                    <!-- Output header -->
                    <div class="tw-px-4 tw-py-2 tw-bg-[#1a1c23] tw-border-b tw-border-gray-800 tw-flex tw-justify-between tw-items-center">
                        <span class="tw-text-xs tw-font-mono tw-uppercase tw-tracking-widest tw-text-gray-500">Terminal Output</span>
                        
                        <div id="loading-indicator" class="tw-hidden tw-flex tw-items-center tw-gap-2">
                            <div class="tw-w-2 tw-h-2 tw-bg-yellow-400 tw-rounded-full tw-animate-pulse"></div>
                            <span class="tw-text-xs tw-text-yellow-400 tw-font-mono">Compiling...</span>
                        </div>

                        @if(isset($error))
                            <span class="tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-bold {{ $error ? 'tw-bg-red-500/10 tw-text-red-400' : 'tw-bg-green-500/10 tw-text-green-400' }}">
                                {{ $error ? 'Build Failed' : 'Build Success' }}
                            </span>
                        @endif
                    </div>
                    <pre class="tw-flex-1 tw-p-4 tw-font-mono tw-text-sm tw-overflow-auto tw-whitespace-pre-wrap tw-leading-relaxed terminal-scroll tw-text-gray-300">{{ $output ?? '// Hasil eksekusi akan muncul di sini...' }}</pre>
                </div>
            </form>

        </div>
    </div>
</div>

@if(isset($tbutSession) && $tbutSession && $activeTask)

@if($tbutSession->is_completed)
{{-- READ-ONLY mode: no timer, no save, no submit --}}
<div
    style="position:fixed;bottom:24px;right:24px;z-index:9999"
    class="tw-flex tw-items-center tw-gap-3 tw-bg-white tw-border tw-border-green-200 tw-rounded-2xl tw-shadow-lg tw-px-5 tw-py-3">
    <i class="fas fa-lock tw-text-green-600"></i>
    <span class="tw-text-sm tw-font-semibold tw-text-green-700">Mode Review — Kode tidak dapat diubah</span>
    <a href="{{ route('virtual-lab.index') }}"
       class="tw-inline-flex tw-items-center tw-gap-1 tw-bg-gray-100 hover:tw-bg-gray-200 tw-text-gray-700 tw-text-xs tw-font-semibold tw-px-3 tw-py-1.5 tw-rounded-lg tw-transition-all">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@else
{{-- ACTIVE mode: timer + save + submit --}}
<form id="form-save-code" style="display:none">
    @csrf
    <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
    <input type="hidden" name="code" id="save-code-content">
    <input type="hidden" name="elapsed" id="save-elapsed">
</form>

<form id="form-submit-task" method="POST" action="{{ route('virtual-lab.submit-task') }}" style="display:none">
    @csrf
    <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
    <input type="hidden" name="code" id="submit-code-content">
    <input type="hidden" name="elapsed" id="submit-elapsed">
</form>

{{-- Submit Confirmation Modern Modal --}}
<div id="submitTaskModal" class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-flex tw-items-center tw-justify-center tw-opacity-0 tw-pointer-events-none tw-transition-all tw-duration-300" style="z-index: 9999;">
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-2xl tw-w-[90%] tw-max-w-md tw-overflow-hidden tw-transform tw-scale-95 tw-transition-all tw-duration-300" id="submitTaskModalDialog">
        <!-- Banner Header -->
        <div class="tw-relative tw-bg-gradient-to-br tw-from-indigo-600 tw-to-blue-600 tw-p-8 tw-flex tw-flex-col tw-items-center tw-text-center tw-overflow-hidden">
            <!-- Decorative circle -->
            <div class="tw-absolute tw-top-[-20%] tw-right-[-10%] tw-w-32 tw-h-32 tw-bg-white tw-opacity-10 tw-rounded-full"></div>
            
            <div class="tw-w-20 tw-h-20 tw-bg-white/20 tw-backdrop-blur-md tw-border tw-border-white/30 tw-text-white tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-4xl tw-mb-5 tw-shadow-lg tw-relative tw-z-10">
                <i class="fas fa-flag-checkered tw-ml-1"></i>
            </div>
            <h3 class="tw-text-2xl tw-font-bold tw-text-white tw-tracking-wide tw-relative tw-z-10">Kumpulkan Tugas?</h3>
            <p class="tw-text-blue-100 tw-text-sm tw-mt-3 tw-leading-relaxed tw-relative tw-z-10">
                Langkah Anda akan permanen direkam. Periksa kembali kode Anda apakah sudah cukup sempurna sebelum di evaluasi oleh sistem.
            </p>
        </div>
        
        <!-- Metrics Snapshot -->
        <div class="tw-px-6 tw-py-5 tw-bg-gray-50 tw-border-b tw-border-gray-100">
            <h4 class="tw-text-xs tw-font-extrabold tw-text-gray-400 tw-uppercase tw-tracking-widest tw-mb-4 tw-text-center">Ringkasan Metrik Skripsi (TBUT)</h4>
            
            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                <div class="tw-bg-white tw-border tw-border-blue-100 tw-rounded-xl tw-p-4 tw-flex tw-flex-col tw-items-center tw-shadow-sm">
                    <span class="tw-text-gray-500 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-mb-1"><i class="fas fa-stopwatch tw-mr-1"></i> Waktu Selesai</span>
                    <span class="tw-text-xl tw-font-bold tw-text-blue-600 tw-font-mono" id="modal-display-timer">00:00</span>
                </div>
                <div class="tw-bg-white tw-border tw-border-amber-100 tw-rounded-xl tw-p-4 tw-flex tw-flex-col tw-items-center tw-shadow-sm">
                    <span class="tw-text-gray-500 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-mb-1"><i class="fas fa-running tw-mr-1"></i> Total Aksi</span>
                    <span class="tw-text-xl tw-font-bold tw-text-amber-500 tw-font-mono"><span id="modal-display-run">0</span>x</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="tw-p-6 tw-flex tw-items-center tw-justify-center tw-gap-3 tw-bg-white">
            <button type="button" id="btn-cancel-submit" class="tw-flex-1 tw-px-5 tw-py-3 tw-rounded-xl tw-text-gray-600 tw-font-bold tw-bg-gray-100 hover:tw-bg-gray-200 tw-transition-colors">
                Kembali
            </button>
            <button type="button" id="btn-confirm-submit" class="tw-flex-1 tw-px-5 tw-py-3 tw-rounded-xl tw-text-white tw-font-bold tw-bg-green-600 hover:tw-bg-green-700 tw-shadow-lg hover:tw-shadow-xl hover:tw-shadow-green-200 tw-transition-all tw-transform hover:tw--translate-y-0.5">
                <i class="fas fa-paper-plane tw-mr-2"></i> Ya, Kumpulkan!
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    let elapsed = {{ $tbutSession->duration_seconds ?? 0 }};
    const timerEl = document.getElementById('tbut-timer');

    function formatTime(s) {
        const m = Math.floor(s / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return m + ':' + sec;
    }

    if (timerEl) {
        timerEl.textContent = formatTime(elapsed);
        setInterval(() => { elapsed++; timerEl.textContent = formatTime(elapsed); }, 1000);
    }

    const codeForm   = document.getElementById('codeForm');
    const runCountEl = document.getElementById('tbut-run-count');
    if (codeForm && runCountEl) {
        codeForm.addEventListener('submit', function() {
            runCountEl.textContent = parseInt(runCountEl.textContent || '0') + 1;
            const elapsedInput = document.getElementById('execute-elapsed');
            if (elapsedInput) elapsedInput.value = elapsed;
        });
    }

    function getCurrentCode() {
        if (window.editorInstance) {
            return window.editorInstance.getValue();
        }
        const activePane = document.querySelector('.editor-pane:not(.tw-hidden) textarea');
        return activePane ? activePane.value : '';
    }

    const btnSave = document.getElementById('btn-save-code');
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            const code = getCurrentCode();
            const csrf = document.querySelector('#form-save-code [name=_token]').value;
            document.getElementById('save-code-content').value = code;
            document.getElementById('save-elapsed').value = elapsed;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            fetch('{{ route('virtual-lab.save-code') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ task_id: {{ $activeTask->id }}, code: code, elapsed: elapsed })
            })
            .then(r => r.json())
            .then(data => {
                btnSave.disabled = false;
                btnSave.innerHTML = data.success
                    ? '<i class="fas fa-check"></i> Tersimpan!'
                    : '<i class="fas fa-save"></i> Simpan';
                if (data.success) setTimeout(() => { btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan'; }, 2000);
            })
            .catch(() => { btnSave.disabled = false; btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan'; });
        });
    }

    const btnSubmit = document.getElementById('btn-submit-task');
    const modalConfirm = document.getElementById('submitTaskModal');
    const modalDialog = document.getElementById('submitTaskModalDialog');
    const btnCancelSubmit = document.getElementById('btn-cancel-submit');
    const btnConfirmSubmit = document.getElementById('btn-confirm-submit');
    const modalTimerDisp = document.getElementById('modal-display-timer');
    const modalRunDisp = document.getElementById('modal-display-run');

    if (btnSubmit && modalConfirm) {
        // Open Modal
        btnSubmit.addEventListener('click', function() {
            // Update Modal Data
            if(modalTimerDisp && timerEl) modalTimerDisp.textContent = timerEl.textContent;
            if(modalRunDisp && runCountEl) modalRunDisp.textContent = runCountEl.textContent;

            // Show Modal
            modalConfirm.classList.remove('tw-opacity-0', 'tw-pointer-events-none');
            modalConfirm.classList.add('tw-opacity-100');
            modalDialog.classList.remove('tw-scale-95');
            modalDialog.classList.add('tw-scale-100');
        });

        // Close Modal
        const closeModal = () => {
            modalConfirm.classList.remove('tw-opacity-100');
            modalConfirm.classList.add('tw-opacity-0', 'tw-pointer-events-none');
            modalDialog.classList.remove('tw-scale-100');
            modalDialog.classList.add('tw-scale-95');
        };

        if(btnCancelSubmit) btnCancelSubmit.addEventListener('click', closeModal);
        modalConfirm.addEventListener('click', function(e) {
            if (e.target === modalConfirm) closeModal();
        });

        // Confirm Action -> trigger form
        if(btnConfirmSubmit) {
            btnConfirmSubmit.addEventListener('click', function() {
                const code = getCurrentCode();
                document.getElementById('submit-code-content').value = code;
                document.getElementById('submit-elapsed').value = elapsed;
                
                // Show loading state
                btnConfirmSubmit.innerHTML = '<i class="fas fa-spinner fa-spin tw-mr-2"></i> Mengirim...';
                btnConfirmSubmit.disabled = true;
                
                document.getElementById('form-submit-task').submit();
            });
        }
    }

    setInterval(() => { if (btnSave) btnSave.click(); }, 60000);
})();
</script>
@endif

@endif



<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/anyword-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/edit/closebrackets.min.js"></script>
<script>
    console.log('Virtual Lab: Inline Script Loaded (Single Tab Workspace)');

    // Initialize CodeMirror Editor with Automation (Autoclose, Hinting)
    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById("code-editor-textarea");
        if (textarea) {
            window.editorInstance = CodeMirror.fromTextArea(textarea, {
                mode: "text/x-java",
                theme: "dracula",
                lineNumbers: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 4,
                readOnly: textarea.hasAttribute('readonly'),
                extraKeys: {"Ctrl-Space": "autocomplete"}
            });
            window.editorInstance.setSize("100%", "100%");

            // Sync CodeMirror → textarea BEFORE form submits
            // Use capture phase (3rd arg = true) so our handler runs first
            const form = document.getElementById('codeForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Save CodeMirror content back to hidden textarea
                    window.editorInstance.save();

                    // Guard: if code textarea is now empty, warn and stop
                    const ta = document.getElementById('code-editor-textarea');
                    if (ta && ta.value.trim() === '') {
                        e.preventDefault();
                        alert('Editor kosong! Tulis kode Java terlebih dahulu sebelum Run Code.');
                        return;
                    }

                    document.getElementById('loading-indicator').classList.remove('tw-hidden');
                }, true); // capture phase ensures we run before default submission
            }
        }
    });

</script>@endsection
