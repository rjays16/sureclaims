<?php

namespace App\Model\Repositories;

use App\Model\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\IndexQueryTableRepository;
use App\Model\Entities\IndexQueryTable;
use App\Model\Validators\IndexQueryTableValidator;

/**
 * Class IndexQueryTableRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class IndexQueryTableRepositoryEloquent extends BaseRepository implements IndexQueryTableRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return IndexQueryTable::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app( RequestCriteria::class ) );
    }

    /**
     * Create a new indexed query
     * @param string $query
     * @param RepositoryInterface $repository
     * @return void
     */
    public function createIndex( string $query, RepositoryInterface $repository ): void
    {
        $this->create([
            'query' => $query,
            'table' => $repository->makeModel()->getTable()
        ] );
    }

    /**
     * @param string|null $query
     * @param RepositoryInterface $repository
     * @return IndexQueryTableRepositoryEloquent|null
     */
    public function findIndexQuery( string $query = null, RepositoryInterface $repository )
    {
        return $this->findWhere( [
            'query' => $query,
            'table' => $repository->makeModel()->getTable(),
            [ 'created_at', '>=', now()->subHour( 1 )->toDateTimeString() ]
        ] )->first();
    }
}
