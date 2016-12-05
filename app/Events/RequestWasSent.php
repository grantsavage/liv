<?php

namespace App\Events;

use App\Post;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RequestWasSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $requester;

    public $receiver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $requester, User $receiver)
    {
        $this->requester = $requester;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('App.User.'. $this->receiver->id)
            ];
    }

    public function broadcastWith() {
        return ['user'=> $this->requester];
    }
}
