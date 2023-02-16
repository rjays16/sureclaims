<?php
/**
 * CaseRateScopes.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Model\Concerns\CaseRate;


use App\Model\Contracts\HospitalTypeInterface;
use Illuminate\Database\Eloquent\Builder;

trait CaseRateScopes
{
    /**
     * Filter case rates for Tertiary Hospital
     * @param Builder $query
     * @return Builder
     */
    public function scopeTertiary( $query ): Builder
    {
        return $query->whereNull( 'hospital_type' );
    }

    /**
     * Filter case rates for Primary Hospital
     * @param Builder $query
     * @return Builder
     */
    public function scopePrimary( $query ): Builder
    {
        return $query->where( 'hospital_type', HospitalTypeInterface::PRIMARY );
    }

    /**
     * Get all active case rate
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query) : Builder
    {
        return $query->where('is_active', '=', 1);
    }
}
