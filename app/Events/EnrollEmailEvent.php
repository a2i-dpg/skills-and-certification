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

    public $email_data;
    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }
}
