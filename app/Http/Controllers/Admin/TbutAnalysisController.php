<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbutSession;
use App\Models\VirtualLabTask;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TbutAnalysisController extends Controller
{
    /**
     * Dashboard TBUT: Daftar semua task dengan rata-rata metrik.
     */
    public function index(Request $request)
    {
        $materialId = $request->get('material_id');

        $tasksQuery = VirtualLabTask::with('material')
            ->withCount('tbutSessions as total_attempts')
            ->withAvg('tbutSessions as avg_duration', 'duration_seconds')
            ->withAvg('tbutSessions as avg_run_count', 'run_count');

        if ($materialId) {
            $tasksQuery->where('material_id', $materialId);
        }

        $tasks = $tasksQuery->orderBy('material_id')->get();

        // Calculate completion rate per task
        foreach ($tasks as $task) {
            $completedCount = TbutSession::where('task_id', $task->id)
                ->where('is_completed', true)->count();
            $successCount = TbutSession::where('task_id', $task->id)
                ->where('is_success', true)->count();
                
            $task->completion_rate = $task->total_attempts > 0
                ? round(($completedCount / $task->total_attempts) * 100, 1)
                : 0;
            $task->success_rate = $completedCount > 0
                ? round(($successCount / $completedCount) * 100, 1)
                : 0;
            $task->completed_count = $completedCount;
            $task->success_count = $successCount;
        }

        $materials = Material::orderBy('title')->get();

        return view('admin.tbut.index', compact('tasks', 'materials', 'materialId'));
    }

    /**
     * Detail sesi TBUT per task — daftar mahasiswa dan rekaman mereka.
     */
    public function show(int $taskId)
    {
        $task = VirtualLabTask::with('material')->findOrFail($taskId);

        $sessions = TbutSession::with('user')
            ->where('task_id', $taskId)
            ->orderByDesc('started_at')
            ->get();

        // Summary stats
        $stats = [
            'total'           => $sessions->count(),
            'completed'       => $sessions->where('is_completed', true)->count(),
            'success'         => $sessions->where('is_success', true)->count(),
            'avg_duration'    => $sessions->avg('duration_seconds'),
            'avg_run_count'   => $sessions->avg('run_count'),
            'min_duration'    => $sessions->min('duration_seconds'),
            'max_duration'    => $sessions->max('duration_seconds'),
        ];

        $stats['completion_rate'] = $stats['total'] > 0
            ? round(($stats['completed'] / $stats['total']) * 100, 1)
            : 0;
            
        $stats['success_rate'] = $stats['completed'] > 0
            ? round(($stats['success'] / $stats['completed']) * 100, 1)
            : 0;

        return view('admin.tbut.show', compact('task', 'sessions', 'stats'));
    }
}
