<?php
 
namespace App\Console\Commands;
 
use App\Models\AppMail;
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
        // $this->call('config:clear');
        // $this->call('route:clear');
        // $this->call('migrate');
        // $this->call('db:seed');

        $this->registerSettings();
        $this->registerEmailTemplates();

        $this->comment('app:install finish');
    }

    public function registerSettings()
    {
        // 
    }

    public function registerEmailTemplates()
    {
        AppMail::send('jeff@grr.la', 'app-user-welcome', [
            // 'user' => 123,
            'user' => \App\Models\AppUser::query()->find(1),
        ]);
        return AppMail::registerTemplates();
    }
}