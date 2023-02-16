<?php

/**
 * Lookups.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib;

use App\Lib\Contracts\LookupsInterface;
use App\Model\Entities\GlobalLookup;
use App\Model\Repositories\Contracts\GlobalLookupRepository;

/**
 *
 * Description of Lookups
 *
 */

class Lookups implements LookupsInterface
{

    /** @var GlobalLookupRepository  */
    private $globalLookups;

    /**
     * Lookups constructor.
     *
     * @param GlobalLookupRepository $globalLookups
     */
    public function __construct(GlobalLookupRepository $globalLookups)
    {
        $this->globalLookups = $globalLookups;
    }

    /**
     * @param string $domain
     * @param string $column
     *
     * @return array
     */
    public function types($domain = null)
    {
        $this->globalLookups->resetScope();
        $criteria = [];
        if ($domain) {
            $criteria['domain_name'] = $domain;
        }
        $result = $this->globalLookups
            ->orderBy('domain_name', 'asc')
            ->orderBy('lookup_value', 'asc')
            ->findWhere($criteria);

        return @$result['data'];
    }

    /**
     * @param string $domain
     * @param string $type
     *
     * @return array
     */
    public function value($domain = null, $type = null)
    {
        $this->globalLookups->resetScope();
        $result = $this->globalLookups
            ->scopeQuery(function($query) use ($domain, $type) {
                /** @var GlobalLookup $query */
                return $query->where([
                    'domain_name' => $domain,
                    'lookup_type' => $type,
                ]);
            })->first();

        return $result['value'];
    }
}
