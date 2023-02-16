<?php

/**
 * RefreshesDatabase.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Concerns;

use Hyn\Tenancy\Contracts\CurrentHostname;
use Hyn\Tenancy\Models\Hostname;
use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Str;

/**
 *
 * Description of RefreshDatabase
 *
 */

trait RefreshesDatabase
{
    use BaseRefreshDatabase {
        BaseRefreshDatabase::refreshInMemoryDatabase as baseRefreshInMemoryDatabase;
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');
        $path = str_replace_first(
            base_path() . DIRECTORY_SEPARATOR,
            '',
            config('tenancy.db.tenant-migrations-path')
        );

        $this->artisan('migrate', [
            '--path' => $path,
        ]);
        $this->app[Kernel::class]->setArtisan(null);
    }
}
