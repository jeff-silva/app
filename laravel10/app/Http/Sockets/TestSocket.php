<?php

namespace App\Http\Sockets;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class TestSocket implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
      dump('onOpen', $connection);
        // TODO: Implement onOpen() method.
    }
    
    public function onClose(ConnectionInterface $connection)
    {
      dump('onClose', $connection);
        // TODO: Implement onClose() method.
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
      dump('onError', $connection);
        // TODO: Implement onError() method.
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
      dump('onMessage', $connection);
        // TODO: Implement onMessage() method.
    }
}