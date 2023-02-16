<?php

namespace App\Listeners;

use App\Events\AutoTransmitRemovedEvent;
use App\Jobs\UploadAutoTransmitTransmittalJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoTransmitRemovedListener
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AutoTransmitRemovedEvent  $event
     * @return void
     */
    public function handle(AutoTransmitRemovedEvent $event)
    {

    }
}
