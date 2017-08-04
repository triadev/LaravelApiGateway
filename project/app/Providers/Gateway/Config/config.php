<?php

use App\Providers\Gateway\Mapper\GatewayHttpMethodMapper;

return [
    'services' => [
        'data' => [
            'scheme' => 'https',
            'host' => 'traum-ferienwohnungen.de',
            'port' => 80,
            'timeout' => 10,
            'http_methods' => [
                GatewayHttpMethodMapper::HTTP_METHOD_POST,
                GatewayHttpMethodMapper::HTTP_METHOD_PUT
            ]
        ]
    ]
];
