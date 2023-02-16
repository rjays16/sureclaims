<?php

namespace App\Listeners;

use App\Events\AutoTransmitAddedEvent;
use App\Jobs\UploadAutoTransmitTransmittalJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoTransmitAddedListener
{

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
     * @param  AutoTransmitAddedEvent $event
     * @return void
     */
    public function handle( AutoTransmitAddedEvent $event )
    {
        if (config('app.debug')) {
            UploadAutoTransmitTransmittalJob::dispatch( $event->transmittal )
                ->onConnection( 'database' )
                ->onQueue( 'upload_transmittals' );
        } else {
            UploadAutoTransmitTransmittalJob::dispatch( $event->transmittal )
                ->delay( now()->addSecond( 30 ) )// comment on test
                ->onConnection( 'database' )
                ->onQueue( 'upload_transmittals' );
        }
    }
}
