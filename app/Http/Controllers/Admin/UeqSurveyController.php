<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UeqSurvey;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UeqSurveyExport;

class UeqSurveyController extends Controller
{
    public function __construct()
    {
        // Tambahkan middleware untuk memastikan hanya admin dan superadmin yang bisa mengakses
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id > 2) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat hasil UEQ Survey');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Ambil semua data survey dengan relasi user
        $query = UeqSurvey::with('user');
        
        // Filter berdasarkan kelas jika ada
        if ($request->has('class') && !empty($request->class)) {
            $query->where('class', $request->class);
        }
        
        $surveys = $query->get();
        
        // Daftar kelas unik untuk filter dropdown
        $classes = UeqSurvey::distinct()->pluck('class')->filter()->values();
        
        // Hitung statistik UEQ (mean, variance, benchmark per item & skala)
        $stats      = $this->calculateAverages($surveys);
        $itemStats  = $stats['items']  ?? [];
        $scaleStats = $stats['scales'] ?? [];
        // Backward-compat: $averages['scale'] = mean
        $averages   = collect($scaleStats)->map(fn($s) => $s['mean'])->toArray();
        
        // Untuk sidebar materials dropdown
        $materials = Material::all();
        
        return view('admin.ueq.index', [
            'surveys'    => $surveys,
            'averages'   => $averages,
            'itemStats'  => $itemStats,
            'scaleStats' => $scaleStats,
            'materials'  => $materials,
            'classes'    => $classes,
            'activePage' => 'ueq',
            'userName'   => auth()->user()->name,
            'userRole'   => auth()->user()->role->role_name
        ]);
    }
    
    // -----------------------------------------------------------------------
    // Official UEQ item definitions:
    // Each entry: [db_column, left_label, right_label, scale, polarity]
    // polarity = 'R' (right=positive, value-4) | 'L' (left=positive, 4-value)
    // -----------------------------------------------------------------------
    private function ueqItems(): array
    {
        return [
            // Attractiveness (6 items)
            ['col'=>'annoying_enjoyable',             'left'=>'annoying',         'right'=>'enjoyable',       'scale'=>'attractiveness', 'dir'=>'R'],
            ['col'=>'good_bad',                       'left'=>'good',             'right'=>'bad',             'scale'=>'attractiveness', 'dir'=>'L'],
            ['col'=>'unlikable_pleasing',             'left'=>'unlikable',        'right'=>'pleasing',        'scale'=>'attractiveness', 'dir'=>'R'],
            ['col'=>'unpleasant_pleasant',            'left'=>'unpleasant',       'right'=>'pleasant',        'scale'=>'attractiveness', 'dir'=>'R'],
            ['col'=>'attractive_unattractive',        'left'=>'attractive',       'right'=>'unattractive',    'scale'=>'attractiveness', 'dir'=>'L'],
            ['col'=>'friendly_unfriendly',            'left'=>'friendly',         'right'=>'unfriendly',      'scale'=>'attractiveness', 'dir'=>'L'],
            // Perspicuity (4 items)
            ['col'=>'not_understandable_understandable','left'=>'not understandable','right'=>'understandable','scale'=>'perspicuity',   'dir'=>'R'],
            ['col'=>'easy_difficult',                 'left'=>'easy',             'right'=>'difficult',       'scale'=>'perspicuity',    'dir'=>'L'],
            ['col'=>'complicated_easy',               'left'=>'complicated',      'right'=>'easy',            'scale'=>'perspicuity',    'dir'=>'R'],
            ['col'=>'clear_confusing',                'left'=>'clear',            'right'=>'confusing',       'scale'=>'perspicuity',    'dir'=>'L'],
            // Efficiency (4 items)
            ['col'=>'fast_slow',                      'left'=>'fast',             'right'=>'slow',            'scale'=>'efficiency',     'dir'=>'L'],
            ['col'=>'inefficient_efficient',          'left'=>'inefficient',      'right'=>'efficient',       'scale'=>'efficiency',     'dir'=>'R'],
            ['col'=>'impractical_practical',          'left'=>'impractical',      'right'=>'practical',       'scale'=>'efficiency',     'dir'=>'R'],
            ['col'=>'organized_cluttered',            'left'=>'organized',        'right'=>'cluttered',       'scale'=>'efficiency',     'dir'=>'L'],
            // Dependability (4 items)
            ['col'=>'unpredictable_predictable',      'left'=>'unpredictable',    'right'=>'predictable',     'scale'=>'dependability',  'dir'=>'R'],
            ['col'=>'obstructive_supportive',         'left'=>'obstructive',      'right'=>'supportive',      'scale'=>'dependability',  'dir'=>'R'],
            ['col'=>'secure_not_secure',              'left'=>'secure',           'right'=>'not secure',      'scale'=>'dependability',  'dir'=>'L'],
            ['col'=>'meets_expectations_does_not_meet','left'=>'meets expectations','right'=>'does not meet', 'scale'=>'dependability',  'dir'=>'L'],
            // Stimulation (4 items)
            ['col'=>'valuable_inferior',              'left'=>'valuable',         'right'=>'inferior',        'scale'=>'stimulation',    'dir'=>'L'],
            ['col'=>'boring_exciting',                'left'=>'boring',           'right'=>'exciting',        'scale'=>'stimulation',    'dir'=>'R'],
            ['col'=>'not_interesting_interesting',    'left'=>'not interesting',  'right'=>'interesting',     'scale'=>'stimulation',    'dir'=>'R'],
            ['col'=>'motivating_demotivating',        'left'=>'motivating',       'right'=>'demotivating',    'scale'=>'stimulation',    'dir'=>'L'],
            // Novelty (4 items)
            ['col'=>'creative_dull',                  'left'=>'creative',         'right'=>'dull',            'scale'=>'novelty',        'dir'=>'L'],
            ['col'=>'inventive_conventional',         'left'=>'inventive',        'right'=>'conventional',    'scale'=>'novelty',        'dir'=>'L'],
            ['col'=>'usual_leading_edge',             'left'=>'usual',            'right'=>'leading edge',    'scale'=>'novelty',        'dir'=>'R'],
            ['col'=>'conservative_innovative',        'left'=>'conservative',     'right'=>'innovative',      'scale'=>'novelty',        'dir'=>'R'],
        ];
    }

    // UEQ Benchmark thresholds (from official UEQ benchmark dataset, 246 products)
    private function benchmarkThresholds(): array
    {
        return [
            'attractiveness' => ['excellent'=>1.36,  'good'=>0.97,  'above_avg'=>0.48,  'below_avg'=>-0.29, 'bad'=>-0.87],
            'perspicuity'    => ['excellent'=>1.50,  'good'=>1.17,  'above_avg'=>0.64,  'below_avg'=>0.02,  'bad'=>-0.56],
            'efficiency'     => ['excellent'=>1.41,  'good'=>1.08,  'above_avg'=>0.61,  'below_avg'=>0.19,  'bad'=>-0.31],
            'dependability'  => ['excellent'=>1.26,  'good'=>0.98,  'above_avg'=>0.53,  'below_avg'=>0.09,  'bad'=>-0.44],
            'stimulation'    => ['excellent'=>1.38,  'good'=>1.03,  'above_avg'=>0.57,  'below_avg'=>0.14,  'bad'=>-0.44],
            'novelty'        => ['excellent'=>1.32,  'good'=>0.93,  'above_avg'=>0.33,  'below_avg'=>-0.27, 'bad'=>-0.64],
        ];
    }

    private function classifyBenchmark(float $mean, string $scale): array
    {
        $t = $this->benchmarkThresholds()[$scale];
        if ($mean >= $t['excellent'])  return ['label'=>'Excellent',     'color'=>'#16a34a', 'bg'=>'#dcfce7'];
        if ($mean >= $t['good'])       return ['label'=>'Good',          'color'=>'#2563eb', 'bg'=>'#dbeafe'];
        if ($mean >= $t['above_avg'])  return ['label'=>'Above Average', 'color'=>'#d97706', 'bg'=>'#fef9c3'];
        if ($mean >= $t['below_avg'])  return ['label'=>'Below Average', 'color'=>'#f97316', 'bg'=>'#ffedd5'];
        if ($mean >= $t['bad'])        return ['label'=>'Bad',           'color'=>'#dc2626', 'bg'=>'#fee2e2'];
        return                                ['label'=>'Awful',         'color'=>'#7c3aed', 'bg'=>'#ede9fe'];
    }

    private function calculateAverages($surveys)
    {
        if ($surveys->isEmpty()) {
            return [];
        }

        $items   = $this->ueqItems();
        $n       = $surveys->count();

        // ---- Per-item converted values (all surveys) ----
        $itemCollected = array_fill(0, count($items), []);
        foreach ($surveys as $survey) {
            foreach ($items as $i => $item) {
                $raw = $survey->{$item['col']};
                $converted = $item['dir'] === 'R' ? ($raw - 4) : (4 - $raw);
                $itemCollected[$i][] = $converted;
            }
        }

        // ---- Item-level stats ----
        $itemStats = [];
        foreach ($items as $i => $item) {
            $vals = $itemCollected[$i];
            $mean = array_sum($vals) / $n;
            $variance = $n > 1
                ? array_sum(array_map(fn($v) => ($v - $mean) ** 2, $vals)) / ($n - 1)
                : 0;
            $itemStats[] = [
                'no'       => $i + 1,
                'col'      => $item['col'],
                'left'     => $item['left'],
                'right'    => $item['right'],
                'scale'    => $item['scale'],
                'mean'     => round($mean, 2),
                'variance' => round($variance, 2),
                'std_dev'  => round(sqrt($variance), 2),
                'n'        => $n,
            ];
        }

        // ---- Scale-level stats ----
        // Mengikuti metode resmi UEQ Data Analysis Tool (Excel):
        // Mean  = rata-rata semua nilai item yang sudah dikonversi (semua responden × semua item dalam skala)
        // Variance = variance dari semua nilai item individual tersebut (bukan dari per-respondent mean)
        $scales = ['attractiveness','perspicuity','efficiency','dependability','stimulation','novelty'];
        $scaleStats = [];
        foreach ($scales as $scale) {
            // Kumpulkan SEMUA nilai item yang sudah dikonversi untuk skala ini
            // (jumlah_item × jumlah_responden nilai)
            $allItemVals = [];
            foreach ($items as $i => $item) {
                if ($item['scale'] === $scale) {
                    foreach ($itemCollected[$i] as $v) {
                        $allItemVals[] = $v;
                    }
                }
            }

            $totalCount = count($allItemVals); // = jumlah_item × n
            $meanScale  = $totalCount > 0 ? array_sum($allItemVals) / $totalCount : 0;

            // Variance menggunakan (n-1) — sample variance — sama seperti Excel UEQ tool
            $varScale = $totalCount > 1
                ? array_sum(array_map(fn($v) => ($v - $meanScale) ** 2, $allItemVals)) / ($totalCount - 1)
                : 0;

            $bench = $this->classifyBenchmark($meanScale, $scale);
            $scaleStats[$scale] = [
                'mean'      => round($meanScale, 3),
                'variance'  => round($varScale, 3),
                'std_dev'   => round(sqrt($varScale), 3),
                'n'         => $n,
                'benchmark' => $bench,
            ];
        }

        return [
            'items'  => $itemStats,
            'scales' => $scaleStats,
        ];
    }

    /**
     * Export UEQ Survey results filtered by class
     */
    public function export(Request $request)
    {
        $class = $request->input('class');
        
        // Query data
        $query = UeqSurvey::with('user');
        if ($class) {
            $query->where('class', $class);
        }
        $surveys = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ueq-survey-results.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($surveys, $headers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'NIM', 'Nama Pengguna', 'Email', 'Kelas', 'Tanggal Pengisian',
                // 26 aspek UEQ
                'Annoying - Enjoyable',
                'Not Understandable - Understandable',
                'Creative - Dull',
                'Easy - Difficult',
                'Valuable - Inferior',
                'Boring - Exciting',
                'Not Interesting - Interesting',
                'Unpredictable - Predictable',
                'Fast - Slow',
                'Inventive - Conventional',
                'Obstructive - Supportive',
                'Good - Bad',
                'Complicated - Easy',
                'Unlikable - Pleasing',
                'Usual - Leading Edge',
                'Unpleasant - Pleasant',
                'Secure - Not Secure',
                'Motivating - Demotivating',
                'Meets Expectations - Does Not Meet',
                'Inefficient - Efficient',
                'Clear - Confusing',
                'Impractical - Practical',
                'Organized - Cluttered',
                'Attractive - Unattractive',
                'Friendly - Unfriendly',
                'Conservative - Innovative',
                'Komentar', 'Saran'
            ]);
            
            // Add data rows
            foreach ($surveys as $survey) {
                fputcsv($file, [
                    $survey->id,
                    $survey->nim ?? '',
                    optional($survey->user)->name ?? 'Tidak ada',
                    optional($survey->user)->email ?? 'Tidak ada',
                    $survey->class ?? '',
                    $survey->created_at->format('d/m/Y H:i'),
                    // 26 aspek UEQ
                    $survey->annoying_enjoyable,
                    $survey->not_understandable_understandable,
                    $survey->creative_dull,
                    $survey->easy_difficult,
                    $survey->valuable_inferior,
                    $survey->boring_exciting,
                    $survey->not_interesting_interesting,
                    $survey->unpredictable_predictable,
                    $survey->fast_slow,
                    $survey->inventive_conventional,
                    $survey->obstructive_supportive,
                    $survey->good_bad,
                    $survey->complicated_easy,
                    $survey->unlikable_pleasing,
                    $survey->usual_leading_edge,
                    $survey->unpleasant_pleasant,
                    $survey->secure_not_secure,
                    $survey->motivating_demotivating,
                    $survey->meets_expectations_does_not_meet,
                    $survey->inefficient_efficient,
                    $survey->clear_confusing,
                    $survey->impractical_practical,
                    $survey->organized_cluttered,
                    $survey->attractive_unattractive,
                    $survey->friendly_unfriendly,
                    $survey->conservative_innovative,
                    $survey->comments ?? '',
                    $survey->suggestions ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function detail($userId)
    {
        $survey = UeqSurvey::where('user_id', $userId)->firstOrFail();
        $user = $survey->user;

        return view('admin.ueq.detail', compact('survey', 'user'));
    }
} 