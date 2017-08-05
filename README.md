# Laravel-Api-Gateway



The API gateway in the microservice architecture represents a central entry point.

## Status of the project
This project is still in development.

## Main features
- OAuth2
- Individual scopes for services and routes
- Event-Handling with response
- Metrics for Prometheus

## Requirements
* Composer
* NPM

## Installation
At first, you must fork this project.

1) cd project/
2) composer install
3) npm install

## Configuration
A service for the gateway must be configured in the config of the gateway packages.
> app/config/gateway.php

| Key        | Value           | Description  |
|:-------------:|:-------------:|:-----:|
| scopes | ARRAY | Scope for OAuth2 |
| services | ARRAY | Services |
| services.* | STRING | Name of the service |
| services.*.scheme | http or https | Scheme |
| services.*.host | STRING | Host |
| services.*.port | INTEGER | Port |
| services.*.timeout | INTEGER | Timeout for curl |
| services.*.http_methods | ARRAY | Valid http methods |
| services.*.http_methods.\* | GET, POST, PUT, DELETE | HTTP-Method |
| services.*.events | ARRAY | Events ( Example: [NAME => CLASS] ) |
| services.*.scopesByRoutes | ARRAY | Scopes by routes ( Example: [REGEX => NAME OF THE SCOPE] ) |

### Additional infos

#### services.*.events
> The event class must be an instance of App\Providers\Gateway\Events\GatewayEvent

#### services.*.scopesByRoutes
> Must be an entry in the scopes array.

## Usage
To use the gateway, an account must be created first:
> http://gateway.dev/register

If an account already exists:
> http://gateway.dev/login

### Token
There are two methods available to create a token:
* OAuth
* Personal Access

> Before the token can be created, a client (OAuth Client | Personal Access Client) must be applied.

#### Authentication and identification of a client
The authentication or identify is done via the authorization header.
```
KEY: Authorization

VALUE: Bearer TOKEN
```

If the authentication of the client fails this response will be shown:
```
401: Unautzorized
```

### Routing
The routing of the gateway determines which service and which endpoint is requested.

> api/{service}/{endpoint}

##### Service
The service, which is selected via the route must be configured.

##### Endpoint
The end point is the core part of the request to the respective service.
This part of the URL is used to build the request URL to the service.

##### Example
> api/data/endpoint/object/1

* The configured service "data" should be used.
* The service is queried with the endpoint "endpoint/object/1" (E.g. https://gateway.de/endpoint/object/1).

### Event-Handling
A service events in the gateway can trigger by using the key **\_events** in the response.
These events are processed synchronously and need to be successfully executed to create a successfully response.

#### Example
```
[
    ...,
    "_events" => [
        "EVENT" => [
            "key1" => "value1",
            "key2" => "value2"
        ]
    ]
]
```

#### Process of an event
An event runs only if it from the abstract class * GatewayEvent * inherits.
> app/Providers/Gateway/Events/GatewayEvent.php

1. Check whether the event is an instance of GatewayEvent.
2. Event will be triggered
3. Validation of the event payload
4. Execute the event

## Metrics
The metrics are formatted for Prometheus.

### Package
All additional information about the package is available at Github.
[LaravelPrometheusExporter](https://github.com/triadev/LaravelPrometheusExporter)

### Endpoint
> triadev/pe/metrics

### List of available metrics

#### Counter
* gateway_{SERVICE}
* gateway_{SERVICE}\_http_method_{HTTP-METHOD}
* gateway_{SERVICE}\_endpoint_{ENDPOINT}
* gateway_dispatch_{SERVICE}\_endpoint_{ENDPOINT}
* gateway_error_service_not_exist
* gateway_error_http_method_not_accept
* gateway_event_{EVENT}
* gateway_error_event_{EVENT}
* gateway_error_{SERVICE}\_scope_{SCOPE}

#### Histogram
* gateway_curl_total_time
* gateway_curl_namelookup_time
* gateway_curl_connect_time
* gateway_curl_pretransfer_time
* gateway_curl_starttransfer_time

##### Addition informations
> ENDPOINT without special chars: /test/gateway => testgateway

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

## Other

### Project related links
- [Wiki](https://github.com/triadev/LaravelApiGateway/wiki)
- [Issue tracker](https://github.com/triadev/LaravelApiGateway/issues)

### Author
- [Christopher Lorke](mailto:christopher.lorke@gmx.de)

### License
The code for LaravelApiGateway is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[ico-license]: https://img.shields.io/github/license/triadev/LaravelApiGateway.svg?style=flat-square

