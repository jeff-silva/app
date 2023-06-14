<?php

namespace App\Console\Commands;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Illuminate\Console\Command;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class RatchetServe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ratchet:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve ratchet websocket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server = IoServer::factory(new HttpServer(new WsServer($this->controller())), 6001);
        $server->run();
    }

    public function controller()
    {
        return new class implements MessageComponentInterface
        {
            protected $clients = [];

            public function onOpen(ConnectionInterface $conn)
            {
                $this->clients[ $conn->resourceId ] = $conn;
                $conn->send(json_encode([ 'resourceId' => $conn->resourceId ]));
                echo "\nonOpen";
            }
        
            public function onMessage(ConnectionInterface $from, $data)
            {
                $data = json_decode($data, true);
                foreach ($this->clients as $client) {
                    $data['from'] = $client->resourceId;
                    $client->send(json_encode($data));
                }

                echo "\nonMessage";
                echo "\nfrom #{$from->resourceId}";
            }
        
            public function onClose(ConnectionInterface $conn)
            {
                unset($this->clients[ $conn->resourceId ]);
                echo "\nonClose";
            }
        
            public function onError(ConnectionInterface $conn, \Exception $e)
            {
                echo "\nonError";
            }
        };
    }
}
