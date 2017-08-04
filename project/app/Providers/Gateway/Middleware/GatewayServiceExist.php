<?php
namespace App\Providers\Gateway\Middleware;

use App\Providers\Gateway\Repository\GatewayConfigRepository;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;

/**
 * Class GatewayServiceExist
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\Gateway\Middleware
 */
class GatewayServiceExist
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $service = $request->route()->parameter('service');

        if (!$this->existService(new GatewayConfigRepository(), $service)) {
            return new Response([
                'status' => 'ERROR',
                'result' => [
                    'message' => 'The service could not be found.',
                    'service' => $service
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }

    /**
     * Exist service
     *
     * @param GatewayConfigRepository $gatewayConfigRepository
     * @param string $service
     * @return bool
     */
    public function existService(GatewayConfigRepository $gatewayConfigRepository, string $service) : bool
    {
        return $gatewayConfigRepository->existService($service);
    }
}
