<?php
$c = file_get_contents('https://wandbox.org/api/list.json');
$r = json_decode($c, true);
$compilers = [];
foreach($r as $o) {
    if (strpos(strtolower($o['language']), 'java') !== false) {
        $compilers[] = $o['name'];
    }
}
file_put_contents('compilers.txt', implode("\n", $compilers));
