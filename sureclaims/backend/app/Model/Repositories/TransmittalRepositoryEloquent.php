<?php

namespace App\Model\Repositories;

use App\Model\Presenters\TransmittalPresenter;
use App\Model\Repositories\Contracts\TransmittalRepository;
use App\Model\Entities\Transmittal;
use App\Model\Validators\TransmittalValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class TransmittalRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class TransmittalRepositoryEloquent
    extends BaseRepository
    implements TransmittalRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Transmittal::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return TransmittalPresenter::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TransmittalValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @inheritdoc
     */
    public function autoTransmittables()
    {
        $this->resetScope();
        $this->scopeQuery(function ($query) {
            /** @var Builder $query  */
            $query = $query->where('auto_transmit', '=', 1);
            $query = $query->where(function ($innerQuery) {
                /** @var Builder $innerQuery */
                $innerQuery
                    ->whereNull('status')
                    ->orWhere('status', '=', Transmittal::STATUS_UPLOADED);

            });
            return $query;
        });
        return $this->all();
    }
}
