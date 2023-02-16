<?php

/**
 * WithMetaphones.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Concerns;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * Description of WithMetaphones
 *
 */
trait WithMetaphones
{
    /**
     * Boot the userstamps trait for a model.
     *
     * @return void
     */
    public static function bootWithMetaphones()
    {
        static::creating([static::class, 'handleMetaphones']);
        static::updating([static::class, 'handleMetaphones']);
    }

    /**
     * @param Model $model
     */
    public static function handleMetaphones($model)
    {
        if (method_exists($model, 'withMetaphones')) {
            $fields = $model->withMetaphones();
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $metaphoneField = $field . '_metaphone';
                    $model->$metaphoneField = metaphone($model->$field);
                }
            }
        }
    }
}
