<?php

/**
 * LookupTypesQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\Lib\Contracts\LookupsInterface;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class LookupTypesQuery extends Query
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'lookupTypes',
    ];

    /** @var LookupsInterface */
    private $lookups;

    /**
     * LookupTypesQuery constructor.
     *
     * @param array $attributes
     * @param LookupsInterface $lookups
     */
    public function __construct($attributes = [], LookupsInterface $lookups)
    {
        $this->lookups = $lookups;
        parent::__construct($attributes);
    }

    /**
     * @return Type
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('LookupType'));
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'domain' => ['name' => 'domain', 'type' => Type::string()],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return array
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $result = $this->lookups->types(@$args['domain']);
        return $result;
    }
}
