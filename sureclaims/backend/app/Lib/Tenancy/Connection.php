<?php

/**
 * Connection.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Tenancy;

use Hyn\Tenancy\Contracts\Website;
use Hyn\Tenancy\Database\Connection as BaseConnection;
use Hyn\Tenancy\Events\Database\ConfigurationLoaded;
use Hyn\Tenancy\Events\Database\ConfigurationLoading;
use Hyn\Tenancy\Exceptions\ConnectionException;

/**
 *
 * Description of Connection
 *
 */

class Connection extends BaseConnection
{

    /**
     * @param Website $website
     * @return array
     * @throws ConnectionException
     */
    public function generateConfigurationArray(Website $website): array
    {
        $clone = config(sprintf(
            'database.connections.%s',
            $this->systemName()
        ));

        $mode = config('tenancy.db.tenant-division-mode');

        $this->emitEvent(new ConfigurationLoading($mode, $clone, $this));

        switch ($mode) {
            case static::DIVISION_MODE_SEPARATE_DATABASE:
                $clone['database'] = $website->uuid;
                // $clone['username'] = $clone['database'] = $website->uuid;
                // $clone['username'] = str_before($website->uuid, '-');
                $clone['username'] = $this->factoryUsername($website->uuid);
                $clone['password'] = $this->passwordGenerator->generate($website);
                break;
            case static::DIVISION_MODE_SEPARATE_PREFIX:
                $clone['prefix'] = sprintf('%d_', $website->id);
                break;
            case static::DIVISION_MODE_BYPASS:
                break;
            default:
                throw new ConnectionException("Division mode '$mode' unknown.");
        }

        $this->emitEvent(new ConfigurationLoaded($clone, $this));

        return $clone;
    }

    private function factoryUsername($websiteUUID) 
    {
        $name = preg_replace('/(-\w+-\w+)$/', "", $websiteUUID);
        if (strpos($name, '-localhost') !== false) {
            $name = str_replace('-localhost', '', $name);
        }
        return $name;
    }
}
