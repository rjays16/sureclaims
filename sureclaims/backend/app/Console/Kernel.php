<?php

namespace App\Console;

use Carbon\Carbon;
use Hyn\Tenancy\Models\Website;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule( Schedule $schedule )
    {
        if (\App::runningUnitTests()) {
            return;
        }

//        $websites = \Cache::rememberForever( config( 'sureclaims.cachekeys.websites' ), function () {
//            return Website::all()->toArray();
//        } );

//        foreach ( $websites as $website ) {
//            $schedule->command( 'sureclaims:transmit', [ '--website_id' => $website[ 'id' ] ] )
//                ->hourly();
////                ->everyMinute(); // test
//        }

        $schedule->command('queue:retry', ['all'])
//            ->hourlyAt(60);
            ->everyMinute(); // test
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load( __DIR__ . '/Commands' );

        require base_path( 'routes/console.php' );
    }
}
