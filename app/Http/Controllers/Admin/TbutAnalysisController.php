<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbutSession;
use App\Models\VirtualLabTask;
use App\Models\Material;
use Illuminate\Http\Request;

class TbutAnalysisController extends Controller
{
    /**
     * Classify Difficulty Score (D) based on thresholds from TBUT framework.
     */
    private function classifyD(float $d): array
    {
        if ($d < 1.5)  return ['label' => 'Mudah',       'color' => '#16a34a', 'bg' => '#dcfce7', 'border' => '#86efac'];
        if ($d < 2.5)  return ['label' => 'Sedang',      'color' => '#b45309', 'bg' => '#fef9c3', 'border' => '#fde047'];
        if ($d <= 4.0) return ['label' => 'Sulit',       'color' => '#dc2626', 'bg' => '#fee2e2', 'border' => '#fca5a5'];
        return            ['label' => 'Sangat Sulit', 'color' => '#7c3aed', 'bg' => '#ede9fe', 'border' => '#c4b5fd'];
    }

    /**
     * Classify combined D + Success Rate (ISO 9241-11 framework).
     */
    private function classifyCombined(float $d, float $sr): array
    {
        // Prioritise the worst signal
        if ($d < 1.5 && $sr >= 80)  return ['label' => 'Mudah',       'color' => '#16a34a', 'bg' => '#dcfce7', 'interpretation' => 'Materi dipahami dengan baik, perlu pengayaan'];
        if ($d < 2.5 && $sr >= 60)  return ['label' => 'Sedang',      'color' => '#b45309', 'bg' => '#fef9c3', 'interpretation' => 'Materi cukup menantang, perlu latihan tambahan'];
        if ($d <= 4.0 && $sr >= 40) return ['label' => 'Sulit',       'color' => '#dc2626', 'bg' => '#fee2e2', 'interpretation' => 'Materi perlu penjelasan ulang / scaffolding'];
        return                        ['label' => 'Sangat Sulit', 'color' => '#7c3aed', 'bg' => '#ede9fe', 'interpretation' => 'Materi terlalu kompleks, perlu redesign konten'];
    }

    /**
     * Compute TBUT Difficulty Score for a single task.
     *
     * Formula (from Saputra 2025 / ISO 9241-11):
     *   T_norm  = t_actual / t_ideal
     *   R_norm  = r_actual / r_ideal
     *   E       = (w1 × T_norm) + (w2 × R_norm)   // per student
     *   D       = mean(E) over all completed sessions
     *
     * Defaults: w1 = w2 = 0.5
     * t_ideal  = median duration of fastest 20% sessions (floor 60s)
     * r_ideal  = median run_count of fastest 20% sessions (floor 1)
     */
    private function computeDifficultyScore($sessions, float $w1 = 0.5, float $w2 = 0.5): array
    {
        $completed = $sessions->where('is_completed', true);

        if ($completed->isEmpty()) {
            return ['D' => null, 't_ideal' => null, 'r_ideal' => null, 'scores' => []];
        }

        // Sort by duration to find fastest 20%
        $sorted    = $completed->sortBy('duration_seconds')->values();
        $top20     = max(1, ceil($sorted->count() * 0.2));
        $fastest   = $sorted->take($top20);

        $t_ideal = max(60,  $fastest->median('duration_seconds') ?? 60);
        $r_ideal = max(1,   $fastest->median('run_count')        ?? 1);

        $scores = [];
        foreach ($completed as $s) {
            $t_norm = $s->duration_seconds / $t_ideal;
            $r_norm = $s->run_count        / $r_ideal;
            $E      = ($w1 * $t_norm) + ($w2 * $r_norm);
            $scores[] = $E;
        }

        $D = count($scores) > 0 ? array_sum($scores) / count($scores) : null;

        return [
            'D'       => $D !== null ? round($D, 2) : null,
            't_ideal' => round($t_ideal / 60, 1),   // convert to minutes
            'r_ideal' => (int) $r_ideal,
            'scores'  => $scores,
        ];
    }

    /**
     * Dashboard TBUT: list all tasks with full metrics + Difficulty Score.
     */
    public function index(Request $request)
    {
        $materialId = $request->get('material_id');

        $tasksQuery = VirtualLabTask::with(['material', 'tbutSessions.user'])
            ->withCount('tbutSessions as total_attempts')
            ->withAvg('tbutSessions as avg_duration', 'duration_seconds')
            ->withAvg('tbutSessions as avg_run_count', 'run_count');

        if ($materialId) {
            $tasksQuery->where('material_id', $materialId);
        }

        $tasks = $tasksQuery->orderBy('material_id')->get();

        // Enrich each task with computed metrics
        foreach ($tasks as $task) {
            $sessions = $task->tbutSessions;

            $completedCount = $sessions->where('is_completed', true)->count();
            $successCount   = $sessions->where('is_success', true)->count();

            $task->completed_count  = $completedCount;
            $task->success_count    = $successCount;
            $task->completion_rate  = $task->total_attempts > 0
                ? round(($completedCount / $task->total_attempts) * 100, 1) : 0;
            $task->success_rate     = $completedCount > 0
                ? round(($successCount / $completedCount) * 100, 1) : 0;

            // Difficulty Score (D)
            $dResult               = $this->computeDifficultyScore($sessions);
            $task->difficulty_score = $dResult['D'];
            $task->t_ideal          = $dResult['t_ideal'];
            $task->r_ideal          = $dResult['r_ideal'];
            $task->d_class          = $task->difficulty_score !== null
                ? $this->classifyD($task->difficulty_score)
                : null;
            $task->combined_class   = ($task->difficulty_score !== null)
                ? $this->classifyCombined($task->difficulty_score, $task->success_rate)
                : null;
        }

        // Global stats
        $allSessions    = TbutSession::when($materialId, function ($q) use ($materialId) {
            $taskIds = VirtualLabTask::where('material_id', $materialId)->pluck('id');
            return $q->whereIn('task_id', $taskIds);
        })->get();

        $totalSessions  = $allSessions->count();
        $completedSess  = $allSessions->where('is_completed', true)->count();
        $successSess    = $allSessions->where('is_success', true)->count();
        $avgDuration    = $allSessions->avg('duration_seconds');
        $avgRunCount    = $allSessions->avg('run_count');
        $completionRate = $totalSessions > 0 ? round(($completedSess / $totalSessions) * 100, 1) : 0;
        $successRate    = $totalSessions > 0 ? round(($successSess  / $totalSessions) * 100, 1) : 0;

        // Global avg Difficulty Score
        $dScores       = $tasks->whereNotNull('difficulty_score')->pluck('difficulty_score');
        $avgDScore     = $dScores->isNotEmpty() ? round($dScores->avg(), 2) : null;

        $materials = Material::orderBy('title')->get();

        return view('admin.tbut.index', compact(
            'tasks', 'materials', 'materialId',
            'totalSessions', 'completedSess', 'successSess',
            'avgDuration', 'avgRunCount', 'completionRate', 'successRate',
            'avgDScore'
        ));
    }

    /**
     * Detail sesi TBUT per task.
     */
    public function show(int $taskId)
    {
        $task     = VirtualLabTask::with('material')->findOrFail($taskId);
        $sessions = TbutSession::with('user')
            ->where('task_id', $taskId)
            ->orderByDesc('started_at')
            ->get();

        // Basic stats
        $stats = [
            'total'        => $sessions->count(),
            'completed'    => $sessions->where('is_completed', true)->count(),
            'success'      => $sessions->where('is_success', true)->count(),
            'avg_duration' => $sessions->avg('duration_seconds'),
            'avg_run_count'=> $sessions->avg('run_count'),
            'min_duration' => $sessions->min('duration_seconds'),
            'max_duration' => $sessions->max('duration_seconds'),
        ];
        $stats['completion_rate'] = $stats['total'] > 0
            ? round(($stats['completed'] / $stats['total']) * 100, 1) : 0;
        $stats['success_rate'] = $stats['completed'] > 0
            ? round(($stats['success'] / $stats['completed']) * 100, 1) : 0;

        // Difficulty Score for this task
        $dResult             = $this->computeDifficultyScore($sessions);
        $stats['D']          = $dResult['D'];
        $stats['t_ideal']    = $dResult['t_ideal'];
        $stats['r_ideal']    = $dResult['r_ideal'];
        $stats['d_class']    = $stats['D'] !== null ? $this->classifyD($stats['D']) : null;
        $stats['combined']   = ($stats['D'] !== null)
            ? $this->classifyCombined($stats['D'], $stats['success_rate'])
            : null;

        return view('admin.tbut.show', compact('task', 'sessions', 'stats'));
    }
}
