<?php
namespace App\Providers\Gateway;

use App\Providers\Gateway\Contract\GatewayContract;
use App\Providers\Gateway\Mapper\GatewayHttpMethodMapper;
use App\Providers\Gateway\Models\GatewayServiceModel;
use App\Providers\Gateway\Repository\GatewayConfigRepository;
use App\User;
use Illuminate\Http\Response;

/**
 * Class Gateway
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway
 */
class Gateway implements GatewayContract
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
    ): Response {
        $gatewayConfigRepository = new GatewayConfigRepository();

        /** @var GatewayServiceModel $service */
        $service = $gatewayConfigRepository->getService($service);

        $response = $this->curl(
            $this->buildRequestUrl($service->getUrl(), $endpoint),
            $httpMethod,
            $service->getTimeout(),
            $header,
            $payload
        );

        return $response;
    }

    /**
     * Build request url
     *
     * @param string $url
     * @param string $endpoint
     * @return string
     */
    public function buildRequestUrl(string $url, string $endpoint) : string
    {
        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        return $url . $endpoint;
    }

    /**
     * Curl
     *
     * @param string $url
     * @param string $http_method
     * @param int $timeout
     * @param array $header
     * @param array $payload
     * @return Response
     */
    private function curl(
        string $url,
        string $http_method,
        int $timeout,
        array $header = [],
        array $payload = []
    ) : Response {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        if ($http_method == GatewayHttpMethodMapper::HTTP_METHOD_POST) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        } elseif ($http_method == GatewayHttpMethodMapper::HTTP_METHOD_PUT) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        }

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            return new Response([
                'status' => 'ERROR',
                'result' => [
                    'message' => 'Request timeout',
                    'url' => $url,
                    'http_method' => $http_method
                ]
            ], Response::HTTP_REQUEST_TIMEOUT);
        }

        if ($result) {
            $result = json_decode($result, true);
        } else {
            $result = curl_error($curl);
        }

        $http_status_code = curl_getinfo($curl)['http_code'];

        curl_close($curl);

        return new Response($result, $http_status_code);
    }
}
