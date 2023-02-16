<?php
/**
 * TransmittalScopes.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Model\Concerns\Transmittal;


use Illuminate\Database\Eloquent\Builder;

/**
 * Trait TransmittalScopes
 *
 * @method Builder recentByYear(?string $year)
 *
 * @package App\Model\Concerns\Transmittal
 */
trait TransmittalScopes
{
    /**
     * @param Builder $query
     * @param string $year '2018'
     * @return Builder
     */
    public function scopeRecentByYear( $query, $year )
    {
        return $query
            ->whereYear( 'created_at', '=', $year )
            ->orderBy( 'transmittal_no', 'desc' );
    }
}
