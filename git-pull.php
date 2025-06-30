<?php
$secret = 'b17c7e3de9a84754a8db2b9c749efea0';

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$logfile = __DIR__ . '/git-webhook-log.txt';
$output = [];

exec('cd .. && git pull 2>&1', $output, $result_code);

// Simpan log
file_put_contents($logfile, date('Y-m-d H:i:s') . " - git pull result: " . implode("\n", $output) . "\n\n", FILE_APPEND);

// Tampilkan hasil ke browser
echo "<pre>";
echo "Code: $result_code\n";
echo implode("\n", $output);
echo "</pre>";
