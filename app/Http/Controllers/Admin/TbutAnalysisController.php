<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbutSession;
use App\Models\VirtualLabTask;
use App\Models\Material;
use Illuminate\Http\Request;

/**
 * TBUT Analysis Controller
 *
 * Implementasi sesuai metode Task-Based Usability Testing (TBUT) dari:
 * Saputra, A. D. (2025). "Usability Evaluation of the Alodokter Application
 * Using the Task-Based Usability Testing Method."
 * Jurnal Teknik Industri, Vol. 11, No. 2, pp. 294-302.
 *
 * Tiga dimensi ISO 9241-11 yang diukur:
 *   1. Effectiveness → Task Success Score (skala 0-2)
 *      - 0 = Gagal (tidak menyelesaikan tugas)
 *      - 1 = Berhasil dengan kesulitan (selesai tapi output belum tepat)
 *      - 2 = Berhasil tanpa kesulitan (selesai dengan output tepat)
 *
 *   2. Efficiency → Time-on-Task (durasi penyelesaian dalam detik)
 *      - Rata-rata waktu penyelesaian per tugas
 *      - Perbandingan antar tugas (tugas mana yang paling lama)
 *
 *   3. Satisfaction → Belum tersedia (memerlukan kuesioner post-task)
 *      - Catatan: Saputra menggunakan skala Likert 1-4 dari user + observer
 *      - Pada sistem ini, satisfaction belum diukur secara langsung
 *
 * Metrik tambahan (bukan dari Saputra, tapi relevan untuk virtual coding lab):
 *   - Run Count: jumlah eksekusi kode per sesi (indikator iterasi debugging)
 */
class TbutAnalysisController extends Controller
{
    /**
     * Hitung Task Success Score per sesi berdasarkan skala 3 poin (Saputra, 2025).
     *
     * Skala:
     *   0 = Gagal (is_completed = false)
     *   1 = Berhasil dengan kesulitan (is_completed = true, is_success = false)
     *   2 = Berhasil tanpa kesulitan (is_completed = true, is_success = true)
     */
    private function getSuccessScore(TbutSession $session): int
    {
        if (!$session->is_completed) {
            return 0; // Gagal
        }
        if ($session->is_success) {
            return 2; // Berhasil tanpa kesulitan (output tepat)
        }
        return 1; // Berhasil dengan kesulitan (selesai tapi output belum tepat)
    }

    /**
     * Klasifikasi rata-rata Success Score berdasarkan interpretasi Saputra.
     *
     * Interpretasi:
     *   1.80 - 2.00 = Sangat Baik (hampir semua berhasil tanpa kesulitan)
     *   1.50 - 1.79 = Baik (mayoritas berhasil, sebagian dengan kesulitan)
     *   1.00 - 1.49 = Cukup (banyak yang mengalami kesulitan)
     *   0.50 - 0.99 = Kurang (banyak yang gagal atau kesulitan berat)
     *   0.00 - 0.49 = Sangat Kurang (mayoritas gagal)
     */
    private function classifySuccessScore(float $score): array
    {
        if ($score >= 1.80)
            return ['label' => 'Sangat Baik', 'color' => '#16a34a', 'bg' => '#dcfce7',
                    'interpretation' => 'Hampir semua mahasiswa berhasil menyelesaikan tugas tanpa kesulitan'];
        if ($score >= 1.50)
            return ['label' => 'Baik', 'color' => '#0ea5e9', 'bg' => '#e0f2fe',
                    'interpretation' => 'Mayoritas berhasil, sebagian mengalami kesulitan minor'];
        if ($score >= 1.00)
            return ['label' => 'Cukup', 'color' => '#b45309', 'bg' => '#fef9c3',
                    'interpretation' => 'Banyak mahasiswa mengalami kesulitan saat mengerjakan tugas'];
        if ($score >= 0.50)
            return ['label' => 'Kurang', 'color' => '#ea580c', 'bg' => '#ffedd5',
                    'interpretation' => 'Banyak mahasiswa gagal atau mengalami kesulitan berat'];
        return ['label' => 'Sangat Kurang', 'color' => '#dc2626', 'bg' => '#fee2e2',
                'interpretation' => 'Mayoritas mahasiswa gagal menyelesaikan tugas'];
    }

    /**
     * Klasifikasi efisiensi berdasarkan rata-rata waktu penyelesaian.
     * Threshold ditentukan berdasarkan konteks virtual coding lab.
     */
    private function classifyEfficiency(float $avgSeconds): array
    {
        $minutes = $avgSeconds / 60;

        if ($minutes <= 5)
            return ['label' => 'Sangat Efisien', 'color' => '#16a34a', 'bg' => '#dcfce7',
                    'interpretation' => 'Tugas diselesaikan dengan cepat (≤ 5 menit)'];
        if ($minutes <= 10)
            return ['label' => 'Efisien', 'color' => '#0ea5e9', 'bg' => '#e0f2fe',
                    'interpretation' => 'Waktu penyelesaian wajar (5-10 menit)'];
        if ($minutes <= 20)
            return ['label' => 'Cukup Efisien', 'color' => '#b45309', 'bg' => '#fef9c3',
                    'interpretation' => 'Waktu penyelesaian agak lama (10-20 menit)'];
        if ($minutes <= 30)
            return ['label' => 'Kurang Efisien', 'color' => '#ea580c', 'bg' => '#ffedd5',
                    'interpretation' => 'Waktu penyelesaian lama (20-30 menit)'];
        return ['label' => 'Tidak Efisien', 'color' => '#dc2626', 'bg' => '#fee2e2',
                'interpretation' => 'Waktu penyelesaian sangat lama (> 30 menit)'];
    }

    /**
     * Dashboard TBUT: Rekap semua tugas dengan metrik ISO 9241-11.
     */
    public function index(Request $request)
    {
        $materialId = $request->get('material_id');

        $tasksQuery = VirtualLabTask::with(['material', 'tbutSessions.user'])
            ->withCount('tbutSessions as total_attempts');

        if ($materialId) {
            $tasksQuery->where('material_id', $materialId);
        }

        $tasks = $tasksQuery->orderBy('material_id')->get();

        // Enrich each task with ISO 9241-11 metrics
        foreach ($tasks as $task) {
            $sessions = $task->tbutSessions;

            // ── Effectiveness: Task Success Score (skala 0-2) ──
            $successScores = $sessions->map(fn($s) => $this->getSuccessScore($s));
            $task->avg_success_score = $successScores->isNotEmpty()
                ? round($successScores->avg(), 2) : null;
            $task->success_class = $task->avg_success_score !== null
                ? $this->classifySuccessScore($task->avg_success_score) : null;

            // Detail breakdown
            $task->count_score_0 = $successScores->filter(fn($s) => $s === 0)->count(); // Gagal
            $task->count_score_1 = $successScores->filter(fn($s) => $s === 1)->count(); // Berhasil dgn kesulitan
            $task->count_score_2 = $successScores->filter(fn($s) => $s === 2)->count(); // Berhasil tanpa kesulitan

            // Task Success Rate (%) — persentase yang berhasil (skor 1 atau 2)
            $task->success_rate = $task->total_attempts > 0
                ? round((($task->count_score_1 + $task->count_score_2) / $task->total_attempts) * 100, 1)
                : 0;

            // ── Efficiency: Time-on-Task ──
            $completedSessions = $sessions->where('is_completed', true);
            $task->avg_duration = $completedSessions->isNotEmpty()
                ? round($completedSessions->avg('duration_seconds')) : null;
            $task->min_duration = $completedSessions->min('duration_seconds');
            $task->max_duration = $completedSessions->max('duration_seconds');
            $task->efficiency_class = $task->avg_duration !== null
                ? $this->classifyEfficiency($task->avg_duration) : null;

            // ── Metrik Tambahan: Run Count ──
            $task->avg_run_count = $sessions->isNotEmpty()
                ? round($sessions->avg('run_count'), 1) : null;
        }

        // ── Global Stats ──
        $allSessions = TbutSession::when($materialId, function ($q) use ($materialId) {
            $taskIds = VirtualLabTask::where('material_id', $materialId)->pluck('id');
            return $q->whereIn('task_id', $taskIds);
        })->get();

        $totalSessions   = $allSessions->count();
        $allScores       = $allSessions->map(fn($s) => $this->getSuccessScore($s));
        $avgSuccessScore = $allScores->isNotEmpty() ? round($allScores->avg(), 2) : null;

        $completedAll    = $allSessions->where('is_completed', true);
        $avgDuration     = $completedAll->isNotEmpty() ? round($completedAll->avg('duration_seconds')) : null;
        $avgRunCount     = $allSessions->isNotEmpty() ? round($allSessions->avg('run_count'), 1) : null;

        $countScore0 = $allScores->filter(fn($s) => $s === 0)->count();
        $countScore1 = $allScores->filter(fn($s) => $s === 1)->count();
        $countScore2 = $allScores->filter(fn($s) => $s === 2)->count();

        $successRate = $totalSessions > 0
            ? round((($countScore1 + $countScore2) / $totalSessions) * 100, 1) : 0;

        $materials = Material::orderBy('title')->get();

        return view('admin.tbut.index', compact(
            'tasks',
            'materials',
            'materialId',
            'totalSessions',
            'avgSuccessScore',
            'avgDuration',
            'avgRunCount',
            'successRate',
            'countScore0',
            'countScore1',
            'countScore2'
        ));
    }

    /**
     * Export semua data TBUT ke Excel (.xlsx).
     */
    public function export(Request $request)
    {
        $materialId = $request->get('material_id');

        $tasksQuery = VirtualLabTask::with(['material', 'tbutSessions.user']);
        if ($materialId) {
            $tasksQuery->where('material_id', $materialId);
        }
        $tasks = $tasksQuery->orderBy('material_id')->get();

        $allSessions = TbutSession::with(['user', 'task.material'])
            ->when($materialId, function ($q) use ($materialId) {
                $taskIds = VirtualLabTask::where('material_id', $materialId)->pluck('id');
                return $q->whereIn('task_id', $taskIds);
            })
            ->orderBy('task_id')
            ->orderBy('started_at')
            ->get();

        $filename = 'TBUT_Export_' . now()->format('Ymd_His') . '.xls';

        return $this->buildXlsx($filename, $tasks, $allSessions);
    }

    /**
     * Export data TBUT untuk satu tugas saja.
     */
    public function exportTask(int $taskId)
    {
        $task = VirtualLabTask::with('material')->findOrFail($taskId);
        $sessions = TbutSession::with('user')
            ->where('task_id', $taskId)
            ->orderByDesc('started_at')
            ->get();

        // Tambahkan relasi task ke setiap sesi
        $sessions->each(fn($s) => $s->setRelation('task', $task));

        $filename = 'TBUT_' . \Illuminate\Support\Str::slug($task->title) . '_' . now()->format('Ymd_His') . '.xls';

        return $this->buildXlsx($filename, collect([$task]), $sessions);
    }

    /**
     * Detail sesi TBUT per task.
     */
    public function show(int $taskId)
    {
        $task = VirtualLabTask::with('material')->findOrFail($taskId);
        $sessions = TbutSession::with('user')
            ->where('task_id', $taskId)
            ->orderByDesc('started_at')
            ->get();

        // ── Effectiveness ──
        $successScores = $sessions->map(fn($s) => $this->getSuccessScore($s));
        $stats = [
            'total'             => $sessions->count(),
            'avg_success_score' => $successScores->isNotEmpty() ? round($successScores->avg(), 2) : null,
            'count_score_0'     => $successScores->filter(fn($s) => $s === 0)->count(),
            'count_score_1'     => $successScores->filter(fn($s) => $s === 1)->count(),
            'count_score_2'     => $successScores->filter(fn($s) => $s === 2)->count(),
        ];
        $stats['success_rate'] = $stats['total'] > 0
            ? round((($stats['count_score_1'] + $stats['count_score_2']) / $stats['total']) * 100, 1) : 0;
        $stats['success_class'] = $stats['avg_success_score'] !== null
            ? $this->classifySuccessScore($stats['avg_success_score']) : null;

        // ── Efficiency ──
        $completedSessions = $sessions->where('is_completed', true);
        $stats['avg_duration']  = $completedSessions->isNotEmpty()
            ? round($completedSessions->avg('duration_seconds')) : null;
        $stats['min_duration']  = $completedSessions->min('duration_seconds');
        $stats['max_duration']  = $completedSessions->max('duration_seconds');
        $stats['efficiency_class'] = $stats['avg_duration'] !== null
            ? $this->classifyEfficiency($stats['avg_duration']) : null;

        // ── Metrik Tambahan ──
        $stats['avg_run_count'] = $sessions->isNotEmpty()
            ? round($sessions->avg('run_count'), 1) : null;

        return view('admin.tbut.show', compact('task', 'sessions', 'stats'));
    }

    /**
     * Build XLSX file menggunakan SpreadsheetML (XML-based, tidak butuh library).
     */
    private function buildXlsx(string $filename, $tasks, $allSessions): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $esc = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_XML1, 'UTF-8');
        $dur = fn($s) => $s > 0 ? gmdate('H:i:s', (int)$s) : '—';

        // ── Sheet 1: Rekap per Tugas (ISO 9241-11) ──
        $sheet1Rows = '';
        $headers1 = [
            'No', 'Materi', 'Judul Tugas', 'Tingkat Kesulitan',
            'Total Peserta',
            'Gagal (Skor 0)', 'Berhasil dgn Kesulitan (Skor 1)', 'Berhasil Tanpa Kesulitan (Skor 2)',
            'Avg Success Score (0-2)', 'Klasifikasi Effectiveness',
            'Task Success Rate (%)',
            'Avg Time-on-Task (H:i:s)', 'Avg Time-on-Task (detik)',
            'Min Durasi (detik)', 'Max Durasi (detik)',
            'Klasifikasi Efficiency',
            'Avg Run Code (x)',
        ];
        $sheet1Rows .= '<Row ss:StyleID="header">';
        foreach ($headers1 as $h) {
            $sheet1Rows .= '<Cell><Data ss:Type="String">' . $esc($h) . '</Data></Cell>';
        }
        $sheet1Rows .= '</Row>';

        foreach ($tasks as $i => $task) {
            $sessions = $task->tbutSessions ?? collect();
            $scores = $sessions->map(fn($s) => $this->getSuccessScore($s));
            $completed = $sessions->where('is_completed', true);

            $avgScore = $scores->isNotEmpty() ? round($scores->avg(), 2) : '—';
            $scoreClass = is_numeric($avgScore) ? $this->classifySuccessScore($avgScore) : null;
            $avgDur = $completed->isNotEmpty() ? round($completed->avg('duration_seconds')) : null;
            $effClass = $avgDur !== null ? $this->classifyEfficiency($avgDur) : null;
            $sr = $sessions->count() > 0
                ? round(($scores->filter(fn($s) => $s >= 1)->count() / $sessions->count()) * 100, 1) : 0;

            $cells = [
                $i + 1,
                $task->material->title ?? '—',
                $task->title,
                ucfirst($task->difficulty ?? '—'),
                $sessions->count(),
                $scores->filter(fn($s) => $s === 0)->count(),
                $scores->filter(fn($s) => $s === 1)->count(),
                $scores->filter(fn($s) => $s === 2)->count(),
                $avgScore,
                $scoreClass['label'] ?? '—',
                $sr,
                $avgDur !== null ? $dur($avgDur) : '—',
                $avgDur ?? '—',
                $completed->min('duration_seconds') ?? '—',
                $completed->max('duration_seconds') ?? '—',
                $effClass['label'] ?? '—',
                $sessions->isNotEmpty() ? round($sessions->avg('run_count'), 1) : '—',
            ];

            $sheet1Rows .= '<Row>';
            foreach ($cells as $val) {
                $type = is_numeric($val) ? 'Number' : 'String';
                $sheet1Rows .= '<Cell><Data ss:Type="' . $type . '">' . $esc($val) . '</Data></Cell>';
            }
            $sheet1Rows .= '</Row>';
        }

        // ── Sheet 2: Detail per Sesi ──
        $sheet2Rows = '';
        $headers2 = [
            'No', 'Materi', 'Judul Tugas', 'Nama Mahasiswa', 'Email',
            'Task Success Score (0-2)', 'Keterangan Skor',
            'Waktu Mulai', 'Waktu Submit',
            'Time-on-Task (H:i:s)', 'Time-on-Task (detik)',
            'Run Code (x)',
        ];
        $sheet2Rows .= '<Row ss:StyleID="header">';
        foreach ($headers2 as $h) {
            $sheet2Rows .= '<Cell><Data ss:Type="String">' . $esc($h) . '</Data></Cell>';
        }
        $sheet2Rows .= '</Row>';

        foreach ($allSessions as $i => $sess) {
            $score = $this->getSuccessScore($sess);
            $scoreLabel = match($score) {
                0 => 'Gagal',
                1 => 'Berhasil dengan kesulitan',
                2 => 'Berhasil tanpa kesulitan',
            };

            $cells2 = [
                $i + 1,
                $sess->task->material->title ?? '—',
                $sess->task->title ?? '—',
                $sess->user->name ?? '—',
                $sess->user->email ?? '—',
                $score,
                $scoreLabel,
                $sess->started_at ? $sess->started_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') : '—',
                $sess->submitted_at ? $sess->submitted_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') : '—',
                $sess->duration_seconds > 0 ? $dur($sess->duration_seconds) : '—',
                $sess->duration_seconds > 0 ? $sess->duration_seconds : 0,
                $sess->run_count,
            ];

            $sheet2Rows .= '<Row>';
            foreach ($cells2 as $val) {
                $type = is_numeric($val) ? 'Number' : 'String';
                $sheet2Rows .= '<Cell><Data ss:Type="' . $type . '">' . $esc($val) . '</Data></Cell>';
            }
            $sheet2Rows .= '</Row>';
        }

        // ── Sheet 3: Ringkasan ──
        $sheet3Rows = '';
        $allScores = $allSessions->map(fn($s) => $this->getSuccessScore($s));
        $completedAll = $allSessions->where('is_completed', true);
        $avgScoreAll = $allScores->isNotEmpty() ? round($allScores->avg(), 2) : '—';
        $avgDurAll = $completedAll->isNotEmpty() ? round($completedAll->avg('duration_seconds')) : '—';

        $summaryData = [
            ['Tanggal Export', now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s')],
            ['Metode', 'Task-Based Usability Testing (Saputra, 2025)'],
            ['Standar Acuan', 'ISO 9241-11 (Effectiveness, Efficiency, Satisfaction)'],
            ['', ''],
            ['EFFECTIVENESS', ''],
            ['Total Peserta', $allSessions->count()],
            ['Gagal (Skor 0)', $allScores->filter(fn($s) => $s === 0)->count()],
            ['Berhasil dgn Kesulitan (Skor 1)', $allScores->filter(fn($s) => $s === 1)->count()],
            ['Berhasil Tanpa Kesulitan (Skor 2)', $allScores->filter(fn($s) => $s === 2)->count()],
            ['Avg Task Success Score', $avgScoreAll],
            ['Task Success Rate (%)', $allSessions->count() > 0
                ? round(($allScores->filter(fn($s) => $s >= 1)->count() / $allSessions->count()) * 100, 1) : 0],
            ['', ''],
            ['EFFICIENCY', ''],
            ['Avg Time-on-Task', is_numeric($avgDurAll) ? $dur($avgDurAll) : '—'],
            ['Avg Time-on-Task (detik)', $avgDurAll],
            ['Avg Run Code (x)', $allSessions->isNotEmpty() ? round($allSessions->avg('run_count'), 1) : '—'],
            ['', ''],
            ['SATISFACTION', ''],
            ['Catatan', 'Belum diukur (memerlukan kuesioner post-task skala 1-4)'],
        ];

        foreach ($summaryData as [$label, $val]) {
            $type = is_numeric($val) ? 'Number' : 'String';
            $sheet3Rows .= '<Row>'
                . '<Cell ss:StyleID="bold"><Data ss:Type="String">' . $esc($label) . '</Data></Cell>'
                . '<Cell><Data ss:Type="' . $type . '">' . $esc($val) . '</Data></Cell>'
                . '</Row>';
        }

        // ── Build XML ──
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel">' . "\n"
            . '<Styles>'
            . '<Style ss:ID="header"><Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/><Interior ss:Color="#4338CA" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/></Style>'
            . '<Style ss:ID="bold"><Font ss:Bold="1"/></Style>'
            . '</Styles>'
            . '<Worksheet ss:Name="Rekap per Tugas"><Table ss:DefaultColumnWidth="120">' . $sheet1Rows . '</Table>'
            . '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><FreezePanes/><FrozenNoSplit/><SplitHorizontal>1</SplitHorizontal><TopRowBottomPane>1</TopRowBottomPane><ActivePane>2</ActivePane></WorksheetOptions>'
            . '</Worksheet>'
            . '<Worksheet ss:Name="Detail per Sesi"><Table ss:DefaultColumnWidth="120">' . $sheet2Rows . '</Table>'
            . '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><FreezePanes/><FrozenNoSplit/><SplitHorizontal>1</SplitHorizontal><TopRowBottomPane>1</TopRowBottomPane><ActivePane>2</ActivePane></WorksheetOptions>'
            . '</Worksheet>'
            . '<Worksheet ss:Name="Ringkasan"><Table ss:DefaultColumnWidth="200">' . $sheet3Rows . '</Table></Worksheet>'
            . '</Workbook>';

        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, $filename, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
