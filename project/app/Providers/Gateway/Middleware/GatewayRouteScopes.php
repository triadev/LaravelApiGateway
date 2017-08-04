<?php
namespace App\Providers\Gateway\Middleware;

use App\Providers\Gateway\Repository\GatewayConfigRepository;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Closure;
use Laravel\Passport\Exceptions\MissingScopeException;

/**
 * Class GatewayRouteScopes
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway\Middleware
 */
class GatewayRouteScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\AuthenticationException|\Laravel\Passport\Exceptions\MissingScopeException
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->token()) {
            throw new AuthenticationException();
        }

        $this->checkScopes(
            $request->user(),
            $request->route()->parameter('service'),
            $request->route()->parameter('endpoint')
        );

        return $next($request);
    }

    /**
     * Check scopes
     *
     * @param User $user
     * @param string $service
     * @param string $endpoint
     * @throws MissingScopeException
     */
    private function checkScopes(User $user, string $service, string $endpoint)
    {
        $serviceConfig = (new GatewayConfigRepository())->getService($service);
        foreach ($serviceConfig->getScopesByRoute() as $route => $scopes) {
            if (preg_match("/{$route}/", $endpoint)) {
                foreach ($scopes as $scope) {
                    if (!$user->tokenCan($scope)) {
                        throw new MissingScopeException($scope);
                    }
                }
            }
        }
    }
}
