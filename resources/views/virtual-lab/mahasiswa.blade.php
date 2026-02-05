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
    </style>
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
            <div class="tw-flex tw-items-center tw-gap-3">
                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-lg tw-text-xs tw-font-bold
                    {{ $activeTask->difficulty == 'beginner' ? 'tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-100' : 
                       ($activeTask->difficulty == 'intermediate' ? 'tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-100' : 
                       'tw-bg-red-50 tw-text-red-700 tw-border tw-border-red-100') }}">
                    {{ ucfirst($activeTask->difficulty) }}
                </span>
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
                
                <!-- Editor Toolbar -->
                <div class="tw-bg-gray-50/80 tw-backdrop-blur-sm tw-border-b tw-border-gray-200 tw-p-2 tw-flex tw-justify-between tw-items-end">
                    <!-- Tabs Container -->
                    <div class="tw-flex tw-items-end tw-gap-1 tw-overflow-x-auto no-scrollbar" id="tabs-header">
                        @php
                            $files = isset($files) && count($files) > 0 ? $files : [
                                ['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        // Tulis kode di sini\n    }\n}"]
                            ];
                        @endphp

                        @foreach($files as $index => $file)
                            @php $currentId = 'file-' . $loop->index; @endphp
                            <div class="tab-btn tw-group tw-relative tw-px-4 tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-3 tw-transition-all {{ $loop->first ? 'tw-bg-white tw-text-blue-600 tw-shadow-sm tw-border-t-2 tw-border-blue-500' : 'tw-text-gray-500 hover:tw-bg-gray-100 hover:tw-text-gray-700' }}" 
                                 data-target="{{ $currentId }}"
                                 onclick="window.switchTab('{{ $currentId }}')">
                                <div class="tw-flex tw-items-center tw-gap-2">
                                    <i class="fab fa-java {{ $loop->first ? 'tw-text-blue-500' : 'tw-text-gray-400' }}"></i>
                                    <input type="text" class="tw-bg-transparent tw-border-none tw-outline-none tw-w-24 tw-cursor-pointer focus:tw-cursor-text filename-input tw-font-medium" 
                                           value="{{ $file['filename'] }}" 
                                           onchange="window.updateFilename(this, '{{ $currentId }}')"
                                           onclick="this.focus()">
                                </div>
                                
                                <input type="hidden" name="files[{{ $currentId }}][filename]" id="input-name-{{ $currentId }}" value="{{ $file['filename'] }}">
                                
                                <span class="tw-opacity-0 group-hover:tw-opacity-100 tw-p-1 hover:tw-bg-red-50 hover:tw-text-red-500 tw-rounded-full tw-transition-all" onclick="window.removeFile('{{ $currentId }}', event)">
                                    <svg class="tw-w-3 tw-h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </span>
                            </div>
                        @endforeach
                        
                        <button type="button" id="add-file-btn" onclick="window.addNewFile()" class="tw-ml-1 tw-p-2 tw-text-gray-400 hover:tw-text-blue-600 hover:tw-bg-blue-50 tw-rounded-lg tw-transition-all" title="Tambah File Baru">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="tw-mb-1 tw-mr-2">
                        <button type="submit" name="action" value="run" class="tw-inline-flex tw-items-center tw-justify-center tw-bg-blue-600 tw-text-white hover:tw-bg-blue-700 tw-font-bold tw-py-2 tw-px-6 tw-rounded-xl tw-shadow-lg tw-shadow-blue-200 tw-transition-all tw-transform hover:tw-scale-105 active:tw-scale-95">
                            <i class="fas fa-play tw-mr-2 tw-text-xs"></i> Run Code
                        </button>
                    </div>
                </div>

                <!-- Editor Area -->
                <div class="tw-flex-1 tw-relative tw-bg-[#1e1e1e]" id="editors-container">
                    @foreach($files as $index => $file)
                        @php $currentId = 'file-' . $loop->index; @endphp
                        <div id="{{ $currentId }}" class="editor-pane tw-absolute tw-inset-0 tw-w-full tw-h-full {{ $loop->first ? '' : 'tw-hidden' }}">
                            <textarea name="files[{{ $currentId }}][content]" 
                                      class="tw-w-full tw-h-full tw-p-6 tw-font-mono tw-text-sm tw-bg-[#1e1e1e] tw-text-gray-200 tw-resize-none focus:tw-outline-none tw-leading-relaxed"
                                      spellcheck="false"
                                      placeholder="// Tulis kode Java di sini...">{{ $file['content'] }}</textarea>
                        </div>
                    @endforeach
                </div>

                <!-- Terminal Output -->
                <div class="tw-h-1/3 tw-min-h-[180px] tw-bg-[#0f1115] tw-border-t tw-border-gray-800 tw-flex tw-flex-col">
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

<script>
    console.log('Virtual Lab: Inline Script Loaded');
    
    // Global Constants for Stylings
    const ACTIVE_TAB_CLASSES = 'tab-btn tw-group tw-relative tw-px-4 tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-3 tw-transition-all tw-bg-white tw-text-blue-600 tw-shadow-sm tw-border-t-2 tw-border-blue-500';
    const INACTIVE_TAB_CLASSES = 'tab-btn tw-group tw-relative tw-px-4 tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-3 tw-transition-all tw-text-gray-500 hover:tw-bg-gray-100 hover:tw-text-gray-700';

    // Robust JS Logic for Tab Management
    // Explicitly bind to window to ensure global access
    window.switchTab = function(fileId) {
        console.log('Switching to', fileId);
        
        // Update Tabs Visuals
        document.querySelectorAll('.tab-btn').forEach(btn => {
            const icon = btn.querySelector('.fab.fa-java');
            
            if (btn.dataset.target === fileId) {
                // Active Style
                btn.className = ACTIVE_TAB_CLASSES;
                if(icon) {
                    icon.classList.remove('tw-text-gray-400');
                    icon.classList.add('tw-text-blue-500');
                }
            } else {
                // Inactive Style
                btn.className = INACTIVE_TAB_CLASSES;
                if(icon) {
                    icon.classList.remove('tw-text-blue-500');
                    icon.classList.add('tw-text-gray-400');
                }
            }
        });

        // Update Editors Visibility
        document.querySelectorAll('.editor-pane').forEach(pane => {
            if (pane.id === fileId) {
                pane.classList.remove('tw-hidden');
            } else {
                pane.classList.add('tw-hidden');
            }
        });
    }

    // Add New File
    window.addNewFile = function() {
        console.log('Adding new file');
        const container = document.getElementById('editors-container');
        const header = document.getElementById('tabs-header');
        const addBtn = document.getElementById('add-file-btn'); // Get explicit button
        
        // Count existing files
        const currentCount = container.children.length;
        if (currentCount >= 5) {
            alert('Maksimal 5 file.');
            return;
        }
        
        // Use simpler ID generation
        const newId = 'file-' + Date.now(); 
        const newName = 'File' + (currentCount + 1) + '.java';

        // 1. Add Editor Pane
        const newEditor = document.createElement('div');
        newEditor.id = newId;
        newEditor.className = 'editor-pane tw-absolute tw-inset-0 tw-w-full tw-h-full tw-hidden';
        newEditor.innerHTML = `
            <textarea name="files[${newId}][content]" 
                  class="tw-w-full tw-h-full tw-p-6 tw-font-mono tw-text-sm tw-bg-[#1e1e1e] tw-text-gray-200 tw-resize-none focus:tw-outline-none tw-leading-relaxed"
                  spellcheck="false"
                  placeholder="// Tulis kode Java di sini..."></textarea>
        `;
        container.appendChild(newEditor);

        // 2. Add Tab Button (NEW STRUCTURE)
        const newTab = document.createElement('div');
        // Default to inactive state initially
        newTab.className = INACTIVE_TAB_CLASSES;
        newTab.dataset.target = newId;
        newTab.onclick = function() { window.switchTab(newId) };
        
        newTab.innerHTML = `
            <div class="tw-flex tw-items-center tw-gap-2">
                <i class="fab fa-java tw-text-gray-400"></i>
                <input type="text" class="tw-bg-transparent tw-border-none tw-outline-none tw-w-24 tw-cursor-pointer focus:tw-cursor-text filename-input tw-font-medium" 
                       value="${newName}" 
                       onchange="window.updateFilename(this, '${newId}')"
                       onclick="this.focus()">
            </div>
            
            <input type="hidden" name="files[${newId}][filename]" id="input-name-${newId}" value="${newName}">
            
            <span class="tw-opacity-0 group-hover:tw-opacity-100 tw-p-1 hover:tw-bg-red-50 hover:tw-text-red-500 tw-rounded-full tw-transition-all" onclick="window.removeFile('${newId}', event)">
                <svg class="tw-w-3 tw-h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </span>
        `;
        
        // Insert BEFORE the Add Button
        if (addBtn && header.contains(addBtn)) {
            header.insertBefore(newTab, addBtn);
        } else {
            header.appendChild(newTab); // Fallback
        }
        
        // Switch to new tab
        window.switchTab(newId);
    }

    // Remove File
    window.removeFile = function(fileId, event) {
        if (event) event.stopPropagation();
        console.log('Removing file', fileId);
        
        const container = document.getElementById('editors-container');
        if (container.children.length <= 1) {
            alert('Minimal 1 file.');
            return;
        }

        const tab = document.querySelector(`.tab-btn[data-target="${fileId}"]`);
        const editor = document.getElementById(fileId);
        
        // Check if we are deleting the currently active tab
        // Check using the active class-marker 'tw-border-blue-500'
        const isActive = tab && tab.classList.contains('tw-border-blue-500'); 
        
        if (tab) tab.remove();
        if (editor) editor.remove();
        
        // If active tab removed, switch to the first available tab
        if (isActive) {
            const firstTab = document.querySelector('.tab-btn');
            if (firstTab) {
                window.switchTab(firstTab.dataset.target);
            }
        }
    }

    // Update Filename Hidden Input
    window.updateFilename = function(input, fileId) {
        const hiddenInput = document.getElementById('input-name-' + fileId);
        if (hiddenInput) {
            hiddenInput.value = input.value;
        }
    }

    // Loading Indicator
    const form = document.getElementById('codeForm');
    if (form) {
        form.addEventListener('submit', function() {
            document.getElementById('loading-indicator').classList.remove('tw-hidden');
        });
    }
</script>@endsection
