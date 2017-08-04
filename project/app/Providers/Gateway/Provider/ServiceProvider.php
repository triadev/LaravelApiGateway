<?php
namespace App\Providers\Gateway\Provider;

use App\Providers\Gateway\Contract\GatewayContract;
use App\Providers\Gateway\Gateway;
use App\Providers\Gateway\Middleware\GatewayEventTrigger;
use App\Providers\Gateway\Middleware\GatewayRouteScopes;
use App\Providers\Gateway\Middleware\GatewayServiceExist;
use App\Providers\Gateway\Middleware\GatewayServiceHttpMethods;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class ServiceProvider
 *
 * @author Christopüher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway\Provider
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../Config/config.php');

        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('gateway.php'),
        ], 'config');

        $this->mergeConfigFrom($source, 'gateway');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');

        // Passport
        Passport::tokensCan(config('gateway')['scopes']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('gateway.service.exist', GatewayServiceExist::class);
        $router->aliasMiddleware('gateway.service.http_method', GatewayServiceHttpMethods::class);
        $router->aliasMiddleware('gateway.event.trigger', GatewayEventTrigger::class);
        $router->aliasMiddleware('gateway.route.scopes', GatewayRouteScopes::class);

        $this->app->singleton(GatewayContract::class, function () {
            return new Gateway();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [
            GatewayContract::class
        ];
    }
}
