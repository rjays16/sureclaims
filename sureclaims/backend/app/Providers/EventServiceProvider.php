<?php

namespace App\Providers;

use App\Events\AutoTransmitAddedEvent;
use App\Events\AutoTransmitRemovedEvent;
use App\Listeners\AutoTransmitAddedListener;
use App\Listeners\AutoTransmitRemovedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AutoTransmitAddedEvent::class => [
            AutoTransmitAddedListener::class,
        ],
        AutoTransmitRemovedEvent::class => [
            AutoTransmitRemovedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
