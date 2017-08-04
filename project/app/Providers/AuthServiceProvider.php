<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
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

        $scopes = [
            'Read' => 'Read access.',
            'Write' => 'Write access.',
        ];

        foreach (Passport::scopes()->toArray() as $scope) {
            $scopes[$scope['id']] = $scope['description'];
        }

        Passport::tokensCan($scopes);
    }
}
