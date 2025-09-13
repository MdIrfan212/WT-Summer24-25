<?php
require_once __DIR__ . '/../model/data_repo.php';

$dir = realpath(__DIR__ . '/../data');
if (!$dir) { http_response_code(500); exit('Data dir missing'); }

$map = ['products','orders','discounts','activity_logs'];
foreach ($map as $name) {
    $file = $dir . DIRECTORY_SEPARATOR . $name . '.json';
    if (is_file($file)) {
        $raw = file_get_contents($file);
        $arr = json_decode($raw, true);
        if (!is_array($arr)) $arr = [];
        save_json($file, $arr);
        echo "Imported $name.json<br>";
    }
}
echo "Done.";
