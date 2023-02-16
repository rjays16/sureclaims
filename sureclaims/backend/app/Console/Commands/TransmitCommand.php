<?php

namespace App\Console\Commands;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\Claim;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\TransmittalRepository;
use App\Model\Transformers\TransmittalXmlTransformer;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Contracts\Website;
use Hyn\Tenancy\Database\Connection;
use Hyn\Tenancy\Traits\AddWebsiteFilterOnCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class TransmitCommand extends Command
{
    use AddWebsiteFilterOnCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<SIGNATURE
sureclaims:transmit
{--|website_id=* : ID number of the website }
SIGNATURE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploads all pending transmittals';

    /**
     * @var WebsiteRepository
     */
    private $websites;
    /**
     * @var TransmittalRepository
     */
    private $transmittals;
    /**
     * @var ClaimRepository
     */
    private $claims;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var EclaimsServiceInterface
     */
    private $service;
    /**
     * @var TransmittalXmlTransformer
     */
    private $transformer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        WebsiteRepository $websites,
        TransmittalRepository $transmittals,
        ClaimRepository $claims,
        Connection $connection,
        EclaimsServiceInterface $service
    ) {
        parent::__construct();

        $this->websites = $websites;
        $this->transmittals = $transmittals;
        $this->claims = $claims;
        $this->connection = $connection;
        $this->service = $service;
        $this->transformer = new TransmittalXmlTransformer();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (env('APP_DEBUG', true)) {
            echo "Uploading is disbled during testing.\n";
            return;
        }
        $this->processHandle(function (Website $website) {
            $this->connection->set($website);

            $this->transmittals->skipPresenter(true);
            $transmittals = $this->transmittals->autoTransmittables();
            $transmittals->chunk(5)->each(function(Collection $chunk) use ($website) {
                foreach ($chunk as $transmittal) {

                    // $this->connection->get()->beginTransaction();
                    /** @var Transmittal $transmittal */
                    if ($transmittal->status === Transmittal::STATUS_UPLOADED) {
                        $this->mapTransmittal($transmittal);
                    } else {
                        $this->uploadTransmittal($transmittal);
                    }
                }
            });

            $this->connection->purge();
        });
    }

    /**
     * @param Transmittal $transmittal
     *
     * @throws \Exception
     */
    private function mapTransmittal(Transmittal $transmittal) : void
    {
        $this->claims->skipPresenter();
        $this->info("Mapping Transmittal # {$transmittal->transmittal_no} ...");

        try {
            $result = $this->service->getUploadedClaimsMap($transmittal->receipt_ticket_no);
            $mapping = array_get($result, 'eCONFIRMATION.MAPPING', []);
            foreach ($mapping as $map) {
                $mapData = array_get($map, '@attributes', []);
                if (@$mapData['pClaimNumber']) {
                    /** @var Claim $claim */
                    $claim = $this->claims->findByField('claim_no', $mapData['pClaimNumber']);
                    $claim->lhio_series_no = @$mapData['pClaimSeriesLhio'];
                    $claim->save();
                }
            }

            $transmittal->status = Transmittal::STATUS_MAPPED;
            $transmittal->save();

            $this->info("Transmittal successfully mapped!");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

//    /**

    /**
     * @param Transmittal $transmittal
     *
     * @throws \Exception
     */
    private function uploadTransmittal(Transmittal $transmittal) : void
    {
        try {
            $xml = $this->transformer->xmlize($transmittal);
            $this->info("Uploading Transmittal # {$transmittal->transmittal_no} ...");
            $result = $this->service->eClaimsUpload($xml);

            $receiptTicketNumber = array_get($result, 'eRECEIPT.@attributes.pReceiptTicketNumber');
            if ($receiptTicketNumber) {
                // Successful upload
                $transmittal->receipt_ticket_no = $receiptTicketNumber;
                $transmittal->transmit_date = ec_from_date(
                    array_get($result, 'eRECEIPT.@attributes.pReceiptTicketNumber')
                );
                $transmittal->transmit_time = ec_from_time(
                    array_get($result, 'eRECEIPT.@attributes.pReceiptTicketNumber')
                );
                $transmittal->transmittal_control_code = array_get($result, 'eRECEIPT.@attributes.pTransmissionControlNumber');
                $transmittal->status = Transmittal::STATUS_UPLOADED;
                $transmittal->save();

                $this->info('Transmittal successfully uploaded!');
            } else {
                // Unsuccessful upload
                throw new \Exception('Unsuccessful upload');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            // $this->connection->get()->rollBack();

            /*
            $collectErrors = [];
            $transmitErrors = array_get($result, 'eRECEIPT.REMARKS');
            foreach ($transmitErrors as $transmitError) {
                $collectErrors[] = [
                    'code' => array_get($transmitError, '@attributes.pErrCode'),
                    'description' => array_get($transmitError, '@attributes.pErrDescription'),
                ];
            }
            $transmittal->transmit_errors = json_encode($collectErrors);
            */
            $transmittal->transmit_errors = $e->getMessage();
            $transmittal->save();
        }
        // $this->connection->get()->commit();
    }
//     * @param string $level
//     * @param string $message
//     * @param array|null $context
//     */
//    private function log(string $level, string $message, ?array $context) : void
//    {
//        $this->$level($message);
//        \Log::$level($message, $context);
//    }
}
