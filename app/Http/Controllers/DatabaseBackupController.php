<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Exception;
use Carbon\Carbon;

class DatabaseBackupController extends Controller
{
    /**
     * Show the database backup page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $backups = $this->getBackups();
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Create a new database backup
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            // Get database configuration
            $dbHost = Config::get('database.connections.mysql.host');
            $dbUsername = Config::get('database.connections.mysql.username');
            $dbPassword = Config::get('database.connections.mysql.password');
            $dbName = Config::get('database.connections.mysql.database');
            $dbPort = Config::get('database.connections.mysql.port', 3306);

            // Generate backup filename with the requested format "hrisBackUp-mm-dd-yy-time"
            $now = Carbon::now();
            $dateFormat = $now->format('m-d-y');
            $timeFormat = $now->format('H-i-s');
            $filename = "hrisBackUp-{$dateFormat}-{$timeFormat}.sql";
            $storagePath = "backups/{$filename}";
            $fullBackupPath = storage_path("app/{$storagePath}");

            // Ensure backup directory exists
            if (!file_exists(dirname($fullBackupPath))) {
                mkdir(dirname($fullBackupPath), 0755, true);
            }

            // First try with mysqldump (preferred method)
            try {
                // Build the mysqldump command
                $command = [
                    'mysqldump',
                    "--host={$dbHost}",
                    "--port={$dbPort}",
                    "--user={$dbUsername}",
                    "--password={$dbPassword}",
                    $dbName,
                    '--single-transaction',
                    '--quick',
                    '--lock-tables=false',
                ];

                // Create the process
                $process = new Process($command);
                $process->setTimeout(300); // 5 minutes timeout

                // Run the process and save the output to the backup file
                $process->run(function ($type, $buffer) use ($fullBackupPath) {
                    if ($type === Process::ERR) {
                        throw new ProcessFailedException(new Process(['error' => $buffer]));
                    } else {
                        file_put_contents($fullBackupPath, $buffer, FILE_APPEND);
                    }
                });

                if (!$process->isSuccessful()) {
                    throw new Exception('mysqldump failed: ' . $process->getErrorOutput());
                }
            } 
            // If mysqldump fails, fall back to PHP-based backup method
            catch (Exception $e) {
                $this->backupUsingPdo($fullBackupPath);
            }

            return redirect()->route('database.backups')
                ->with('success', 'Database backup created successfully.');
                
        } catch (Exception $e) {
            return redirect()->route('database.backups')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Create a database backup using PDO when mysqldump isn't available
     *
     * @param string $fullBackupPath Path to save the backup
     * @return void
     * @throws Exception
     */
    private function backupUsingPdo($fullBackupPath)
    {
        try {
            // Get database configuration
            $dbHost = Config::get('database.connections.mysql.host');
            $dbUsername = Config::get('database.connections.mysql.username');
            $dbPassword = Config::get('database.connections.mysql.password');
            $dbName = Config::get('database.connections.mysql.database');
            $dbPort = Config::get('database.connections.mysql.port', 3306);

            // Connect to the database
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8";
            $pdo = new \PDO($dsn, $dbUsername, $dbPassword, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
            ]);

            // Start the backup file with header comments
            $output = "-- Database Backup for {$dbName}\n";
            $output .= "-- Generated on " . date('Y-m-d H:i:s') . "\n";
            $output .= "-- Using PHP PDO Backup Method\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            file_put_contents($fullBackupPath, $output);

            // Get all tables
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
            
            foreach ($tables as $table) {
                // Get the create table statement
                $tableStructure = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
                $createTable = $tableStructure['Create Table'] ?? $tableStructure['Create View'] ?? null;
                
                if (!$createTable) {
                    continue;
                }
                
                $output = "\n-- Table structure for table `{$table}`\n\n";
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $output .= $createTable . ";\n\n";
                
                file_put_contents($fullBackupPath, $output, FILE_APPEND);
                
                // Skip data for certain tables that might be large
                $skipDataTables = ['jobs', 'failed_jobs', 'logs', 'activity_log', 'sessions']; 
                if (in_array($table, $skipDataTables)) {
                    $output = "-- Data for table `{$table}` has been skipped\n\n";
                    file_put_contents($fullBackupPath, $output, FILE_APPEND);
                    continue;
                }
                
                // Get table data
                $rows = $pdo->query("SELECT * FROM `{$table}`");
                $columnCount = $rows->columnCount();
                
                if ($columnCount > 0) {
                    $output = "-- Data for table `{$table}`\n";
                    file_put_contents($fullBackupPath, $output, FILE_APPEND);
                    
                    $insertCounter = 0;
                    $insertHeader = null;
                    $insertValues = [];
                    
                    while ($row = $rows->fetch(\PDO::FETCH_NUM)) {
                        // Create column header only once
                        if ($insertHeader === null) {
                            $insertHeader = "INSERT INTO `{$table}` VALUES";
                        }
                        
                        // Format each row's values
                        $rowValues = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $rowValues[] = 'NULL';
                            } elseif (is_numeric($value)) {
                                $rowValues[] = $value;
                            } else {
                                $rowValues[] = $pdo->quote($value);
                            }
                        }
                        
                        $insertValues[] = '(' . implode(',', $rowValues) . ')';
                        $insertCounter++;
                        
                        // Write in chunks of 100 to avoid memory issues
                        if ($insertCounter >= 100) {
                            if ($insertHeader && !empty($insertValues)) {
                                $output = $insertHeader . "\n" . implode(",\n", $insertValues) . ";\n";
                                file_put_contents($fullBackupPath, $output, FILE_APPEND);
                            }
                            $insertCounter = 0;
                            $insertValues = [];
                        }
                    }
                    
                    // Insert any remaining rows
                    if ($insertHeader && !empty($insertValues)) {
                        $output = $insertHeader . "\n" . implode(",\n", $insertValues) . ";\n\n";
                        file_put_contents($fullBackupPath, $output, FILE_APPEND);
                    } else {
                        $output = "\n";
                        file_put_contents($fullBackupPath, $output, FILE_APPEND);
                    }
                }
            }
            
            // End the backup file
            $output = "\nSET FOREIGN_KEY_CHECKS=1;\n";
            file_put_contents($fullBackupPath, $output, FILE_APPEND);
            
        } catch (Exception $e) {
            throw new Exception('PDO backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a specific backup file
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        try {
            // Validate the filename
            if (!preg_match('/^[\w\-\.]+\.sql$/', $filename)) {
                return redirect()->route('database.backups')
                    ->with('error', 'Invalid backup filename.');
            }

            $path = storage_path("app/backups/{$filename}");

            // Check if file exists
            if (!file_exists($path)) {
                return redirect()->route('database.backups')
                    ->with('error', 'Backup file not found.');
            }

            // Return the file as a download
            return Response::download($path, $filename, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename=' . $filename,
            ]);
        } catch (Exception $e) {
            return redirect()->route('database.backups')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific backup file
     *
     * @param string $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($filename)
    {
        try {
            // Validate the filename to prevent path traversal attacks
            if (!preg_match('/^[\w\-\.]+\.sql$/', $filename)) {
                return redirect()->route('database.backups')
                    ->with('error', 'Invalid backup filename.');
            }

            $path = "backups/{$filename}";
            
            if (Storage::exists($path)) {
                Storage::delete($path);
                return redirect()->route('database.backups')
                    ->with('success', 'Backup deleted successfully.');
            } else {
                return redirect()->route('database.backups')
                    ->with('error', 'Backup file not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('database.backups')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Get all available backup files
     *
     * @return array
     */
    private function getBackups()
    {
        $backups = [];
        
        if (Storage::exists('backups')) {
            $files = Storage::files('backups');
            
            foreach ($files as $file) {
                if (Str::endsWith($file, '.sql')) {
                    $filename = basename($file);
                    $size = Storage::size($file);
                    $lastModified = Storage::lastModified($file);
                    
                    $backups[] = [
                        'filename' => $filename,
                        'size' => $this->formatFileSize($size),
                        'date' => Carbon::createFromTimestamp($lastModified)->format('Y-m-d H:i:s'),
                        'age' => Carbon::createFromTimestamp($lastModified)->diffForHumans(),
                    ];
                }
            }
            
            // Sort backups by date (newest first)
            usort($backups, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        return $backups;
    }

    /**
     * Format file size to human-readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Static version of formatFileSize for use in views
     *
     * @param int $bytes
     * @return string
     */
    public static function formatFileSizeStatic($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
