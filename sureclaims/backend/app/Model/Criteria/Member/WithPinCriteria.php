<?php

/**
 * WithPinCriteria.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Criteria\Member;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithPinCriteria.
 *
 * @package namespace App\Model\Criteria;
 */
class WithPinCriteria implements CriteriaInterface
{

    /**
     * @var string
     */
    private $pin;

    /**
     * WithPinCriteria constructor.
     * @param string $pin
     */
    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('pin', '=', $this->pin);
        return $model;
    }
}
