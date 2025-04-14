<?php

namespace Avcodewizard\LaravelBackup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupCommand extends Command
{
    protected $signature = 'backup:run';
    protected $description = 'Run database and storage backup, deleting old backups';

    public function handle()
    {
        $backupPath = config('laravelBackup.backup_path');
        $keepDays = config('laravelBackup.keep_days');

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $fileName = $backupPath . '/' . date('Y-m-d-H-i-s');

        $dbConnection = config('database.default');
        switch ($dbConnection) {
            case 'mysql': // MySQL backup
                $this->backupMysql($fileName);
                break;

            case 'pgsql': //PostgreSQL backup
                $this->backupPgsql($fileName);
                break;

            case 'sqlite': // SQLite backup
                $this->sqliteBackup($fileName);
                break;

            default:
                $this->error("Database connection [$dbConnection] not supported for backup.");
                break;
        }

        // Storage Backup
        if (config('laravelBackup.backup_storage_directory')) {
            $storageZip = $fileName . '_storage.zip';
            if (File::exists(public_path('storage'))) {
                exec("zip -r {$storageZip} " . 'public/storage');
            } else {
                exec("zip -r {$storageZip} " . 'storage/app/public');
            }
            $this->info("Storage backup completed: {$storageZip}");
        }

        $this->deleteOldBackups($backupPath, $keepDays);
    }

    private function backupMysql($fileName)
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $backupFile = $fileName . '_database.sql.gz';
        $command = "mysqldump -h $dbHost -u $dbUser --password=$dbPass $dbName | gzip > $backupFile";
        exec($command);

        $this->info("Database backup completed: {$backupFile}");
    }

    private function backupPgsql($fileName)
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT', 5432);

        $backupFile = $fileName . '_database.sql.gz';

        $command = "PGPASSWORD=\"$dbPass\" pg_dump -h $dbHost -p $dbPort -U $dbUser $dbName | gzip > $backupFile";
        putenv("PGPASSWORD=$dbPass"); // Alternative way

        exec($command);
        $this->info("PostgreSQL backup completed: {$backupFile}");
    }

    private function sqliteBackup($fileName)
    {
        $dbPath = database_path('database.sqlite');
        $backupFile = $fileName . '_database.sqlite.bak';
    
        copy($dbPath, $backupFile);
        $this->info("SQLite backup completed: {$backupFile}");
    }

    private function deleteOldBackups($backupPath, $days)
    {
        $files = glob($backupPath . '/*');
        $now = time();

        foreach ($files as $file) {
            // if (is_file($file) && $now - filemtime($file) >= 10) {
            if (is_file($file) && $now - filemtime($file) >= $days * 86400) {
                unlink($file);
                $this->info("Deleted old backup: {$file}");
            }
        }
    }
}
