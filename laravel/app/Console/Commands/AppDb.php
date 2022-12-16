<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDb extends App
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database commands';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createModels();
        $this->createControllers();
    }

    public function createModels()
    {
        foreach($this->databaseTables() as $table) {
            if (!$table->Model->Exists) {
                dump("php artisan make:model {$table->Model->Class} --controller --migration");
            }
            // dump($table->Model, $table->Controller);
        }
    }
    
    public function createControllers()
    {
        // foreach($this->databaseTables() as $table) {
        //     dump($table->Controller);
        // }
    }
}
