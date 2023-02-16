<?php

use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Database\Seeder;

class HostnameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $websites = app()->make(\Hyn\Tenancy\Contracts\Repositories\WebsiteRepository::class);
        $customers = app()->make(\Hyn\Tenancy\Contracts\Repositories\CustomerRepository::class);
        $hostnames = app()->make(\Hyn\Tenancy\Contracts\Repositories\HostnameRepository::class);

        /** @var Website $website */
        $website = factory(Website::class)->make();

        /** @var Customer $customer */
        $customer = factory(Customer::class)->make();

        $customers->create($customer);

        $hostname = factory(Hostname::class)->make();

        $website->customer()->associate($customer);
        $websites->create($website);

        $hostname->website()->associate($website);
        $hostname->customer()->associate($customer);
        $hostnames->create($hostname);
    }
}
