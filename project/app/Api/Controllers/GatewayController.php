<?php
namespace App\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class GatewayController
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Api\Controllers
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
        return new Response();
    }
}
