<?php

/**
 * NormalizesFillableData.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Concerns;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * Description of NormalizesFillableData
 *
 */

trait NormalizesFillableData
{
    /**
     * @param Model $model
     * @param array $data
     */
    public function normalizeFillableData(Model $model, $data)
    {
        $normalizedData = [];
        $dateTimeFields = $model->getDates();
        foreach ($data as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);
            if ($model->isFillable($normalizedKey)) {
                if (in_array($normalizedKey, $dateTimeFields)) {
                    $normalizedValue = null;
                    foreach (['ec_from_date', 'ec_from_datetime', 'ec_from_time'] as $func) {
                        try {
                            $normalizedValue = call_user_func($func, $value);
                        } catch (\Exception $e) {}
                    }
                    $normalizedData[$normalizedKey] = $normalizedValue;
                } else {
                    $normalizedData[$normalizedKey] = $value;
                }
            }
        }
        return $normalizedData;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function normalizeKey($key)
    {
        return snake_case($key);
    }
}
