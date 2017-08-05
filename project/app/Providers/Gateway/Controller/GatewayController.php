<?php
namespace App\Providers\Gateway\Controller;

use App\Providers\Gateway\Contract\GatewayContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use PrometheusExporter;

/**
 * Class GatewayController
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway\Controller
 */
class GatewayController extends Controller
{
    /**
     * Gateway
     *
     * @param Request $request
     * @param string $service
     * @param string $endpoint
     * @return Response
     */
    public function gateway(Request $request, string $service, string $endpoint) : Response
    {
        /*PrometheusExporter::incCounter(
            sprintf("gateway_%s", $service),
            "Metrics by service"
        );

        PrometheusExporter::incCounter(
            sprintf("gateway_%s_%s", $service, strtolower($request->method())),
            "Metrics by http method from service"
        );

        PrometheusExporter::incCounter(
            sprintf("gateway_%s_%s", $service, $endpoint),
            "Metrics by endpoint from service"
        );*/

        /** @var GatewayContract $gatewayService */
        $gatewayService = app(GatewayContract::class);

        $headers = ['Excpect:'];

        $payload = [];
        foreach ($request->request->all() as $key => $param) {
            if (!in_array($key, [
                'gateway.provider'
            ])) {
                $payload[$key] = $param;
            }
        }

        return $gatewayService->dispatch(
            $request->user(),
            $request->method(),
            $service,
            $endpoint,
            $headers,
            $payload
        );
    }
}
