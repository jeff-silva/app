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

        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');

        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
    }
}
