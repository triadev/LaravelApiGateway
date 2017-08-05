<?php
namespace App\Providers\PassportExtension\Provider;

use App\Providers\PassportExtension\Controller\OAuthClientController;
use App\Providers\PassportExtension\Controller\PasswordClientController;
use App\Providers\PassportExtension\Controller\PersonalAccessClientController;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\PassportExtension\Provider
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
        //
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

        $router->group([
            'prefix' => 'oauth/extended',
            'middleware' => ['web', 'auth']
        ], function () use ($router) {

            // OAuth-Clients
            $router->get('/clients', [
                'uses' => OAuthClientController::class . '@forUser',
            ]);

            // Person-Access-Clients
            $router->get('/personal-access-clients', [
                'uses' => PersonalAccessClientController::class . '@forUser',
            ]);

            $router->post('/personal-access-clients', [
                'uses' => PersonalAccessClientController::class . '@store',
            ]);

            $router->put('/personal-access-clients/{client_id}', [
                'uses' => PersonalAccessClientController::class . '@update',
            ]);

            $router->delete('/personal-access-clients/{client_id}', [
                'uses' => PersonalAccessClientController::class . '@destroy',
            ]);
        });
    }
}
