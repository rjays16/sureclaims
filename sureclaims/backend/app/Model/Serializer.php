<?php

/**
 * Serializer.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model;

use League\Fractal\Serializer\ArraySerializer;

/**
 *
 * Description of Serializer
 *
 */

class Serializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey === '') {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }
}
