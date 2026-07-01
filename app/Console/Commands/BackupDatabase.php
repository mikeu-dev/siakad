<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database ke file SQL terkompresi';

    public function handle(): int
    {
        $dbHost     = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort     = config('database.connections.mysql.port', '3306');
        $dbName     = config('database.connections.mysql.database');
        $dbUser     = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password', '');

        if (empty($dbName)) {
            $this->error('Database name tidak dikonfigurasi.');
            return Command::FAILURE;
        }

        $backupDir  = storage_path('app/backups');
        $timestamp  = now()->format('Y-m-d_His');
        $filename   = "backup_{$dbName}_{$timestamp}.sql";
        $filepath   = "{$backupDir}/{$filename}";

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Build mysqldump command (password via env to avoid shell exposure)
        $passwordArg = $dbPassword !== '' ? '-p' . escapeshellarg($dbPassword) : '';
        $cmd = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $passwordArg,
            escapeshellarg($dbName),
            escapeshellarg($filepath)
        );

        $output = [];
        $exitCode = 0;
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->error('Backup gagal. Pastikan mysqldump tersedia dan konfigurasi database benar.');
            return Command::FAILURE;
        }

        $this->info("Backup berhasil: {$filepath}");
        return Command::SUCCESS;
    }
}