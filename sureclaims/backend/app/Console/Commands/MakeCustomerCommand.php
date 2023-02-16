<?php

/**
 * MakeCustomerCommand.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Console\Commands;

use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Console\Command;

class MakeCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<SIGNATURE
sureclaims:make:customer 
{fqdn : Domain name used to access the sureclaims application}
{--c|name= : The name of the customer}
{--e|email= : Primary email of the customer}
SIGNATURE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a customer record with matching website and hostname';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle()
    {
        $websites = app()->make(\Hyn\Tenancy\Contracts\Repositories\WebsiteRepository::class);
        $customers = app()->make(\Hyn\Tenancy\Contracts\Repositories\CustomerRepository::class);
        $hostnames = app()->make(\Hyn\Tenancy\Contracts\Repositories\HostnameRepository::class);

        if (!$this->confirm("This will create a new customer database for hostname {$this->argument('fqdn')}?")) {
            $this->line('Okay...fine!');
            return;
        }

        $this->line('Creating system entries...');

        /** @var Website $website */
        $website = factory(Website::class)->make();

        /** @var Customer $customer */
        $customer = factory(Customer::class)->make();
        $customer->name = $this->option('name') ?:
            $customer->name = $this->argument('fqdn');

        $this->line('Creating customer record ...');
        $customer->email = $this->option('email') ?: '';
        $customers->create($customer);
        $this->info('Customer record created!');

        /** @var Hostname $hostname */
        $hostname = factory(Hostname::class)->make();
        $hostname->fqdn = $this->argument('fqdn');

        $this->line('Creating customer website ...');
        $website->uuid = str_replace('.', '-', $this->argument('fqdn'));
        $website->customer()->associate($customer);
        $websites->create($website);
        $this->info('Customer website created!');

        $this->line('Setting up customer database ...');
        $hostname->website()->associate($website);
        $hostname->customer()->associate($customer);
        $hostnames->create($hostname);

        $this->info('DONE!');
    }
}
