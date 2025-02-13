<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message; // the message instance

    public function __construct(Message $message)
    {
         $this->message = $message;
    }

    // The channel on which the event will be broadcast
    public function broadcastOn()
    {
         return new Channel('chat');
    }

    // The data to broadcast
    public function broadcastWith()
    {
         return [
              'user_id'    => $this->message->user_id,
              'username'   => $this->message->username,
              'message'    => $this->message->message,
              'created_at' => $this->message->created_at->format('H:i'),
         ];
    }
}
