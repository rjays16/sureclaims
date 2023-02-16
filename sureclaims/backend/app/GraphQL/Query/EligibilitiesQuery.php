<?php

/**
 * ELigibilitiesQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Repositories\Contracts\EligibilityRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * Description of ELigibilitiesQuery
 *
 */
class EligibilitiesQuery extends Query
{
    use ResolvesQueryFields;

    /** @var EligibilityRepository */
    protected $eligibilities;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'eligibilities'
    ];

    /**
     * ELigibilitiesQuery constructor.
     *
     * @param array $attributes
     * @param EligibilityRepository $eligibilities
     */
    public function __construct($attributes = [], EligibilityRepository $eligibilities)
    {
        $this->eligibilities = $eligibilities;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('Eligibility'));
    }

    /**
     *
     */
    public function args()
    {
        return [
            'patient' => ['name' => 'patient', 'type' => Type::nonNull(Type::id())],
        ];
    }


    /**
     * @param $root
     * @param array $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->eligibilities->resetScope();
        $this->eligibilities
            ->orderBy('checked_at', 'desc');

        if (isset($args['patient'])) {
            $this->eligibilities->scopeQuery(function ($query) use ($args) {
                /** @var Model|Builder $query  */
                $query = $query->where('patient_id', '=', $args['patient']);
                $query = $query->where('is_ok', '=', true);
                $query = $query->take(10);
                $query = $query->orderBy('checked_at', 'desc');
                return $query;
            });
        }

        $result = $this->eligibilities->all();
        return @$result['eligibilities'];
    }
}
