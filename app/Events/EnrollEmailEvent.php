<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trainee;
    public function __construct($trainee)
    {
        $this->trainee = $trainee;
    }
}
