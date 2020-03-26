<?php

namespace App\Providers;

use App\Income;
use App\Paycheck;
use App\Bill;
use App\Policies\IncomePolicy;
use App\Policies\PaycheckPolicy;
use App\Policies\BillPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Income::class => IncomePolicy::class,
        Paycheck::class => PaycheckPolicy::class,
        Bill::class => BillPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
