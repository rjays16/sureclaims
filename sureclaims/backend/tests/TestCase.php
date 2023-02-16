<?php

namespace Tests;

use App\Console\Kernel;
use Hyn\Tenancy\Providers\TenancyProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /** @var Application */
    protected $app;

    /**
     * Service providers to load during this test.
     *
     * @var array
     */
    protected $loadProviders = [
        TenancyProvider::class,
    ];

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=testing');
        putenv('TENANCY_DEFAULT_CONNECTION=testing');
        putenv('TENANCY_DEFAULT_HOSTNAME=local.testing');
        putenv('TENANCY_CURRENT_HOSTNAME=local.testing');
        putenv('TENANCY_SYSTEM_CONNECTION_NAME=testing');
        putenv('TENANCY_TENANT_CONNECTION_NAME=testing');

        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        foreach ($this->loadProviders as $provider) {
            if (!$app->register($provider)) {
                throw new \RuntimeException("Failed registering $provider");
            }
        }

        $this->app = $app;
        return $app;
    }

    /**
     * Allows implementation in a test.
     *
     * @param Application $app
     */
    protected function duringSetUp(Application $app)
    {
        // ..
    }

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->duringSetUp($this->app);
    }

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
