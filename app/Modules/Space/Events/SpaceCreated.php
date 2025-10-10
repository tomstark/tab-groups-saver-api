<?php

declare(strict_types=1);

namespace App\Modules\Space\Events;

use App\Modules\Space\Models\Space;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SpaceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Space $space
    ) {
        //
    }

    // ToDo
    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return array<int, Channel>
    //  */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
