<x-layout bodyClass="g-sidenav-show bg-gray-100">
    <!-- Inject Tailwind (Priority High) -->
    @push('head')
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                prefix: 'tw-',
                corePlugins: {
                    preflight: false, // DISABLE Preflight to protect Bootstrap Sidebar
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            .font-inter { font-family: 'Inter', sans-serif; }
            
            /* Custom Scrollbar for Terminal */
            .terminal-scroll::-webkit-scrollbar { width: 8px; }
            .terminal-scroll::-webkit-scrollbar-track { background: #1f2937; }
            .terminal-scroll::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
            
            /* Fix Grid Layout since we disabled preflight */
            .tw-grid { display: grid; }
        </style>
    @endpush

    <x-navbars.sidebar activePage="virtual-lab" 
        :userName="auth()->user()->name" 
        :userRole="auth()->user()->role->name ?? 'User'"
        :materials="[]" />
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg font-inter">
        <x-navbars.navs.auth titlePage="Virtual Lab Koding" />
        
        <div class="container-fluid py-4 tw-min-h-screen tw-bg-gray-100">
            <!-- Header -->
            <div class="tw-mb-6">
                <h1 class="tw-text-3xl md:tw-text-4xl tw-font-bold tw-text-gray-900">Virtual Lab Koding</h1>
                <p class="tw-text-gray-600 tw-text-lg tw-mt-2">
                    Praktikkan konsep Pemrograman Berbasis Objek secara langsung di sini.
                </p>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-8 tw-h-auto lg:tw-h-[calc(100vh-220px)] tw-min-h-[600px]">
                <!-- Instruction Panel -->
                <div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-p-6 tw-flex tw-flex-col tw-h-full tw-overflow-hidden tw-mb-4 lg:tw-mb-0">
                    <div class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-mb-4 tw-border-b tw-pb-2">Instruksi Praktikum</div>
                    <div class="prose max-w-none tw-text-gray-700 tw-flex-1 tw-overflow-y-auto tw-pr-2 terminal-scroll">
                        <h3 class="tw-text-lg tw-font-bold tw-text-blue-600 tw-mb-2">Modul 1: Class dan Object</h3>
                        <p class="tw-mb-4 tw-text-sm tw-leading-relaxed">
                            Pada praktikum ini, Anda akan belajar cara membuat <code>class</code> dan <code>object</code> di Java. 
                            <code>Class</code> adalah cetakan, dan <code>object</code> adalah hasil dari cetakan tersebut.
                        </p>
                        
                        <div class="tw-bg-blue-50 tw-border-l-4 tw-border-blue-500 tw-p-4 tw-mb-4">
                            <h4 class="tw-font-bold tw-text-blue-700 tw-text-sm tw-mb-2">Tugas Anda:</h4>
                            <ul class="tw-list-disc tw-list-inside tw-text-sm tw-space-y-1 tw-text-gray-700">
                                <li>Tambahkan atribut <code>nama</code> (String) dan <code>nim</code> (String) pada <code>class Mahasiswa</code>.</li>
                                <li>Buat method <code>tampilkanInfo()</code> di dalam <code>class Mahasiswa</code>.</li>
                                <li>Di metod <code>main</code>, buat object <code>Mahasiswa</code>, isi atribut, dan panggil methodnya.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Code Editor & Output Panel -->
                <form method="POST" action="{{ route('virtual-lab.execute') }}" id="codeForm" class="tw-flex tw-flex-col tw-h-full tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-relative">
                    @csrf
                    
                    <!-- Toolbar -->
                    <div class="tw-bg-gray-50 tw-border-b tw-border-gray-200 tw-p-3 tw-flex tw-justify-between tw-items-center">
                        <div class="tw-flex tw-items-center tw-space-x-1 tw-overflow-x-auto" id="tabs-header">
                            {{-- Server Side Rendered Tabs --}}
                            @php
                                $files = isset($files) && count($files) > 0 ? $files : [
                                    ['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        // Tulis kode di sini\n    }\n}"],
                                    ['filename' => 'Mahasiswa.java', 'content' => "class Mahasiswa {\n    // Class definition\n}"]
                                ];
                            @endphp

                            @foreach($files as $index => $file)
                                {{-- Use Loop Index to Ensure 0, 1, 2... IDs --}}
                                @php $currentId = 'file-' . $loop->index; @endphp
                                <div class="tab-btn tw-px-3 tw-py-1.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-2 {{ $loop->first ? 'tw-bg-white tw-text-blue-600 tw-border-t-2 tw-border-blue-600 tw-shadow-sm' : 'tw-text-gray-500 hover:tw-text-gray-700 hover:tw-bg-gray-100' }}" 
                                     data-target="{{ $currentId }}"
                                     onclick="window.switchTab('{{ $currentId }}')">
                                    <i class="fab fa-java tw-text-xs"></i>
                                    {{-- Allow editing filename --}}
                                    <input type="text" class="tw-bg-transparent tw-border-none tw-outline-none tw-w-24 tw-text-center tw-cursor-text filename-input" 
                                           value="{{ $file['filename'] }}" 
                                           onchange="window.updateFilename(this, '{{ $currentId }}')"
                                           onclick="this.focus()">
                                    
                                    {{-- Hidden input for filename --}}
                                    <input type="hidden" name="files[{{ $currentId }}][filename]" id="input-name-{{ $currentId }}" value="{{ $file['filename'] }}">
                                    
                                    {{-- Remove Button --}}
                                    <span class="tw-ml-1 tw-text-gray-400 hover:tw-text-red-500" onclick="window.removeFile('{{ $currentId }}', event)">
                                        &times;
                                    </span>
                                </div>
                            @endforeach
                            
                            {{-- Add Button --}}
                            <button type="button" id="add-file-btn" onclick="window.addNewFile()" class="tw-ml-2 tw-text-gray-400 hover:tw-text-blue-600 tw-transition-colors" title="Tambah File">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>

                        <div class="tw-flex tw-space-x-2">
                            <button type="submit" name="action" value="run" class="tw-bg-blue-600 tw-text-white hover:tw-bg-blue-700 tw-px-4 tw-py-1.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-shadow tw-transition-all tw-flex tw-items-center">
                                <i class="fas fa-play tw-mr-2"></i> Run
                            </button>
                        </div>
                    </div>

                    <!-- Editor Areas (Server Side Rendered) -->
                    <div class="tw-flex-1 tw-relative tw-bg-gray-50" id="editors-container">
                        @foreach($files as $index => $file)
                            @php $currentId = 'file-' . $loop->index; @endphp
                            <div id="{{ $currentId }}" class="editor-pane tw-absolute tw-inset-0 tw-w-full tw-h-full {{ $loop->first ? '' : 'tw-hidden' }}">
                                <textarea name="files[{{ $currentId }}][content]" 
                                          class="tw-w-full tw-h-full tw-p-4 tw-font-mono tw-text-sm tw-bg-gray-50 tw-text-gray-800 tw-resize-none focus:tw-outline-none focus:tw-bg-white tw-transition-colors"
                                          placeholder="// Tulis kode di sini...">{{ $file['content'] }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <!-- Terminal Output -->
                    <div class="tw-bg-gray-900 tw-text-gray-300 tw-border-t tw-border-gray-700 tw-flex tw-flex-col tw-h-1/3 tw-min-h-[150px]">
                        <div class="tw-flex tw-justify-between tw-items-center tw-px-4 tw-py-2 tw-bg-gray-800 tw-border-b tw-border-gray-700">
                            <span class="tw-text-xs tw-font-mono tw-uppercase tw-tracking-wider tw-text-gray-400">Terminal Output</span>
                            <div id="loading-indicator" class="tw-hidden tw-text-xs tw-yellow-400 tw-animate-pulse">Running Code...</div>
                            @if(isset($error))
                                <span class="tw-text-xs tw-text-{{ $error ? 'red' : 'green' }}-400">
                                    {{ $error ? 'Error' : 'Success' }}
                                </span>
                            @endif
                        </div>
                        <pre class="tw-flex-1 tw-p-4 tw-font-mono tw-text-sm tw-overflow-auto tw-whitespace-pre-wrap tw-leading-tight terminal-scroll tw-text-gray-300">{{ $output ?? '// Hasil eksekusi akan muncul di sini...' }}</pre>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        console.log('Virtual Lab: Inline Script Loaded');
        
        // Robust JS Logic for Tab Management
        // Explicitly bind to window to ensure global access
        window.switchTab = function(fileId) {
            console.log('Switching to', fileId);
            
            // Update Tabs Visuals
            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.dataset.target === fileId) {
                    // Active Style
                    btn.className = 'tab-btn tw-px-3 tw-py-1.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-2 tw-bg-white tw-text-blue-600 tw-border-t-2 tw-border-blue-600 tw-shadow-sm';
                } else {
                    // Inactive Style
                    btn.className = 'tab-btn tw-px-3 tw-py-1.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-2 tw-text-gray-500 hover:tw-text-gray-700 hover:tw-bg-gray-100';
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
                      class="tw-w-full tw-h-full tw-p-4 tw-font-mono tw-text-sm tw-bg-gray-50 tw-text-gray-800 tw-resize-none focus:tw-outline-none focus:tw-bg-white tw-transition-colors"
                      placeholder="// Tulis kode di sini..."></textarea>
            `;
            container.appendChild(newEditor);
    
            // 2. Add Tab Button
            const newTab = document.createElement('div');
            newTab.className = 'tab-btn tw-px-3 tw-py-1.5 tw-text-sm tw-font-medium tw-rounded-t-lg tw-cursor-pointer tw-flex tw-items-center tw-gap-2 tw-text-gray-500 hover:tw-text-gray-700 hover:tw-bg-gray-100';
            newTab.dataset.target = newId;
            newTab.onclick = function() { window.switchTab(newId) };
            
            newTab.innerHTML = `
                <i class="fab fa-java tw-text-xs"></i>
                <input type="text" class="tw-bg-transparent tw-border-none tw-outline-none tw-w-24 tw-text-center tw-cursor-text filename-input" 
                       value="${newName}" 
                       onchange="window.updateFilename(this, '${newId}')">
                <input type="hidden" name="files[${newId}][filename]" id="input-name-${newId}" value="${newName}">
                <span class="tw-ml-1 tw-text-gray-400 hover:tw-text-red-500" onclick="window.removeFile('${newId}', event)">
                    &times;
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
            const isActive = tab && tab.classList.contains('tw-border-blue-600'); 
            
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
    </script>
</x-layout>
