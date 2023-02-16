<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\Hospital;

/**
 * Class HospitalTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class HospitalTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * Transform the Hospital entity.
     *
     * @param \App\Model\Entities\Hospital $hospital
     *
     * @return array
     */
    public function transform(Hospital $hospital)
    {
        return [
            'id' => (int) $hospital->id,

            /* place your other model properties here */
            'code' => $hospital->code,
            'name' => $hospital->name,
            'address' => $hospital->address,
            'category' => $hospital->category,

            'createdAt' => $hospital->created_at,
            'updatedAt' => $hospital->updated_at
        ];
    }

}
