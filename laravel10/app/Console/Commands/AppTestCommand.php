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
            'random' => rand(0, 9999),
            'uniqid' => uniqid(),
        ];

        // $evt = event(new TestEvent([
        //     'uniqid' => uniqid(),
        // ]));

        $evt['Event'] = event(new \App\Events\Event('test', 'client', $data));

        // $evt['PusherEvent'] = event(new \App\Events\PusherEvent('test', 'client-test', $data));
        // $evt['TestEvent'] = event(new \App\Events\TestEvent());

        // $evt['raw'] = event('test', $data);
        // $evt['raw'] = event('client-test', $data);

        dump($evt);
    }
}
