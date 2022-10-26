<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize application instalation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $app_name = env('APP_NAME');

        $this->info("-------- Installing {$app_name} --------");
        $this->clearCache();
        $this->migrate();
        $this->seed();
        $this->info("-------- Installing {$app_name} --------");
    }

    public function clearCache()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
    }

    public function migrate()
    {
        // $this->call('migrate', ['--force' => true]);
        
        foreach($this->getClasses('/app/Models') as $model) {
            $schema = $model->migrationSchema();
            if (empty($schema['fields'])) continue;

            if (\Schema::hasTable($model->getTable())) {
                $field_name_last = false;
                foreach($schema['fields'] as $field_name => $field_type) {
                    $sql = ["ALTER TABLE {$model->getTable()}"];
                    $sql[] = \Schema::hasColumn($model->getTable(), $field_name)? 'MODIFY COLUMN': 'ADD COLUMN';
                    $sql[] = "`{$field_name}` {$field_type}";
                    if ($field_name_last) $sql[] = "AFTER `{$field_name_last}`";
                    $sql = implode(' ', $sql);

                    $this->info($sql);
                    \DB::statement($sql);

                    $field_name_last = $field_name;
                }
            } else {
                $sql = ["CREATE TABLE `{$model->getTable()}` ("];
                foreach($schema['fields'] as $field_name => $field_type) {
                    $sql[] = "\t`{$field_name}` {$field_type},";
                }
                $sql[] = "\tPRIMARY KEY (`id`) USING BTREE";
                $sql[] = ") COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB";
                $sql = implode("\n", $sql);
                
                $this->info($sql);
                \DB::statement($sql);
            }
        }
    }

    public function seed()
    {
        $this->call('db:seed', ['--force' => true]);
    }

    public function getClasses($folder)
    {
        $path = realpath(base_path(trim($folder, '/')));
        $files = glob("{$path}/*");
        
        return array_filter(array_map(function($file) use($folder) {
            $file = pathinfo($file, PATHINFO_FILENAME);
            $file = '\\'. implode('\\', array_map('ucfirst', array_filter(preg_split('/[^a-zA-Z0-9]/', $folder)))) . "\\{$file}";

            try {
                return app($file);
            }
            catch(\Exception $e) {
                return false;
            }
            return $file;
        }, $files));
    }
}
