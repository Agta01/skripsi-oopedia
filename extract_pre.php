<?php
$html = file_get_contents('scratch_out.html');
preg_match('/<pre[^>]*>(.*?)<\/pre>/is', $html, $matches);
echo "Output from `<pre>`:\n" . trim(strip_tags($matches[1] ?? 'NOT FOUND'));
