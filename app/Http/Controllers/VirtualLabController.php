<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VirtualLabController extends Controller
{
    /**
     * Display the virtual lab page
     */
    public function index()
    {
        // Jika user adalah Mahasiswa (3) atau Guest (4), gunakan layout mahasiswa
        if (auth()->check() && auth()->user()->role_id >= 3) {
            return view('virtual-lab.mahasiswa');
        }

        // Default untuk Admin/Dosen
        return view('virtual-lab.index');
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
