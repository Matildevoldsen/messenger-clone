<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Chat $chat, public User $user)
    {}

    public function broadcastOn(): Channel
    {
        return new Channel('private-App.Models.User.' . $this->user->id);
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => new \App\Http\Resources\ChatResource($this->chat),
        ];
    }
}
