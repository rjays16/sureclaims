<?php

namespace App\Model\Criteria\Person;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithNameSearchCriteria.
 *
 * @package namespace App\Model\Criteria;
 */
class WithNameSearchCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $search;

    /**
     * WithPinCriteria constructor.
     * @param string $search
     */
    public function __construct($search)
    {
        $this->search = $search;
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
        $terms = array_map(function($term) {
            return trim($term);
        }, explode(',', $this->search));

        if (empty($terms)) {
            return $model;
        }

        $model = $model->where('last_name', 'LIKE', "{$terms[0]}%");
        if (!empty($terms[1])) {
            $model = $model->where('first_name', 'LIKE', "{$terms[1]}%");
        } else {
            $model = $model->orWhere('first_name', 'LIKE', "{$terms[0]}%");
        }

        return $model;
    }
}
