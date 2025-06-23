<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DatabaseBackupController;

class CreateDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup automatically';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting database backup process...');
        
        try {
            $backupController = new DatabaseBackupController();
            $backupController->create();
            
            $this->info('Database backup created successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred during backup: ' . $e->getMessage());
            return 1;
        }
    }
} 