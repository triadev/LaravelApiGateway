<?php
namespace App\Providers\Gateway\Controller;

use App\Providers\Gateway\Contract\GatewayContract;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

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
