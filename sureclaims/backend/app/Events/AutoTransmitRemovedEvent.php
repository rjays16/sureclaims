<?php

namespace App\Events;

use App\Model\Entities\Transmittal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AutoTransmitRemovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Transmittal
     */
    public $transmittal;

    /**
     * Create a new event instance.
     *
     * @param Transmittal $transmittal
     */
    public function __construct(Transmittal $transmittal)
    {
        //
        $this->transmittal = $transmittal;
    }
}
