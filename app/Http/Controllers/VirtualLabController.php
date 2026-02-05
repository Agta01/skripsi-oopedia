<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VirtualLabController extends Controller
{
    /**
     * Display the virtual lab page
     */
    /**
     * Display the virtual lab page
     */
    public function index(Request $request)
    {
        // 1. Fetch data needed for Task List (and Sidebar)
        // Order tasks by title for consistent display
        $materials = \App\Models\Material::with(['virtualLabTasks' => function($query) {
            $query->orderBy('title');
        }])->get();

        // 2. Determine Mode: Sandbox vs Task vs List
        // If 'task' param exists -> Show specific task in Editor
        // If 'mode' param is 'sandbox' -> Show Sandbox in Editor
        // Else -> Show Task List View
        
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

        // 3. Render View based on logic
        
        // 3. Render View based on logic
        
        // If User is Admin/Dosen (Role 1 & 2)
        if (auth()->check() && auth()->user()->role_id <= 2) {
            if ($showEditor) {
                // Return Admin Editor View
                // Prepared default files for Sandbox
                $filesData = [
                    [
                        'filename' => 'Main.java',
                        'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"
                    ]
                ];

                if ($activeTask) {
                    $filesData = [
                        [
                            'filename' => 'Main.java',
                            'content' => $activeTask->template_code
                        ]
                    ];
                }

                return view('virtual-lab.index', [
                    'materials' => $materials,
                    'activeTask' => $activeTask,
                    'files' => $filesData
                ]);
            } else {
                // Return Admin Task List View
                return view('virtual-lab.admin-task-list', [
                    'materials' => $materials
                ]);
            }
        }

        // If User is Mahasiswa (Role 3), switch between List and Editor
        if ($showEditor) {
            // Prepared default files for Sandbox
            $filesData = [
                [
                    'filename' => 'Main.java',
                    'content' => "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello World!\");\n    }\n}"
                ]
            ];

            // If Task is active, override with template code
            if ($activeTask) {
                $filesData = [
                    [
                        'filename' => 'Main.java',
                        'content' => $activeTask->template_code
                    ]
                ];
            }

            return view('virtual-lab.mahasiswa', [
                'materials' => $materials,
                'activeTask' => $activeTask,
                'files' => $filesData
            ]);
        } else {
            // Show the Task Selection List
            return view('virtual-lab.task-list', [
                'materials' => $materials
            ]);
        }
    }

    /**
     * Execute Java code using Piston API
     */
    public function execute(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:5',
            'files.*.filename' => 'required|string',
            'files.*.content' => 'required|string',
            'action' => 'nullable|string'
        ]);

        // Normalize files array to ensure 0-indexed list for the View
        $filesData = array_values($request->input('files'));
        $action = $request->action;

        // Handle submit action
        if ($action === 'submit') {
            return redirect()->route('virtual-lab.index')
                ->with('success', 'Kode Anda telah disubmit untuk dinilai!');
        }

        // Handle run action
        try {
            // Prepare files for Piston API
            $apiFiles = [];
            $mainFileIndex = 0;

            foreach ($filesData as $index => $fileData) {
                $content = $fileData['content'];
                $apiFiles[] = [
                    'name' => $fileData['filename'],
                    'content' => $content
                ];

                // Detect Main class (public static void main)
                if (preg_match('/public\s+static\s+void\s+main/i', $content)) {
                    $mainFileIndex = $index;
                }
            }

            // Move Main file to the top (Index 0) for Piston execution
            if ($mainFileIndex > 0) {
                $mainFile = $apiFiles[$mainFileIndex];
                unset($apiFiles[$mainFileIndex]);
                array_unshift($apiFiles, $mainFile);
            }

            // Using Piston API to execute Java code
            $response = Http::timeout(30)->post('https://emkc.org/api/v2/piston/execute', [
                'language' => 'java',
                'version' => '15.0.2',
                'files' => $apiFiles
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                $output = $result['run']['output'] ?? '';
                $stderr = $result['run']['stderr'] ?? '';
                $error = false;

                if ($stderr) {
                    // Check if success but with stderr (warnings) or actual error
                    if ($result['run']['code'] !== 0) {
                         $output = "Error:\n" . $stderr;
                         $error = true;
                    } else {
                         // Warnings or mixed output
                         $output .= "\n" . $stderr;
                    }
                } elseif (empty($output)) {
                    $output = 'Program executed successfully (no output)';
                }

                return view('virtual-lab.index', [
                    'files' => $filesData, // Return original order to UI
                    'output' => $output,
                    'error' => $error
                ]);
            } else {
                return view('virtual-lab.index', [
                    'files' => $filesData,
                    'output' => 'Failed to execute code. API Status: ' . $response->status(),
                    'error' => true
                ]);
            }
        } catch (\Exception $e) {
            // Check role for error view
            $viewName = (auth()->check() && auth()->user()->role_id >= 3) ? 'virtual-lab.mahasiswa' : 'virtual-lab.index';

            return view($viewName, [
                'files' => $filesData,
                'output' => 'Error: ' . $e->getMessage(),
                'error' => true
            ]);
        }
    }
}
