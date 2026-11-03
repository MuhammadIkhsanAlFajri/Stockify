<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class DatabaseBackupController extends Controller
{
    private function backupDirectory(): string
    {
        $directory = 'backups';

        Storage::disk('local')->makeDirectory($directory);

        return $directory;
    }


    private function createBackup(): string
    {
        $connection = config('database.connections.mysql');

        if (($connection['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('Koneksi database bukan MySQL.');
        }

        $directory = $this->backupDirectory();

        $filename = 'stockify_' . now()->format('Ymd_His')
            . '_' . Str::random(6) . '.sql';

        $relativePath = $directory . '/' . $filename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        $binary = env(
            'STOCKIFY_MYSQLDUMP_PATH',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe'
        );

        if (!is_file($binary)) {
            throw new RuntimeException(
                'Program mysqldump tidak ditemukan di: ' . $binary
            );
        }


        $command = [
            $binary,
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


        $process = new Process(
            $command,
            base_path(),
            [
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
                'WINDIR' => getenv('WINDIR') ?: 'C:\\Windows',
                'PATH' => getenv('PATH') ?: '',
            ]
        );

        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            $error = trim($process->getErrorOutput());
            $output = trim($process->getOutput());

            Storage::disk('local')->delete($relativePath);

            throw new RuntimeException(
                'mysqldump gagal. '
                    . ($error !== '' ? $error : $output)
            );
        }

        $sql = $process->getOutput();

        if (trim($sql) === '') {
            throw new RuntimeException(
                'mysqldump berhasil dijalankan tetapi output SQL kosong.'
            );
        }

        $written = Storage::disk('local')->put($relativePath, $sql);

        if (
            !$written || !Storage::disk('local')->exists($relativePath)
            || Storage::disk('local')->size($relativePath) === 0
        ) {
            Storage::disk('local')->delete($relativePath);

            throw new RuntimeException(
                'File backup SQL gagal disimpan atau kosong.'
            );
        }

        return $relativePath;
    }


    public function index(): View
    {
        $directory = $this->backupDirectory();

        $files = collect(
            Storage::disk('local')->files($directory)
        )
            ->filter(fn($path) => Str::endsWith($path, '.sql'))
            ->sortDesc()
            ->map(fn($path) => [
                'path' => $path,
                'name' => basename($path),
                'size' => Storage::disk('local')->size($path),
                'date' => date(
                    'd/m/Y H:i:s',
                    Storage::disk('local')->lastModified($path)
                ),
            ])
            ->values();

        return view('settings.backup', compact('files'));
    }

    public function store(): RedirectResponse
    {
        try {
            $path = $this->createBackup();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'database_backup',
                'description' => 'Membuat backup database Stockify.',
                'properties' => [
                    'filename' => basename($path),
                ],
            ]);

            return redirect()
                ->route('settings.backup')
                ->with('success', 'Backup berhasil dibuat: ' . basename($path));
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'backup' => 'Backup gagal: ' . $e->getMessage(),
            ]);
        }
    }

    public function download(string $filename): BinaryFileResponse
    {
        abort_unless(
            preg_match('/\Astockify_[A-Za-z0-9_.-]+\.sql\z/', $filename) === 1,
            404
        );

        $path = 'backups/' . $filename;

        abort_unless(Storage::disk('local')->exists($path), 404);

        return response()->download(
            Storage::disk('local')->path($path),
            $filename
        );
    }

    public function restore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'backup_file' => ['required', 'file', 'extensions:sql', 'max:102400'],
            'confirmation' => ['required', 'in:RESTORE'],
        ], [
            'confirmation.in' => 'Ketik RESTORE dengan benar untuk melanjutkan.',
            'backup_file.max' => 'Ukuran file backup maksimal 100 MB.',
        ]);

        $connection = config('database.connections.mysql');

        $binary = env(
            'STOCKIFY_MYSQL_PATH',
            'C:\\xampp\\mysql\\bin\\mysql.exe'
        );

        $uploaded = $validated['backup_file'];
        $input = null;

        try {
            // Selalu buat backup kondisi saat ini sebelum restore.
            $safetyBackup = $this->createBackup();

            $input = fopen($uploaded->getRealPath(), 'rb');

            if ($input === false) {
                throw new RuntimeException('File backup tidak dapat dibaca.');
            }


            $command = [
                $binary,
                '--protocol=TCP',
                '--host=127.0.0.1',
                '--port=3307',
                '--user=' . $connection['username'],
                '--default-character-set=utf8mb4',
                $connection['database'],
            ];

            if (!empty($connection['password'])) {
                $command[] = '--password=' . $connection['password'];
            }

            $process = new Process($command);
            $process->setInput($input);
            $process->setTimeout(600);
            $process->run();

            fclose($input);
            $input = null;

            if (!$process->isSuccessful()) {
                throw new RuntimeException(
                    'Restore gagal. Database mungkin sudah berubah sebagian. Backup pengaman: '
                        . basename($safetyBackup)
                );
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'database_restore',
                'description' => 'Memulihkan database Stockify dari file backup.',
                'properties' => [
                    'uploaded_filename' => $uploaded->getClientOriginalName(),
                    'safety_backup' => basename($safetyBackup),
                ],
            ]);

            return redirect()
                ->route('settings.backup')
                ->with('success', 'Restore berhasil. Backup pengaman sebelum restore: '
                    . basename($safetyBackup));
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'restore' => 'Restore gagal. Periksa file SQL dan konfigurasi database. '
                    . 'Jika proses sudah dimulai, periksa kondisi database sebelum digunakan kembali.',
            ]);
        } finally {
            if (is_resource($input)) {
                fclose($input);
            }
        }
    }
}
