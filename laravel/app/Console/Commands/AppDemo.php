<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDemo extends AppInstall
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make demo data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creating demo data');

        foreach($this->getClasses('/app/Models') as $model) {
            $model->demo();
        }
    }
}
