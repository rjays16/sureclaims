<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDetails extends Model
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo( Customer::class, 'customer_id' );
    }
}
