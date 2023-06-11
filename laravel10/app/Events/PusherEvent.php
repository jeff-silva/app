<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Models\General\Property\Properties;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channels = false;
    public $event = false;
    public $data = [];
    public $private = false;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channels, $event, $data, $private=false)
    {
      $this->channels = $channels;
      $this->event = $event;
      $this->data = $data;
      $this->private = $private;
    }

    public function broadcastOn()
    {
      return $this->private ?
        new PrivateChannel($this->channels):
        new Channel($this->channels);
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
