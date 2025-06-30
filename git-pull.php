<?php
$secret = 'b17c7e3de9a84754a8db2b9c749efea0';

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$logfile = __DIR__ . '/git-webhook-log.txt';
$output = [];
$result_code = 0;

// Ganti dirname(__DIR__) → __DIR__ karena .git ada di dalam public_html
$command = 'cd ' . escapeshellarg(__DIR__) . ' && git pull 2>&1';

exec($command, $output, $result_code);

file_put_contents(
    $logfile,
    date('Y-m-d H:i:s') . " - CMD: $command\nResult Code: $result_code\n" . implode("\n", $output) . "\n\n",
    FILE_APPEND
);

echo "<pre>Command: $command\nCode: $result_code\n";
echo htmlspecialchars(implode("\n", $output));
echo "</pre>";
