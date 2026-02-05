<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\User;
use App\Models\Question;
use App\Models\Progress;
use App\Models\QuestionBankConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        
        // Get progress statistics
        $progressStats = DB::table('progress')
            ->select(
                'material_id',
                DB::raw('COUNT(DISTINCT question_id) as answered_questions'),
                DB::raw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_answers')
            )
            ->where('user_id', $userId)
            ->groupBy('material_id')
            ->get();

        $materials = Material::with(['questions'])->get()
            ->map(function($material) use ($progressStats) {
                $totalQuestions = $material->questions->count();
                $materialProgress = $progressStats->firstWhere('material_id', $material->id);
                
                $correctAnswers = $materialProgress ? $materialProgress->correct_answers : 0;
                $progressPercentage = $totalQuestions > 0 
                    ? min(100, round(($correctAnswers / $totalQuestions) * 100))
                    : 0;

                return (object)[
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'progress_percentage' => $progressPercentage,
                    'total_questions' => $totalQuestions,
                    'completed_questions' => $correctAnswers
                ];
            });

        return view('mahasiswa.dashboard.dashboard', [
            'materials' => Material::all(), // For navbar
            'dashboardMaterials' => $materials // For dashboard cards
        ]);
    }

    public function materi($slug = null)
    {
        $materials = Material::all();
        if ($slug) {
            $material = Material::where('title', str_replace('-', ' ', $slug))->firstOrFail();
            return view('mahasiswa.materi', compact('materials', 'material'));
        }
        return view('mahasiswa.materi', compact('materials'));
    }

    public function leaderboard()
    {
        // 1. Caching Strategy: Cache leaderboard data for 5 minutes (300 seconds)
        $leaderboardData = \Illuminate\Support\Facades\Cache::remember('leaderboard_data', 300, function () {
            
            // A. Hitung konfigurasi soal (tetap diperlukan untuk badge & persentase)
            $materials = Material::with(['questionBankConfigs' => function($query) {
                $query->where('is_active', true);
            }])->get();

            $totalBeginner = 0; $totalMedium = 0; $totalHard = 0;
            $totalConfiguredQuestions = 0;

            foreach ($materials as $material) {
                $config = $material->questionBankConfigs->first();
                if ($config) {
                    $totalBeginner += $config->beginner_count;
                    $totalMedium += $config->medium_count;
                    $totalHard += $config->hard_count;
                } else {
                    $totalBeginner += $material->questions()->where('difficulty', 'beginner')->count();
                    $totalMedium += $material->questions()->where('difficulty', 'medium')->count();
                    $totalHard += $material->questions()->where('difficulty', 'hard')->count();
                }
            }
            $totalConfiguredQuestions = $totalBeginner + $totalMedium + $totalHard;

            // B. OPTIMASI SKOR: Hitung skor langsung di Database menggunakan Subquery
            // Subquery: Dapatkan attempt minimum per user per soal
            $subQuery = DB::table('progress')
                ->join('questions', 'progress.question_id', '=', 'questions.id')
                ->join('users', 'progress.user_id', '=', 'users.id')
                ->select('progress.user_id', 'progress.question_id', 'questions.difficulty')
                ->selectRaw('MIN(progress.attempt_number) as min_attempt')
                ->where('progress.is_correct', 1)
                ->where('users.role_id', 3)
                ->groupBy('progress.user_id', 'progress.question_id', 'questions.difficulty');

            // Main Query: Hitung Total Skor User
            $userScores = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
                ->mergeBindings($subQuery)
                ->select('user_id')
                ->selectRaw('SUM(FLOOR(
                    (CASE difficulty 
                        WHEN "beginner" THEN 5 
                        WHEN "medium" THEN 10 
                        WHEN "hard" THEN 15 
                        ELSE 0 END) *
                    (CASE 
                        WHEN min_attempt = 1 THEN 1.0 
                        WHEN min_attempt = 2 THEN 0.8 
                        WHEN min_attempt = 3 THEN 0.6 
                        WHEN min_attempt = 4 THEN 0.4 
                        ELSE 0.2 END)
                )) as total_score')
                ->groupBy('user_id')
                ->pluck('total_score', 'user_id');

            // C. Ambil Data Statistik Leaderboard
            $data = DB::table('users')
                ->leftJoin('progress', 'users.id', '=', 'progress.user_id')
                ->leftJoin('questions', 'progress.question_id', '=', 'questions.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    DB::raw('COUNT(DISTINCT CASE WHEN progress.is_correct = 1 THEN progress.question_id END) as total_correct_questions'),
                    DB::raw('COUNT(DISTINCT progress.question_id) as total_attempted'),
                    DB::raw('SUM(CASE WHEN progress.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers'),
                    DB::raw('MAX(progress.updated_at) as completion_date'),
                    DB::raw('COUNT(DISTINCT CASE WHEN progress.is_correct = 1 AND questions.difficulty = "beginner" THEN progress.question_id END) as beginner_completed'),
                    DB::raw('COUNT(DISTINCT CASE WHEN progress.is_correct = 1 AND questions.difficulty = "medium" THEN progress.question_id END) as medium_completed'),
                    DB::raw('COUNT(DISTINCT CASE WHEN progress.is_correct = 1 AND questions.difficulty = "hard" THEN progress.question_id END) as hard_completed')
                )
                ->where('users.role_id', 3)
                ->groupBy('users.id', 'users.name', 'users.email')
                ->get();

            // D. Gabungkan Skor dan Hitung Atribut Tambahan
            foreach ($data as $user) {
                // Pasang skor dari hasil query optimasi
                $user->weighted_score = $userScores[$user->id] ?? 0;
                $user->formatted_score = number_format($user->weighted_score, 0, ',', '.');
                
                // Hitung Persentase
                $user->percentage = $totalConfiguredQuestions > 0
                    ? min(100, round(($user->total_correct_questions / $totalConfiguredQuestions) * 100))
                    : 0;

                // Tentukan Badge
                if ($user->hard_completed >= $totalHard && $totalHard > 0) {
                    $user->badge = 'Hard';
                    $user->badge_color = 'danger';
                } elseif ($user->medium_completed >= $totalMedium && $totalMedium > 0) {
                    $user->badge = 'Medium';
                    $user->badge_color = 'warning';
                } elseif ($user->beginner_completed >= $totalBeginner && $totalBeginner > 0) {
                    $user->badge = 'Beginner';
                    $user->badge_color = 'success';
                } else {
                    $user->badge = 'Learner';
                    $user->badge_color = 'secondary';
                }
            }

            // E. Urutkan dan Return
            return $data->sortByDesc('weighted_score')->values();
        });

        // 2. Assign Rank (Dilakukan di luar cache agar index-nya selalu fresh saat ditampilkan, 
        // meskipun datanya sama, logic rank sederhana ini cepat)
        // 2. Assign Rank Globally (Pre-Pagination)
        $rank = 1;
        foreach ($leaderboardData as $data) {
            $data->rank = $rank++;
        }
        
        // 3. Find Current User Rank (Before slicing)
        $currentUserId = auth()->id();
        $currentUserRank = $leaderboardData->firstWhere('id', $currentUserId);

        // 4. Manual Pagination
        $page = request()->get('page', 1);
        $perPage = 10;
        
        // Slice items for current page
        $pagedData = $leaderboardData->slice(($page - 1) * $perPage, $perPage)->all();
        
        // Create paginator
        $leaderboardData = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData, 
            $leaderboardData->count(), 
            $perPage, 
            $page, 
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('mahasiswa.leaderboard', compact('leaderboardData', 'currentUserRank'));
    }
}