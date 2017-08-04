<?php
namespace App\Providers\Gateway\Contract;

use App\User;
use Illuminate\Http\Response;

/**
 * Interface GatewayContract
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway\Contract
 */
interface GatewayContract
{
    /**
     * Dispatch
     *
     * @param User $user
     * @param string $httpMethod
     * @param string $service
     * @param string $endpoint
     * @param array $header
     * @param array $payload
     * @return Response
     */
    public function dispatch(
        User $user,
        string $httpMethod,
        string $service,
        string $endpoint,
        array $header = [],
        array $payload = []
    ) : Response;
}
