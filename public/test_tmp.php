<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new \App\Http\Controllers\Admin\DashboardController();
$reflection = new ReflectionMethod($controller, 'getMaterialStatistics');
$reflection->setAccessible(true);
$materialStats = $reflection->invoke($controller);

echo "matLabels: " . json_encode($materialStats->pluck('title')) . "\n";
echo "matRates: " . json_encode($materialStats->pluck('completion_rate')) . "\n";
echo "matCount: " . json_encode($materialStats->pluck('active_students')) . "\n";
