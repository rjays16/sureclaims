<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Model\Repositories\Contracts\HospitalRepository::class, \App\Model\Repositories\HospitalRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\UserRepository::class, \App\Model\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\UserProfileRepository::class, \App\Model\Repositories\UserProfileRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\PersonRepository::class, \App\Model\Repositories\PersonRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\MemberRepository::class, \App\Model\Repositories\MemberRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\GlobalLookupRepository::class, \App\Model\Repositories\GlobalLookupRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\EligibilityRepository::class, \App\Model\Repositories\EligibilityRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\DoctorRepository::class, \App\Model\Repositories\DoctorRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\ClaimRepository::class, \App\Model\Repositories\ClaimRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\TransmittalRepository::class, \App\Model\Repositories\TransmittalRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\SupportingDocumentRepository::class, \App\Model\Repositories\SupportingDocumentRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\IcdCodeRepository::class, \App\Model\Repositories\IcdCodeRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\EmployerRepository::class, \App\Model\Repositories\EmployerRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\HciRepository::class, \App\Model\Repositories\HciRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\SampleRepository::class, \App\Model\Repositories\SampleRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\RvsCodeRepository::class, \App\Model\Repositories\RvsCodeRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\IndexQueryTableRepository::class, \App\Model\Repositories\IndexQueryTableRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\CaseRateRepository::class, \App\Model\Repositories\CaseRateRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\SecondCaseRateRepository::class, \App\Model\Repositories\SecondCaseRateRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\ReturnDocumentRepository::class, \App\Model\Repositories\ReturnDocumentRepositoryEloquent::class);
        $this->app->bind(\App\Model\Repositories\Contracts\ClaimStatusDetailRepository::class, \App\Model\Repositories\ClaimStatusDetailRepositoryEloquent::class);
        //:end-bindings:
    }
}
