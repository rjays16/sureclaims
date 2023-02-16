<?php

/**
 * BaseRepository.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Repositories;

use App\Model\Presenters\BasePresenter;
use App\Model\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;

/**
 *
 * Description of BaseRepository
 *
 * @property BasePresenter $presenter
 */
abstract class BaseRepository
    extends PrettusBaseRepository
    implements RepositoryInterface
{

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null $page
     * @param int|null $pageSize
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function paginate($page = null, $pageSize = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($pageSize) ? config('repository.pagination.limit', 15) : $pageSize;

        /** @var \Illuminate\Pagination\LengthAwarePaginator $results */
        $results = $this->model->{$method}($limit, $columns, 'page', $page);
        $results->appends(app('request')->query());

        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * @param array $rows
     */
    public function createMany(array $rows)
    {
        foreach ($rows as $row)
        {
            $this->create($row);
        }
    }

    /**
     * @param array $includes
     * @param array $excludes
     * @param array $fieldSets
     *
     * @return $this
     */
    public function parsePresenterParameters($includes = [], $excludes = [], $fieldSets = [])
    {
        if ($this->presenter) {
            $this->presenter->includes($includes);
            $this->presenter->excludes($excludes);
            $this->presenter->fieldSets($fieldSets);
        }

        return $this;
    }

    /**
     * @param array $includes
     *
     * @return $this
     */
    public function parsePresenterIncludes($includes = [])
    {
        if ($this->presenter) {
            $this->presenter->includes($includes);
        }

        return $this;
    }

    /**
     * @param array $fieldSets
     *
     * @return $this
     */
    public function parsePresenterFieldSets($fieldSets = [])
    {
        if ($this->presenter) {
            $this->presenter->fieldSets($fieldSets);
        }

        return $this;
    }

    /**
     * @param mixed $model
     *
     * @return Model|array|null
     */
    public function present($model)
    {
        if ($this->presenter) {
            return $this->presenter->present($model);
        }
        return $model;
    }
}
