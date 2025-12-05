<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MobileNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $userId;
    public $payload;

    public function __construct($userId, $payload) {
        $this->userId  = $userId;
        $this->payload = $payload;
    }

    public function broadcastOn() {
        return new PrivateChannel('mobile.user.' . $this->userId);
    }

    public function broadcastAs() {
        return 'mobile-notify';
    }

    public function broadcastWith() {
        return $this->payload;
    }
}
