<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModelsAndMigrations extends Command
{
    protected $signature = 'make:tailor-migrations';
    protected $description = 'Generate only migrations for Tailor Shop project with 1-minute gap';

    public function handle()
    {
        $tables = [
            'users',
            'measurements',
            'items',
            'item_measurements',
            'item_styles',
            'customers',
            'customer_items',
            'customer_item_measurements',
            'customer_item_styles'
        ];

        $timestamp = Carbon::now();

        foreach ($tables as $table) {
            // Migration name (e.g. create_users_table)
            $migrationName = 'create_' . $table . '_table';

            // Custom timestamp with +1 minute each time
            $fileName = $timestamp->format('Y_m_d_His') . '_' . $migrationName . '.php';

            // Path to migration folder
            $path = database_path('migrations/' . $fileName);

            // Call make:migration to generate stub
            $this->callSilent('make:migration', [
                'name' => $migrationName,
                '--create' => $table
            ]);

            // Find the latest migration Laravel generated (default timestamp)
            $latestFile = collect(glob(database_path('migrations/*.php')))
                ->sortByDesc(fn($file) => filemtime($file))
                ->first();

            if ($latestFile && file_exists($latestFile)) {
                // Rename it to our custom timestamped filename
                rename($latestFile, $path);
                $this->info("Created: {$fileName} ✅");
            }

            // Add 1 minute to timestamp for next migration
            $timestamp->addMinute();
        }

        return Command::SUCCESS;
    }
}
