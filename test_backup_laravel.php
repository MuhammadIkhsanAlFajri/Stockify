
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$connection = config('database.connections.mysql');

var_dump([
    'driver' => $connection['driver'],
    'host' => $connection['host'],
    'port' => $connection['port'],
    'database' => $connection['database'],
    'username' => $connection['username'],
]);

$command = [
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    '--protocol=TCP',
    '--host=127.0.0.1',
    '--port=3307',
    '--user=' . $connection['username'],
    '--single-transaction',
    '--routines',
    '--triggers',
    '--default-character-set=utf8mb4',
    $connection['database'],
];

if (!empty($connection['password'])) {
    $command[] = '--password=' . $connection['password'];
}

$process = new \Symfony\Component\Process\Process($command);
$process->setTimeout(300);
$process->run();

echo "\nExit code: " . $process->getExitCode() . "\n";
echo "Output error:\n" . $process->getErrorOutput() . "\n";
echo "Output:\n" . substr($process->getOutput(), 0, 1000) . "\n";
