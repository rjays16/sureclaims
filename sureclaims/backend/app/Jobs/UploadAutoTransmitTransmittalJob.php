<?php

namespace App\Jobs;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\Transmittal;
use App\Processesors\UploadTransmittalProcessor;
use Exception;
use Hyn\Tenancy\Queue\TenantAwareJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadAutoTransmitTransmittalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * @var int
     */
    public $timeout = 120;

    /**
     * @var Transmittal
     */
    private $transmittal;

    /**
     * Create a new job instance.
     *
     * @param Transmittal $transmittal
     */
    public function __construct( Transmittal $transmittal )
    {
        //
        $this->transmittal = $transmittal;
    }

    /**
     * @return static
     */
    public function retryUntil()
    {
        return now()->addHour( 1 );
    }

    /**
     * Execute the job.
     *
     * @param EclaimsServiceInterface $service
     * @param UploadTransmittalProcessor $processor
     * @return void
     */
    public function handle( UploadTransmittalProcessor $processor )
    {
        if ( !$this->transmittal->auto_transmit ) {
            $this->delete();
            return;
        }

        if ( config( 'app.debug' ) ) {
           return;
        }

        $processor->upload( $this->transmittal );
    }
}
