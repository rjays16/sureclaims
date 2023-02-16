<?php

namespace App\Model\Repositories\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface TransmittalRepository.
 *
 * @package namespace App\Model\Repositories\Contracts;
 */
interface TransmittalRepository extends RepositoryInterface
{
    //

    /**
     * Get a collection of transmittals that have not been
     * transmitted or mapped yet via PHIC ECS web service
     *
     * @return Collection|array
     */
    public function autoTransmittables();
}
