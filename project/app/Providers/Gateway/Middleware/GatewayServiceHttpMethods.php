<?php
namespace App\Providers\Gateway\Middleware;

use App\Providers\Gateway\Repository\GatewayConfigRepository;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;
use PrometheusExporter;

/**
 * Class GatewayServiceHttpMethods
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\Gateway\Middleware
 */
class GatewayServiceHttpMethods
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

        if (!$this->existHttpMethod(new GatewayConfigRepository(), $service, $request->method())) {
            PrometheusExporter::incCounter('gateway_error_http_method_not_accept', 'Metric: http method not accept');

            return new Response([
                'status' => 'ERROR',
                'result' => [
                    'message' => 'The service do not accept this http method.',
                    'service' => $service,
                    'method' => $request->method()
                ]
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }

    /**
     * Exist http method
     *
     * @param GatewayConfigRepository $gatewayConfigRepository
     * @param string $service
     * @param string $http_method
     * @return bool
     */
    public function existHttpMethod(
        GatewayConfigRepository $gatewayConfigRepository,
        string $service,
        string $http_method
    ) : bool {
        return $gatewayConfigRepository->existHttpMethod($service, $http_method);
    }
}
