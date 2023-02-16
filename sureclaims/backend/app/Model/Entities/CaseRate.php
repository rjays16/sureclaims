<?php

namespace App\Model\Entities;

use App\Model\Concerns\CaseRate\CaseRateScopes;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CaseRate.
 * @property string  $id
 * @property string  $hospital_type
 * @property string  $code
 * @property string  $description
 * @property string  $item_code
 * @property string  $item_description
 * @property double  $hci_fee
 * @property double  $prof_fee
 * @property boolean $allow_second
 * @property double  $secondary_hci_fee
 * @property double  $secondary_prof_fee
 * @property Carbon  $effectivity_date
 * @property string  $case_type
 * @property Carbon  $createdAt
 * @property Carbon  $updatedAt
 * @property double  $amount
 * @property double  $secondaryAmount
 *
 * @package namespace App\Model\Entities;
 */
class CaseRate extends Model implements Transformable
{
    use TransformableTrait,
        UsesSystemConnection,
        CaseRateScopes;

    const CASETYPE_ICD = 'icd';
    const CASETYPE_RVS = 'rvs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $dates = [
        'effectivity_date:Y-m-d',
        'created_at',
        'updated_at',
    ];

    /**
     * Return the total amount of primary fee: $hci_fee and $prof_fee
     */
    public function getAmountAttribute()
    {
        return round( ( $this->hci_fee ?: 0 ) + ( $this->prof_fee ?: 0 ) );
    }

    /**
     * Return the total secondary amount of primary fee: $secondary_hci_fee and $secondary_prof_fee
     */
    public function getSecondaryAmountAttribute()
    {
        return round( ( $this->secondary_hci_fee ?: 0 ) + ( $this->secondary_prof_fee ?: 0 ) );
    }
}
