<?php

/**
 * HostnameIdentification.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Tenancy;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Events\Hostnames\Identified;
use Hyn\Tenancy\Jobs\HostnameIdentification as BaseHostnameIdentification;
use Illuminate\Http\Request;

/**
 *
 * Description of HostnameIdentification
 *
 */

class HostnameIdentification extends BaseHostnameIdentification
{

    /**
     * @param Request $request
     * @param HostnameRepository $hostnameRepository
     * @return Hostname|null
     */
    public function handle(Request $request, HostnameRepository $hostnameRepository)
    {
        $hostname = config('tenancy.current_hostname');

        if (!$hostname) {
            $hostname = $request->getHost();
        }

        $hostname = $hostnameRepository->findByHostname($hostname);
        if (!$hostname) {
            $hostname = $hostnameRepository->getDefault();
        }

        $this->emitEvent(new Identified($hostname));
        return $hostname;
    }
}
