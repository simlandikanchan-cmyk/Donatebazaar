<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup
                            {--path= : Custom backup directory relative to storage/app}
                            {--keep=7 : Number of backups to keep}
                            {--compress : Compress backup with gzip}
                            {--only-data : Backup only data, no schema}
                            {--only-schema : Backup only schema, no data}';

    protected $description = 'Backup the application database to a timestamped SQL file.';

    public function handle(): int
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if (! $config || $config['driver'] !== 'mysql') {
            $this->error("Unsupported database driver: {$config['driver']}. Only MySQL is supported.");

            return self::FAILURE;
        }

        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        if (empty($database) || empty($username)) {
            $this->error('Database configuration is incomplete. Check your .env file.');

            return self::FAILURE;
        }

        $backupPath = $this->option('path') ?: 'backups/database';
        $keep = (int) $this->option('keep');
        $compress = $this->option('compress');
        $onlyData = $this->option('only-data');
        $onlySchema = $this->option('only-schema');

        if ($onlyData && $onlySchema) {
            $this->error('Cannot use --only-data and --only-schema together.');

            return self::FAILURE;
        }

        if (! Storage::exists($backupPath)) {
            Storage::makeDirectory($backupPath);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "{$database}_{$timestamp}.sql";
        $fullPath = storage_path("app/{$backupPath}/{$filename}");

        if ($compress) {
            $fullPath .= '.gz';
        }

        $this->info("Creating backup: {$filename}");

        $command = $this->buildMysqldumpCommand(
            $host,
            $port,
            $database,
            $username,
            $password,
            $fullPath,
            $onlyData,
            $onlySchema,
            $compress
        );

        $startTime = microtime(true);

        try {
            $this->executeCommand($command);
        } catch (RuntimeException $e) {
            $this->error("Backup failed: {$e->getMessage()}");
            Log::channel('backups')->error('Database backup failed', [
                'database' => $database,
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }

        $duration = round(microtime(true) - $startTime, 2);
        $size = Storage::exists(str_replace(storage_path('app/'), '', $fullPath))
            ? Storage::size(str_replace(storage_path('app/'), '', $fullPath))
            : 0;

        $this->info("Backup completed in {$duration}s. Size: " . $this->formatBytes($size));

        if ($keep > 0) {
            $this->rotateBackups($backupPath, $keep);
        }

        Log::channel('backups')->info('Database backup completed', [
            'database' => $database,
            'file' => $filename,
            'size' => $size,
            'duration_seconds' => $duration,
        ]);

        return self::SUCCESS;
    }

    private function buildMysqldumpCommand(
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
        string $outputPath,
        bool $onlyData,
        bool $onlySchema,
        bool $compress
    ): string {
        $mysqldump = $this->resolveMysqldumpPath();

        if (! $mysqldump || ! file_exists($mysqldump)) {
            throw new RuntimeException('mysqldump binary not found. Please install MySQL client tools.');
        }

        $command = [
            escapeshellarg($mysqldump),
            '--host=' . escapeshellarg($host),
            '--port=' . (int) $port,
            '--user=' . escapeshellarg($username),
            '--single-transaction',
            '--routines',
            '--triggers',
            '--events',
        ];

        if ($password !== '') {
            $command[] = '--password=' . escapeshellarg($password);
        }

        if ($onlyData) {
            $command[] = '--no-create-info';
        }

        if ($onlySchema) {
            $command[] = '--no-data';
        }

        $command[] = escapeshellarg($database);

        if ($compress) {
            $command[] = '| gzip > ' . escapeshellarg($outputPath);
        } else {
            $command[] = '> ' . escapeshellarg($outputPath);
        }

        return implode(' ', $command);
    }

    private function resolveMysqldumpPath(): ?string
    {
        $candidates = [
            'C:/xampp/mysql/bin/mysqldump.exe',
            'C:/xampp/mysql/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function executeCommand(string $command): void
    {
        if (str_contains($command, '| gzip')) {
            $parts = explode('|', $command);
            $dumpCommand = trim($parts[0]);
            $redirect = trim($parts[1] ?? '');

            $fullCommand = $dumpCommand . ' ' . $redirect;
        } else {
            $fullCommand = $command;
        }

        $output = [];
        $exitCode = 0;

        if (PHP_OS_FAMILY === 'Windows') {
            $fullCommand = str_replace(['/', '\\'], '\\', $fullCommand);
            exec($fullCommand . ' 2>&1', $output, $exitCode);
        } else {
            exec($fullCommand . ' 2>&1', $output, $exitCode);
        }

        if ($exitCode !== 0) {
            $errorOutput = is_array($output) ? implode("\n", $output) : (string) $output;
            throw new RuntimeException("mysqldump exited with code {$exitCode}. Command: {$fullCommand}. Output: {$errorOutput}");
        }
    }

    private function rotateBackups(string $path, int $keep): void
    {
        $files = collect(Storage::files($path))
            ->filter(fn ($file) => preg_match('/\.(sql|sql\.gz)$/', $file))
            ->sortDesc()
            ->values()
            ->all();

        $toDelete = array_slice($files, $keep);

        foreach ($toDelete as $file) {
            Storage::delete($file);
            $this->line("Rotated old backup: {$file}");
        }
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
