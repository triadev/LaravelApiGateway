<?php

Route::match(
    ['get', 'post', 'put', 'delete'],
    '{service}/{endpoint}',
    [
        'uses' => \App\Api\Controllers\GatewayController::class . '@gateway'
    ]
)->where('service', '[a-zA-Z\_\-]+')
    ->where('endpoint', '.*');
