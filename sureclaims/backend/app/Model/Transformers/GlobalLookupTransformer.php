<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\GlobalLookup;

/**
 * Class GlobalLookupTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class GlobalLookupTransformer extends TransformerAbstract
{
    /**
     * Transform the GlobalLookup entity.
     *
     * @param \App\Model\Entities\GlobalLookup $model
     *
     * @return array
     */
    public function transform(GlobalLookup $model)
    {
        return [
            'id' => $model->id,
            'domain' => $model->domain_name,
            'type' => $model->lookup_type,
            'value' => $model->lookup_value,
        ];
    }
}
