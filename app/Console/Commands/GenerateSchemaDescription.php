<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class GenerateSchemaDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-schema-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a text description of the database schema for the AI assistant.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $schemaDescription = "";
            
            // استخدام طريقة بديلة للحصول على أسماء الجداول
            $tables = DB::select('SHOW TABLES');
            $tableNames = [];
            
            foreach ($tables as $table) {
                $tableNames[] = array_values((array) $table)[0];
            }

            $this->info("Building schema description...");

            foreach ($tableNames as $tableName) {
                // نتجاهل جداول لارافيل الداخلية
                if (in_array($tableName, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens'])) {
                    continue;
                }

                $columns = Schema::getColumnListing($tableName);
                $schemaDescription .= "Table '{$tableName}' has columns: " . implode(', ', $columns) . ". ";
            }

            $this->info("\nSchema Description Generated Successfully:");
            $this->line("--------------------------------------------------");
            // نطبع الوصف في الطرفية ليسهل نسخه
            $this->line($schemaDescription);
            $this->line("--------------------------------------------------");
            $this->info("\nCopy the text above and use it in your GeminiAssistant service or store it in the cache/config.");

            // يمكنك أيضًا تخزين هذا الوصف في الكاش لسهولة الوصول إليه
            // \Illuminate\Support\Facades\Cache::put('db_schema_description', $schemaDescription, now()->addHours(24));
            // $this->info("Schema has also been saved to cache with key 'db_schema_description'");

        } catch (\Exception $e) {
            $this->error('An error occurred while generating the schema description.');
            $this->error($e->getMessage());
            Log::error($e);
        }
    }
}
