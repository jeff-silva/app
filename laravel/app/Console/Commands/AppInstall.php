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

        // $this->info("-------- Installing {$app_name} --------");
        // $this->clearCache();
        $this->migrate();
        // $this->seed();
        // $this->info("-------- Installing {$app_name} --------");
    }

    public function clearCache()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
    }

    public function migrate()
    {
        $this->call('migrate', ['--force' => true]);

        foreach($this->getClasses('/app/models') as $model) {
            if (! \Schema::hasTable($model->getTable())) {
                \Schema::create($model->getTable(), function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->timestamps();
                });
            }

            \Schema::table($model->getTable(), function($table) use($model) {
                $columns = \Schema::getColumnListing($model->getTable());
                $model->onMigrate($table, $columns);
            });
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

        return array_map(function($file) {
            $file = str_replace(base_path(), '', $file);
            $file = str_replace('.php', '', $file);
            $file = array_filter(explode(DIRECTORY_SEPARATOR, $file));
            $file = '\\'. implode('\\', array_map('ucfirst', $file));

            try {
                return app($file);
            }
            catch(\Exception $e) {
                return false;
            }
            return $file;
        }, $files);
    }
}
