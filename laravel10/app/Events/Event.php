<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Event implements ShouldBroadcast
{
    use Dispatchable;

    public $channel = false;
    public $event = false;
    public $data = [];
    public $private = false;

    /**
     * Create a new event instance.
     */
    public function __construct($channel, $event, $data, $private=false)
    {
        $this->channel = $channel;
        $this->event = $event;
        $this->data = $data;
        $this->private = $private;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return $this->private ?
            new PrivateChannel($this->channel):
            new Channel($this->channel);
    }
    
    public function broadcastAs()
    {
        return $this->event;
    }

    public function broadcastWith()
    {
      return $this->data;
    }
}
