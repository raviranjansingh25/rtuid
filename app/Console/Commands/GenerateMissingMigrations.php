<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class GenerateMissingMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:missing-migrations';  // Set your custom command name

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration files for tables without migrations.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("🔍 Scanning tables...");

        // 1. Get all DB tables
        $allTables = collect(DB::select('SHOW TABLES'))->map(function ($table) {
            return array_values((array) $table)[0];
        })->toArray();

        // 2. Get existing migration table names
        $migrationFiles = File::allFiles(database_path('migrations'));
        $migratedTables = [];

        foreach ($migrationFiles as $file) {
            $content = file_get_contents($file);
            if (preg_match("/Schema::create\(['\"](.+?)['\"]/", $content, $matches)) {
                $migratedTables[] = $matches[1];
            }
        }

        // 3. Get tables without migration
        $tablesToGenerate = array_diff($allTables, $migratedTables);

        if (empty($tablesToGenerate)) {
            $this->info("✅ All tables already have migrations.");
            return 0; // Exit with success status
        }

        $this->info("🧱 Generating migration for: " . implode(', ', $tablesToGenerate));

        // 4. Call migrate:generate for each table (this will include field types)
        foreach ($tablesToGenerate as $table) {
            if($table != 'migrations'){
                $this->info("Generating migration for table: {$table}");

                // Generate migration for each table
                $status = Artisan::call('migrate:generate', [
                    '--tables' => 'wishlists',
                    '--no-interaction' => true,
                ]);
                
            }
        }

        $this->info("✅ Migration files generated successfully!");
        return 0; // Exit with success status
    }
}
