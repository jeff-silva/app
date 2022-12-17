<?php

namespace App\Console\Commands;

class AppInstall extends App
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

        $this->infoTitle("Installing {$app_name}");
        $this->clearCache();
        // $this->migrate();
        $this->seed();
        $this->databaseUML();
    }

    public function infoTitle($title)
    {
        $str = '•';
        $this->info('');
        $this->info(implode(' ', [
            str_repeat($str, 10),
            $title,
            str_repeat($str, 60 - strlen($title)),
        ]));
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
        
        $foreign_keys = [];
        foreach($this->getModels() as $model) {
            $schemaFields = $model->getTableFields();
            $schemaFks = $model->getTableFks();
            if (empty($schemaFields)) continue;

            $this->infoTitle("Table: {$model->getTable()}");

            if (\Schema::hasTable($model->getTable())) {
                $field_name_last = false;
                foreach($schemaFields as $field_name => $field_type) {
                    $sql = ["ALTER TABLE {$model->getTable()}"];
                    $sql[] = \Schema::hasColumn($model->getTable(), $field_name)? 'MODIFY COLUMN': 'ADD COLUMN';
                    $sql[] = "`{$field_name}` {$field_type}";
                    if ($field_name_last) $sql[] = "AFTER `{$field_name_last}`";
                    $sql = implode(' ', $sql) .';';

                    $this->info($sql);
                    \DB::statement($sql);

                    $field_name_last = $field_name;
                }
            } else {
                $sql = ["CREATE TABLE `{$model->getTable()}` ("];
                foreach($schemaFields as $field_name => $field_type) {
                    $sql[] = "\t`{$field_name}` {$field_type},";
                }
                $sql[] = "\tPRIMARY KEY (`id`) USING BTREE";
                $sql[] = ") COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB";
                $sql = implode("\n", $sql);
                
                $this->info($sql);
                \DB::statement($sql);
            }

            foreach($schemaFks as $fk_name => $fk) {
                $foreign_keys[ $fk_name ] = (object) [
                    'table' => $model->getTable(),
                    'fk' => $fk,
                ];
            }
        }

        $this->infoTitle('Foreign keys');
        foreach($this->databaseFks() as $fk) {
            $sql = "ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`;";
            \DB::statement($sql);
            $this->info($sql);
        }

        foreach($foreign_keys as $fk_name => $fk) {
            $sql = "ALTER TABLE `{$fk->table}` ADD CONSTRAINT `{$fk_name}` {$fk->fk};";
            \DB::statement($sql);
            $this->info($sql);
        }
    }

    public function seed()
    {
        $this->infoTitle('Seed');
        // $this->call('db:seed', ['--force' => true]);
        $seeds = $this->getModels();

        usort($seeds, function($a, $b) {
            if(!$a->seedAfter || $a->getTable() == $b->seedAfter)
                return -1;
            if(!$b->seedAfter || $b->getTable() == $a->seedAfter)
                return 1;
            return 0;
        });

        foreach($seeds as $model) {
            $model->seed();
            $this->info($model->getTable());
        }
    }

    public $getClasses = [];
    public function getClasses($folder)
    {
        $path = realpath(base_path(trim($folder, '/')));

        if (isset($this->getClasses[ $path ])) {
            return $this->getClasses[ $path ];
        }

        $files = glob("{$path}/*");
        
        return $this->getClasses[ $path ] = array_filter(array_map(function($file) use($folder) {
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

    public function getModels() {
        return array_filter(array_map(function($model) {
            return (in_array(\App\Traits\Model::class, class_uses_recursive(get_class($model))))? $model: false;
        }, $this->getClasses('/app/Models')));
    }
}
