<?php
namespace App\Providers\Gateway\Models;

/**
 * Class GatewayServiceModel
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\Gateway\Models
 */
class GatewayServiceModel
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $http_methods;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var array
     */
    private $scopesByRoute = [];

    /**
     * GatewayServiceModel constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->http_methods = $config['http_methods'];
        $this->timeout = $config['timeout'];
        $this->events = $config['events'];
        $this->scopesByRoute = $config['scopesByRoute'];

        $this->buildUrl($config['scheme'], $config['host'], $config['port']);
    }

    /**
     * Build url
     *
     * @param string $scheme
     * @param string $host
     * @param int $port
     */
    private function buildUrl(string $scheme, string $host, int $port)
    {
        $this->url = sprintf(
            "%s://%s%s",
            $scheme,
            $host,
            $port != 80 ? ":{$port}" : ''
        );
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getHttpMethods(): array
    {
        return $this->http_methods;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function getEvent(string $key): ?string
    {
        if (array_key_exists($key, $this->events)) {
            return $this->events[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getScopesByRoute(): array
    {
        return $this->scopesByRoute;
    }
}
