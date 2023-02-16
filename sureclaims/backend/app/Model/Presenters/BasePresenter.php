<?php

/**
 * FractalRelationshipsPresenter.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Presenters;

use Prettus\Repository\Presenter\FractalPresenter;


/**
 *
 * Description of FractalRelationshipsPresenter
 *
 */

abstract class BasePresenter extends FractalPresenter
{

    /**
     * @param array $fieldSets
     *
     * @return $this
     */
    public function fieldSets($fieldSets = [])
    {
        $this->fractal->parseFieldsets($fieldSets);

        return $this;
    }


    /**
     * @param array $includes
     *
     * @return $this
     */
    public function includes($includes = [])
    {
        $this->fractal->parseIncludes($includes);

        return $this;
    }

    /**
     * @param array $excludes
     *
     * @return $this
     */
    public function excludes($excludes = [])
    {
        $this->fractal->parseExcludes($excludes);

        return $this;
    }
}
