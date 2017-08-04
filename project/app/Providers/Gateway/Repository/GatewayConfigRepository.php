<?php
namespace App\Providers\Gateway\Repository;

use App\Providers\Gateway\Models\GatewayServiceModel;

/**
 * Class GatewayConfigRepository
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\Gateway\Repository
 */
class GatewayConfigRepository
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * GatewayConfigRepository constructor.
     *
     * @param array|null $config
     */
    public function __construct(?array $config = null)
    {
        if ($config) {
            $this->config = $config;
        } else {
            $this->config = config('gateway');
        }
    }

    /**
     * Exist service
     *
     * @param string $service
     * @return bool
     */
    public function existService(string $service) : bool
    {
        if (array_key_exists('services', $this->config)) {
            if (array_key_exists($service, $this->config['services'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get service
     *
     * @param string $service
     * @return GatewayServiceModel|null
     */
    public function getService(string $service) : ?GatewayServiceModel
    {
        if (array_key_exists('services', $this->config)) {
            if (array_key_exists($service, $this->config['services'])) {
                return new GatewayServiceModel(
                    $this->config['services'][$service]
                );
            }
        }

        return null;
    }

    /**
     * Exist http method
     *
     * @param string $service
     * @param string $http_method
     * @return bool
     */
    public function existHttpMethod(string $service, string $http_method) : bool
    {
        $service = $this->getService($service);
        if ($service) {
            if (in_array(strtoupper($http_method), $service->getHttpMethods())) {
                return true;
            }
        }

        return false;
    }
}
