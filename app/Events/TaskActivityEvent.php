<?php

namespace App\Events;

use App\Models\task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;
    public $oldvalue;
    public $newvalue;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct(task $task, string $action,array $oldvalue=[],array $newvalue=[])
    {
        $this->task=$task;
        $this->oldvalue=$oldvalue;
        $this->newvalue=$newvalue;
        $this->action=$action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
