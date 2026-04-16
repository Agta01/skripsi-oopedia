<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\VirtualLabTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VirtualLabTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VirtualLabTask::with(['material', 'creator']);

        if ($request->has('material_id') && $request->material_id != '') {
            $query->where('material_id', $request->material_id);
        }

        $tasks = $query->latest()->paginate(10);
        $materials = Material::all();

        return view('admin.virtual-lab-tasks.index', compact('tasks', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        return view('admin.virtual-lab-tasks.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id'           => 'required|exists:materials,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'required|string',
            'template_code'         => 'required|string',
            'expected_output'       => 'nullable|string',
            'expected_result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deadline_minutes'      => 'nullable|integer|min:1|max:480',
            'difficulty'            => 'required|in:beginner,intermediate,advanced',
            'test_cases'            => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['created_by'] = auth()->id();

        // Handle test_cases if provided as JSON string
        if (!empty($validated['test_cases'])) {
            $validated['test_cases'] = json_decode($validated['test_cases'], true);
        }

        // Handle expected result image upload
        if ($request->hasFile('expected_result_image')) {
            $validated['expected_result_image'] = $request->file('expected_result_image')
                ->store('expected-results', 'public');
        }

        VirtualLabTask::create($validated);

        return redirect()->route('admin.virtual-lab-tasks.index')
            ->with('success', 'Tugas Virtual Lab berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VirtualLabTask $virtualLabTask)
    {
        $materials = Material::all();
        return view('admin.virtual-lab-tasks.edit', compact('virtualLabTask', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VirtualLabTask $virtualLabTask)
    {
        $validated = $request->validate([
            'material_id'           => 'required|exists:materials,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'required|string',
            'template_code'         => 'required|string',
            'expected_output'       => 'nullable|string',
            'expected_result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deadline_minutes'      => 'nullable|integer|min:1|max:480',
            'difficulty'            => 'required|in:beginner,intermediate,advanced',
            'test_cases'            => 'nullable|string',
        ]);

        if ($request->title !== $virtualLabTask->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        if (!empty($validated['test_cases'])) {
            $validated['test_cases'] = json_decode($validated['test_cases'], true);
        }

        // Handle expected result image upload
        if ($request->hasFile('expected_result_image')) {
            // Delete old image if exists
            if ($virtualLabTask->expected_result_image) {
                Storage::disk('public')->delete($virtualLabTask->expected_result_image);
            }
            $validated['expected_result_image'] = $request->file('expected_result_image')
                ->store('expected-results', 'public');
        }

        // Handle image removal
        if ($request->has('remove_expected_image') && $request->remove_expected_image == '1') {
            if ($virtualLabTask->expected_result_image) {
                Storage::disk('public')->delete($virtualLabTask->expected_result_image);
            }
            $validated['expected_result_image'] = null;
        }

        $virtualLabTask->update($validated);

        return redirect()->route('admin.virtual-lab-tasks.index')
            ->with('success', 'Tugas Virtual Lab berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VirtualLabTask $virtualLabTask)
    {
        // Delete image file if exists
        if ($virtualLabTask->expected_result_image) {
            Storage::disk('public')->delete($virtualLabTask->expected_result_image);
        }

        $virtualLabTask->delete();
        return redirect()->route('admin.virtual-lab-tasks.index')
            ->with('success', 'Tugas Virtual Lab berhasil dihapus.');
    }
}
