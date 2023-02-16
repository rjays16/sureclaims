<?php
/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://laravel-tenancy.com
 * @see https://github.com/hyn/multi-tenant
 */
namespace Tests\Concerns;

use App;
use App\Lib\Auth\CurrentUser;
use App\User;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Traits\DispatchesEvents;

trait InteractsWithTenancy
{
    use DispatchesEvents;

    /**
     *
     */
    protected function setUpTenancy() : void
    {
        $this->setupCurrentHostName();
    }

    /**
     *
     */
    protected function setupCurrentHostName() : void
    {
        // Force tenancy:migration to use sqlite in memory database
        $website = new Website();
        $website->uuid = ':memory:';
        // $website->save();

        //
        $hostname = new Hostname();
        $hostname->fqdn = 'local.testing';
        $hostname->website()->associate($website);

        App::singleton(CurrentHostname::class, function () use ($hostname) {
            return $hostname;
        });

        $user = new User();
        App::singleton(CurrentUser::class, function () use ($user) {
            return $user;
        });
    }

}