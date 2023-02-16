<?php

/**
 * RepositoryInterface.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface as BaseRepositoryInterface;

/**
 *
 * Description of RepositoryInterface
 *
 */

interface RepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $includes
     * @param array $excludes
     * @param array $fieldSets
     *
     * @return $this
     */
    public function parsePresenterParameters($includes = [], $excludes = [], $fieldSets = []);

    /**
     * @param array $includes
     *
     * @return $this
     */
    public function parsePresenterIncludes($includes = []);

    /**
     * @param array $fieldSets
     *
     * @return $this
     */
    public function parsePresenterFieldSets($fieldSets = []);

    /**
     * @param mixed $model
     *
     * @return mixed
     */
    public function present($model);

    /**
     * @param array $rows
     *
     * @return mixed
     */
    public function createMany(array $rows);
}
