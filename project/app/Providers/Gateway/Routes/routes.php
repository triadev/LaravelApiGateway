<?php

Route::group([
    'prefix' => 'api/{service}/{endpoint}',
    'where' => [
        'service' => '[a-zA-Z]+',
        'endpoint' => '.*'
    ],
    'middleware' => [
        'api',
        'gateway.service.exist',
        'gateway.service.http_method'
    ]
], function () {

    // Get
    Route::get('', [
        'uses' => \App\Providers\Gateway\Controller\GatewayController::class . '@gateway'
    ])->middleware('oauth.anyScope:read,write');

    // Post
    Route::post('', [
        'uses' => \App\Providers\Gateway\Controller\GatewayController::class . '@gateway'
    ])->middleware('oauth.anyScope:write');

    // Put
    Route::put('', [
        'uses' => \App\Providers\Gateway\Controller\GatewayController::class . '@gateway'
    ])->middleware('oauth.anyScope:write');

    // Delete
    Route::delete('', [
        'uses' => \App\Providers\Gateway\Controller\GatewayController::class . '@gateway'
    ])->middleware('oauth.anyScope:write');
});
