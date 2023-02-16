<?php
/**
 * ClaimQuery.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Model\Concerns\Claim;


use Illuminate\Database\Eloquent\Builder;

/**
 * Trait ClaimScopes
 *
 * @method Builder recentByYear(?string $year)
 *
 * @package App\Model\Concerns\Claim
 */
trait ClaimScopes
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
            ->orderBy( 'claim_no', 'desc' );
    }

    /**
     * @param Builder $query
     * @param bool $yes
     * @return Builder
     */
    public function scopeAttachedToTransmittal( $query, $yes = false )
    {
        return $query->whereNull('transmittal_id', 'OR', $yes)
            ->orWhere('transmittal_id', $yes ? '<>' : '=', '');
    }

    public function getAllTransmittedClaims($query)
    {
       return $query->whereNotNull( 'lhio_series_no')
                ->whereNull('status')
                ->get();
    }

    public function getClaimsWithErrors($query)
    {
        return $query->where('is_valid', '=', '0')
                    ->get();
    }
    public function getAllReadyClaims($query)
    {
        return $query->whereNull('lhio_series_no')
                    ->where('is_valid', '=', 1)
                    ->get();
    }

    public function getStatus($query, $status)
    {
        return $query->where( 'status', '=', "{$status}" )
                        ->get();
    }

}