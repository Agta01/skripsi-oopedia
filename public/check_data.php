<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Cek Data Dashboard ===\n\n";

echo "1. Progress table row count: " . DB::table('progress')->count() . "\n";
echo "2. Materials count: " . DB::table('materials')->count() . "\n";
echo "3. TBUT Sessions count: " . DB::table('tbut_sessions')->count() . "\n";
echo "4. TBUT Completed: " . DB::table('tbut_sessions')->where('is_completed', true)->count() . "\n\n";

echo "5. materialStats query result:\n";
$stats = DB::table('materials')
    ->leftJoin('progress', function($join) {
        $join->on('materials.id', '=', 'progress.material_id')
             ->where('progress.is_correct', '=', true);
    })
    ->select(
        'materials.id',
        'materials.title',
        DB::raw('COUNT(DISTINCT progress.user_id) as active_students'),
        DB::raw('ROUND((COUNT(DISTINCT CASE WHEN progress.is_correct = 1 THEN progress.id ELSE NULL END) * 100.0) / NULLIF(COUNT(DISTINCT progress.id), 0), 1) as completion_rate')
    )
    ->groupBy('materials.id', 'materials.title')
    ->orderByDesc('active_students')
    ->limit(5)
    ->get();

foreach ($stats as $s) {
    echo "  - {$s->title}: active={$s->active_students}, rate={$s->completion_rate}\n";
}

echo "\n6. Table names available:\n";
$tables = DB::select("SHOW TABLES");
foreach ($tables as $t) {
    foreach ((array)$t as $name) {
        echo "  - $name\n";
    }
}
