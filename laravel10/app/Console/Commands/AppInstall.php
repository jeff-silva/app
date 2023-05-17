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
    protected $description = 'Install app data';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment('app:install');
    }
}