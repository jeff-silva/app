<?php

namespace App\Console\Commands;

use App\Events\TestEvent;
use Illuminate\Console\Command;

class AppTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'uniqid' => uniqid(),
        ];

        // $evt = event(new TestEvent([
        //     'uniqid' => uniqid(),
        // ]));

        $evt = event(new \App\Events\Event('test', 'init', $data));
        $evt = event(new \App\Events\PusherEvent('test', 'init', $data));
        $evt = event(new \App\Events\TestEvent());

        dump($evt);
    }
}
