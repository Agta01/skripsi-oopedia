<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TbutSession;

class VirtualLabController extends Controller
{
    public function index(Request $request)
    {
        $materials = \App\Models\Material::with([
            'virtualLabTasks' => function ($query) {
                $query->orderBy('title');
            }
        ])->get();

        $showEditor = false;
        $activeTask = null;

        if ($request->has('task') && $request->task != '') {
            $activeTask = \App\Models\VirtualLabTask::find($request->task);
            if ($activeTask)
                $showEditor = true;
        } elseif ($request->input('mode') === 'sandbox') {
            $showEditor = true;
        }

        $tbutSession = null;
        if ($showEditor && $activeTask && auth()->check() && auth()->user()->role_id == 3) {
            $tbutSession = TbutSession::firstOrCreate(
                ['user_id' => auth()->id(), 'task_id' => $activeTask->id],
                ['started_at' => now(), 'run_count' => 0, 'is_completed' => false]
            );
        }

        if (auth()->check() && auth()->user()->role_id <= 2) {
            if ($showEditor) {
                $filesData = [['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"]];
                if ($activeTask) {
                    $filesData = [['filename' => 'Main.java', 'content' => $activeTask->template_code]];
                }
                return view('virtual-lab.index', ['materials' => $materials, 'activeTask' => $activeTask, 'files' => $filesData]);
            }
            return view('virtual-lab.admin-task-list', ['materials' => $materials]);
        }

        if ($showEditor) {
            $filesData = [['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"]];
            if ($activeTask) {
                $savedCode = $tbutSession?->final_code ?? $activeTask->template_code;
                $filesData = [['filename' => 'Main.java', 'content' => $savedCode]];
            }
            return view('virtual-lab.mahasiswa', [
                'materials' => $materials,
                'activeTask' => $activeTask,
                'files' => $filesData,
                'tbutSession' => $tbutSession,
            ]);
        }

        $completedTaskIds = [];
        if (auth()->check() && auth()->user()->role_id == 3) {
            $completedTaskIds = TbutSession::where('user_id', auth()->id())
                ->where('is_completed', true)->pluck('task_id')->toArray();
        }
        return view('virtual-lab.task-list', ['materials' => $materials, 'completedTaskIds' => $completedTaskIds]);
    }

    public function saveCode(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer|exists:virtual_lab_tasks,id',
            'code' => 'required|string',
            'elapsed' => 'nullable|integer',
        ]);

        if (!auth()->check() || auth()->user()->role_id != 3) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $session = TbutSession::where('user_id', auth()->id())->where('task_id', $request->task_id)->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 404);
        }

        $session->update([
            'final_code' => $request->code,
            'duration_seconds' => $request->elapsed ?? $session->duration_seconds,
        ]);

        return response()->json(['success' => true, 'message' => 'Kode disimpan!']);
    }

    public function submitTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer|exists:virtual_lab_tasks,id',
            'code' => 'required|string',
            'elapsed' => 'nullable|integer',
        ]);

        if (!auth()->check() || auth()->user()->role_id != 3) {
            return redirect()->route('virtual-lab.index')->with('error', 'Unauthorized');
        }

        $session = TbutSession::where('user_id', auth()->id())->where('task_id', $request->task_id)->first();
        if ($session) {
            $session->update([
                'final_code' => $request->code,
                'duration_seconds' => $request->elapsed ?? $session->duration_seconds,
                'submitted_at' => now(),
                'is_completed' => true,
            ]);
        }

        return redirect()->route('virtual-lab.index')
            ->with('success', '✅ Tugas berhasil diselesaikan! Kode Anda telah disimpan.');
    }

    /**
     * Execute Java code via Wandbox API using native PHP streams.
     *
     * IMPORTANT: Piston/emkc.org has been whitelist-only since Feb 2026 (returns 401).
     * Laravel Http facade was sending wrong Content-Type (form-encoded) causing Wandbox 422.
     * Solution: use file_get_contents + stream_context which sends proper application/json.
     *
     * Wandbox stores the primary 'code' field as prog.java, so 'public' must be stripped
     * from class declarations to avoid the "should be declared in file named X.java" error.
     */
    public function execute(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:5',
            'files.*.filename' => 'required|string',
            'files.*.content' => 'nullable|string',
            'action' => 'nullable|string',
            'elapsed' => 'nullable|integer',
            'stdin' => 'nullable|string',
        ]);

        $filesData = array_values($request->input('files'));

        $activeTask = null;
        if ($request->has('task_id')) {
            $activeTask = \App\Models\VirtualLabTask::find($request->input('task_id'));
        }

        $materials = \App\Models\Material::with([
            'virtualLabTasks' => function ($query) {
                $query->orderBy('title');
            }
        ])->get();

        // TBUT: Increment run_count (only if session is NOT yet completed)
        $tbutSession = null;
        if ($activeTask && auth()->check() && auth()->user()->role_id == 3) {
            $tbutSession = TbutSession::where('user_id', auth()->id())
                ->where('task_id', $activeTask->id)->first();

            if ($tbutSession && !$tbutSession->is_completed) {
                // Only update metrics when task is still active (not in review mode)
                $tbutSession->increment('run_count');
                $elapsed = $request->input('elapsed');
                $mainCode = $filesData[0]['content'] ?? null;
                $upd = [];
                if ($mainCode)
                    $upd['final_code'] = $mainCode;
                if ($elapsed !== null)
                    $upd['duration_seconds'] = max($elapsed, $tbutSession->duration_seconds);
                if (!empty($upd))
                    $tbutSession->update($upd);
                $tbutSession->refresh();
            }
        }        // Cast stdin to string
        $stdin = (string) $request->input('stdin', '');
        $output = '';
        $error = false;

        try {
            // TIO allows a single file execution for java-openjdk.
            // We combine all classes into one.
            $mainCode = trim($filesData[0]['content'] ?? '');

            if (empty($mainCode)) {
                throw new \Exception("Kode kosong! Silakan tulis kode Java terlebih dahulu.");
            }

            $combinedCode = $mainCode;
            foreach ($filesData as $i => $fd) {
                if ($i === 0) continue;
                $combinedCode .= "\n\n" . trim($fd['content'] ?? '');
            }

            // Membangun payload TIO API
            $lang = "java-openjdk";
            $payloadStr = "Vlang\0" . "1\0" . "$lang\0";
            $payloadStr .= "F.code.tio\0" . strlen($combinedCode) . "\0" . $combinedCode;
            $payloadStr .= "F.input.tio\0" . strlen($stdin) . "\0" . $stdin;
            $payloadStr .= "Vargs\0" . "0\0";
            $payloadStr .= "R";
            $gzPayload = gzdeflate($payloadStr, 9);

            // ── Kirim ke TIO (timeout 30s) ──
            $response = \Illuminate\Support\Facades\Http::timeout(30)
                ->withBody($gzPayload, 'application/octet-stream')
                ->post('https://tio.run/cgi-bin/run/api/');

            $httpCode = $response->status();
            \Illuminate\Support\Facades\Log::info('TIO Response', ['status' => $httpCode]);

            if ($response->successful()) {
                $rawOutput = $response->body();
                
                // Parse TIO Response format (token separated)
                $token = substr($rawOutput, 0, 16);
                $parts = explode($token, $rawOutput);
                
                $programOut = trim($parts[1] ?? '');
                $debugOut   = trim($parts[2] ?? '');

                // Deteksi error jika debug output mengandung error message
                if (preg_match('/error:|Exception in thread|Could not find or load/i', $debugOut)) {
                    // Ekstrak pesan error sebelum bagian statistik TIO (Real time: ...)
                    $errParts = explode('Real time:', $debugOut);
                    $errMsg = trim($errParts[0]);
                    
                    $output = $errMsg;
                    if (!empty($programOut)) {
                        $output = $programOut . "\n\n[Compile/Runtime Error]\n" . $errMsg;
                    }
                    $error = true;
                } else {
                    $output = $programOut;
                    if (empty($output)) {
                        $output = 'Program executed successfully (no output)';
                    }
                }
            } else {
                $output = "Gagal menjalankan kode. Server TIO error (HTTP $httpCode). Coba beberapa saat lagi.";
                $error = true;
            }
        } catch (\Exception $e) {
            $output = 'Error: ' . $e->getMessage();
            $error = true;
        }

        // TBUT: Compare output with expected_output
        if (!$error && $activeTask && $tbutSession && !empty($activeTask->expected_output)) {
            $norm = fn($s) => preg_replace('/\s+/', ' ', trim(strtolower($s)));
            if ($norm($output) === $norm($activeTask->expected_output)) {
                $tbutSession->update(['is_success' => true]);
                $tbutSession->refresh();
            }
        }

        $viewName = (auth()->check() && auth()->user()->role_id == 3)
            ? 'virtual-lab.mahasiswa'
            : 'virtual-lab.index';

        return view($viewName, [
            'materials' => $materials,
            'activeTask' => $activeTask,
            'files' => $filesData,
            'output' => $output,
            'error' => $error,
            'tbutSession' => $tbutSession,
            'stdin' => $stdin,
        ]);
    }

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
