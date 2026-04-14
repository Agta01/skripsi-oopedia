<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
auth()->login(\App\Models\User::first());
$matStats = collect([
    (object)['title'=>'A','completion_rate'=>50,'active_students'=>1]
]);

$html = view('admin.dashboard.index', [
    'userName'=>'A',
    'userRole'=>'A',
    'totalStudents'=>1,
    'activeStudents'=>1,
    'totalMaterials'=>1,
    'totalQuestions'=>1,
    'materialStats'=>$matStats,
    'tbutCompleted'=>6,
    'tbutTotal'=>6,
    'tbutAvgDur'=>1000,
    'tbutAvgRun'=>8.7,
    'studentProgress'=>[],
    'popularMaterials'=>[],
    'recentProgress'=>[]
])->render();

file_put_contents('public/dashboard_test.html', $html);
echo "HTML written to public/dashboard_test.html\n";
