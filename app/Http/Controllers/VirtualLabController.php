<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TbutSession;

class VirtualLabController extends Controller
{
    /**
     * Display the virtual lab page
     */
    public function index(Request $request)
    {
        // 1. Fetch data needed for Task List (and Sidebar)
        $materials = \App\Models\Material::with(['virtualLabTasks' => function($query) {
            $query->orderBy('title');
        }])->get();

        // 2. Determine Mode: Sandbox vs Task vs List
        $showEditor = false;
        $activeTask = null;
        
        if ($request->has('task') && $request->task != '') {
            $activeTask = \App\Models\VirtualLabTask::find($request->task);
            if ($activeTask) {
                $showEditor = true;
            }
        } elseif ($request->input('mode') === 'sandbox') {
            $showEditor = true;
        }

        // 3. TBUT: Start/Resume session when student opens a task
        $tbutSession = null;
        if ($showEditor && $activeTask && auth()->check() && auth()->user()->role_id == 3) {
            $tbutSession = TbutSession::firstOrCreate(
                ['user_id' => auth()->id(), 'task_id' => $activeTask->id],
                ['started_at' => now(), 'run_count' => 0, 'is_completed' => false]
            );
            // NOTE: if already completed, we still show the view but in read-only mode
            // (handled in the Blade view by checking $tbutSession->is_completed)
        }

        // 4. Render View based on logic
        // If User is Admin/Dosen (Role 1 & 2)
        if (auth()->check() && auth()->user()->role_id <= 2) {
            if ($showEditor) {
                $filesData = [['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"]];
                if ($activeTask) {
                    $filesData = [['filename' => 'Main.java', 'content' => $activeTask->template_code]];
                }
                return view('virtual-lab.index', [
                    'materials' => $materials,
                    'activeTask' => $activeTask,
                    'files' => $filesData
                ]);
            } else {
                return view('virtual-lab.admin-task-list', ['materials' => $materials]);
            }
        }

        // If User is Mahasiswa (Role 3)
        if ($showEditor) {
            $filesData = [['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"]];

            // If Task is active, load saved code or template
            if ($activeTask) {
                $savedCode = $tbutSession?->final_code ?? $activeTask->template_code;
                $filesData = [['filename' => 'Main.java', 'content' => $savedCode]];
            }

            return view('virtual-lab.mahasiswa', [
                'materials'    => $materials,
                'activeTask'   => $activeTask,
                'files'        => $filesData,
                'tbutSession'  => $tbutSession,
            ]);
        } else {
            // Pass set of completed task IDs so task-list can render 'Review' button
            $completedTaskIds = [];
            if (auth()->check() && auth()->user()->role_id == 3) {
                $completedTaskIds = TbutSession::where('user_id', auth()->id())
                    ->where('is_completed', true)
                    ->pluck('task_id')
                    ->toArray();
            }
            return view('virtual-lab.task-list', [
                'materials'        => $materials,
                'completedTaskIds' => $completedTaskIds,
            ]);
        }
    }

    /**
     * Save code (AJAX, without execution) — TBUT: records intermediate code.
     */
    public function saveCode(Request $request)
    {
        $request->validate([
            'task_id'    => 'required|integer|exists:virtual_lab_tasks,id',
            'code'       => 'required|string',
            'elapsed'    => 'nullable|integer', // seconds elapsed from JS timer
        ]);

        if (!auth()->check() || auth()->user()->role_id != 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $session = TbutSession::where('user_id', auth()->id())
            ->where('task_id', $request->task_id)
            ->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 404);
        }

        $session->update([
            'final_code'       => $request->code,
            'duration_seconds' => $request->elapsed ?? $session->duration_seconds,
        ]);

        return response()->json(['success' => true, 'message' => 'Kode disimpan!']);
    }

    /**
     * Submit task — TBUT: marks session as completed with final metrics.
     */
    public function submitTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer|exists:virtual_lab_tasks,id',
            'code'    => 'required|string',
            'elapsed' => 'nullable|integer',
        ]);

        if (!auth()->check() || auth()->user()->role_id != 3) {
            return redirect()->route('virtual-lab.index')->with('error', 'Unauthorized');
        }

        $session = TbutSession::where('user_id', auth()->id())
            ->where('task_id', $request->task_id)
            ->first();

        if ($session) {
            $session->update([
                'final_code'       => $request->code,
                'duration_seconds' => $request->elapsed ?? $session->duration_seconds,
                'submitted_at'     => now(),
                'is_completed'     => true,
            ]);
        }

        return redirect()->route('virtual-lab.index')
            ->with('success', '✅ Tugas berhasil diselesaikan! Kode Anda telah disimpan.');
    }

    /**
     * Execute Java code using Piston API
     */
    public function execute(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|max:5',
            'files.*.filename' => 'required|string',
            'files.*.content'  => 'required|string',
            'action'  => 'nullable|string',
        ]);

        $filesData = array_values($request->input('files'));
        $action    = $request->action;

        // Fetch Active Task if ID is present
        $activeTask = null;
        if ($request->has('task_id')) {
            $activeTask = \App\Models\VirtualLabTask::find($request->input('task_id'));
        }

        // Fetch Materials for sidebar
        $materials = \App\Models\Material::with(['virtualLabTasks' => function($query) {
            $query->orderBy('title');
        }])->get();

        // TBUT: Increment run_count for mahasiswa
        $tbutSession = null;
        if ($activeTask && auth()->check() && auth()->user()->role_id == 3) {
            $tbutSession = TbutSession::where('user_id', auth()->id())
                ->where('task_id', $activeTask->id)
                ->first();

            if ($tbutSession) {
                $tbutSession->increment('run_count');
                // Also save a snapshot of current code
                $mainCode = $filesData[0]['content'] ?? null;
                if ($mainCode) {
                    $tbutSession->update(['final_code' => $mainCode]);
                }
                $tbutSession->refresh();
            }
        }

        // Handle run action
        try {
            // Prepare files for Piston API
            $apiFiles = [];
            $mainFileIndex = 0;

            foreach ($filesData as $index => $fileData) {
                $content  = $fileData['content'];
                $baseName = preg_replace('/(\\.java)+$/i', '', trim($fileData['filename']));
                $finalFilename = $baseName . '.java';

                // Auto-detect class name
                if (preg_match('/(?:public\\s+)?class\\s+([a-zA-Z0-9_]+)/', $content, $matches)) {
                    $finalFilename = $matches[1] . '.java';
                }

                $apiFiles[] = ['name' => $finalFilename, 'content' => $content];

                if (preg_match('/public\\s+static\\s+void\\s+main\\s*\\(/i', $content)) {
                    $mainFileIndex = $index;
                }
            }

            if ($mainFileIndex > 0) {
                $mainFile = $apiFiles[$mainFileIndex];
                unset($apiFiles[$mainFileIndex]);
                array_unshift($apiFiles, $mainFile);
            }

            $response = Http::timeout(30)->post('https://emkc.org/api/v2/piston/execute', [
                'language' => 'java',
                'version'  => '15.0.2',
                'files'    => $apiFiles
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $output = $result['run']['output'] ?? '';
                $stderr = $result['run']['stderr'] ?? '';
                $error  = false;

                if ($stderr) {
                    if ($result['run']['code'] !== 0) {
                        $output = "Error:\n" . $stderr;
                        $error  = true;
                    } else {
                        $output .= "\n" . $stderr;
                    }
                } elseif (empty($output)) {
                    $output = 'Program executed successfully (no output)';
                }
            } else {
                $output = 'Failed to execute code. API Status: ' . $response->status();
                $error  = true;
            }
        } catch (\Exception $e) {
            $output = 'Error: ' . $e->getMessage();
            $error  = true;
        }

        // Determine View based on Role
        $viewName = (auth()->check() && auth()->user()->role_id == 3)
            ? 'virtual-lab.mahasiswa'
            : 'virtual-lab.index';

        return view($viewName, [
            'materials'   => $materials,
            'activeTask'  => $activeTask,
            'files'       => $filesData,
            'output'      => $output,
            'error'       => $error,
            'tbutSession' => $tbutSession,
        ]);
    }

    /**
     * Mark virtual lab tour as complete for the authenticated user
     */
    public function completeTour()
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['has_seen_virtual_lab_tour' => true]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    }
}
