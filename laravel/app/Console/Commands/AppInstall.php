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
        $this->info('-------- App Instalation');
        $this->call('migrate');
        $this->call('db:seed');
    }
}
