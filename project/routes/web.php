<?php

use Illuminate\Http\Request;

Route::get('/', function () {
    // Build the query parameter string to pass auth information to our request
    $query = http_build_query([
        'client_id' => 1,
        'redirect_uri' => 'http://gateway.dev/callback',
        'response_type' => 'code',
        'scope' => 'write'
    ]);

    // Redirect the user to the OAuth authorization page
    return redirect('http://gateway.dev/oauth/authorize?' . $query);
});

Route::get('callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://192.168.48.1/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 1,
            'client_secret' => '4LTA6b1I6WqGNk2S4BW26IUzO086BQbdIm1aFnbn',
            'redirect_uri' => 'http://gateway.dev/callback',
            'code' => $request->code //
        ]
    ]);

    // echo the access token; normally we would save this in the DB
    return json_decode((string) $response->getBody(), true)['access_token'];
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
