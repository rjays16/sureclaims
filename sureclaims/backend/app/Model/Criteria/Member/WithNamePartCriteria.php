<?php

namespace App\Model\Criteria\Member;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithNamePartCriteria.
 *
 * @package namespace App\Model\Criteria;
 */
class WithNamePartCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * WithPinCriteria constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Apply criteria in query repository
     *
     * @param Model|Builder $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model
            ->person()
            ->where('last_name', 'LIKE', "{$this->name}%")
            ->orWhere('first_name', 'LIKE', "{$this->name}%")
            ->orWhere('middle_name', 'LIKE', "{$this->name}%");

        return $model;
    }
}
