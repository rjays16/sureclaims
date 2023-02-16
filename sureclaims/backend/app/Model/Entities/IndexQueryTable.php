<?php

namespace App\Model\Entities;

use App\Model\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class IndexQueryTable.
 * @property int $id
 * @property string $query
 * @property string $table
 * @property Carbon $created_at
 *
 * @package namespace App\Model\Entities;
 */
class IndexQueryTable extends Model implements Transformable
{
    use TransformableTrait,
        UsesSystemConnection;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'query',
        'table'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating( function ( $model ) {
            /** @var self $model */
            if ( !$model->created_at ) {
                $model->setCreatedAt( $model->freshTimestamp() );
            }
        } );
    }


}
