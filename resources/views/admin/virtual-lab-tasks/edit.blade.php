<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="virtual-lab-tasks" :userName="auth()->user()->name" :userRole="auth()->user()->role->role_name" />
    <main class="main-content position-relative border-radius-lg">
        <x-navbars.navs.auth titlePage="Edit Tugas Virtual Lab" />
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Edit Tugas: {{ $virtualLabTask->title }}</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <form action="{{ route('admin.virtual-lab-tasks.update', $virtualLabTask->id) }}" method="POST" class="px-4 py-3">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label for="material_id" class="ms-0">Pilih Materi Sub-Bab</label>
                                            <select class="form-control" id="material_id" name="material_id" required>
                                                <option value="">-- Pilih Materi --</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}" {{ $virtualLabTask->material_id == $material->id ? 'selected' : '' }}>
                                                        {{ $material->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label for="difficulty" class="ms-0">Tingkat Kesulitan</label>
                                            <select class="form-control" id="difficulty" name="difficulty" required>
                                                <option value="beginner" {{ $virtualLabTask->difficulty == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="intermediate" {{ $virtualLabTask->difficulty == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="advanced" {{ $virtualLabTask->difficulty == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group input-group-outline mb-4 is-filled">
                                    <label class="form-label">Judul Tugas</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $virtualLabTask->title) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Deskripsi Tugas & Instruksi</label>
                                    <textarea name="description" class="form-control tinymce" rows="10">{{ old('description', $virtualLabTask->description) }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Template Code (Starter Code)</label>
                                    <small class="d-block text-muted mb-2">Kode ini yang akan muncul pertama kali di editor siswa.</small>
                                    <textarea name="template_code" class="form-control font-monospace" rows="10" style="background: #f5f5f5; font-family: 'Courier New', Courier, monospace;">{{ old('template_code', $virtualLabTask->template_code) }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Test Cases (JSON Output)</label>
                                    <small class="d-block text-muted mb-2">Opsional. Format JSON untuk input/output testing (Future Feature).</small>
                                    <textarea name="test_cases" class="form-control font-monospace" rows="5" placeholder='[{"input": "5", "output": "25"}]'>{{ old('test_cases', $virtualLabTask->test_cases ? json_encode($virtualLabTask->test_cases) : '') }}</textarea>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('admin.virtual-lab-tasks.index') }}" class="btn btn-light me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
