<?php

namespace App\Console\Commands;

use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Hyn\Tenancy\Events\Hostnames\Identified;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Traits\AddWebsiteFilterOnCommand;
use Hyn\Tenancy\Traits\DispatchesEvents;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CleanupCommand extends Command
{
    use AddWebsiteFilterOnCommand, DispatchesEvents;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<SIGNATURE
sureclaims:cleanup
{--|website_id= : ID number of the website }
SIGNATURE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up temporary files on the tenant subsystems';

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var WebsiteRepository
     */
    private $websites;
    /**
     * @var SupportingDocumentRepository
     */
    private $documents;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        Connection $connection,
        WebsiteRepository $websites,
        SupportingDocumentRepository $documents
    ) {
        parent::__construct();
        $this->connection = $connection;
        $this->websites =  $websites;
        $this->documents = $documents;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->processHandle(function (Website $website) {
            $this->connection->set($website);

            /**
             * @var Collection $hostnames
             * @var Hostname $hostname
             */
            $hostnames = $website->hostnames;
            $hostname = $hostnames->first();
            if (!$hostname) {
                $this->connection->purge();
                return;
            }

            $this->emitEvent(new Identified($hostname));
            $this->info("Cleaning up `{$hostname->fqdn}`...");

            $this->documents->skipPresenter();
            $this->documents->resetScope();
            $this->documents->scopeQuery(function ($query) {
                /** @var Builder $query */
                $query = $query->whereNull('claim_id');
                return $query;
            });

            $diskName = 'tenant';
            $uploadDir = '/supporting-documents/';
            $disk = \Storage::disk($diskName);

            $count = 0;
            $this->documents->all()->chunk(10)->each(function ($chunk) use ($disk, $uploadDir, &$count) {
                foreach ($chunk as $document) {
                    /** @var SupportingDocument $document */
                    if ($disk->delete($uploadDir . $document->storage_uid)) {
                        // Delete raw files as well
                        $disk->delete($uploadDir . $document->storage_uid.'.raw');
                        $document->delete();
                        $count++;
                    }
                }
            });

            $this->info("{$count} supporting document uploads cleaned up!");
            $this->connection->purge();
        });
    }
}
