<?php
namespace App\Providers\Gateway\Middleware;

use App\Providers\Gateway\Events\GatewayEvent;
use App\Providers\Gateway\Repository\GatewayConfigRepository;
use App\User;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;

/**
 * Class GatewayEventTrigger
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\Gateway\Middleware
 */
class GatewayEventTrigger
{
    /**
     * @var Response
     */
    private $response;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Response response */
        $this->response = $next($request);

        $resultTriggerEvents = $this->triggerEvents(
            $request->user(),
            $request->route()->parameter('service')
        );

        if (!$resultTriggerEvents) {
            $this->response = new Response([
                'status' => 'ERROR',
                'result' => [
                    'message' => 'An error occurred during the processing of the data.'
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->response;
    }

    /**
     * Trigger events
     *
     * @param User $user
     * @param string $service
     * @return bool
     */
    public function triggerEvents(User $user, string $service) : bool
    {
        $result = true;

        $serviceConfig = (new GatewayConfigRepository())->getService($service);
        if ($serviceConfig) {
            $responseContent = json_decode($this->response->content(), true);
            if (array_key_exists('_events', $responseContent)) {
                foreach ($responseContent['_events'] as $eventKey => $eventPayload) {
                    $event = $serviceConfig->getEvent($eventKey);
                    if ($event) {
                        /** @var GatewayEvent $eventClass */
                        $eventClass = new $event();
                        if ($eventClass instanceof GatewayEvent) {
                            $result = $eventClass->trigger($user->getAttribute('id'), $eventPayload);
                            if (!$result) {
                                break;
                            }
                        }
                    }
                }

                unset($responseContent['_events']);

                $this->response->setContent(json_encode($responseContent));
            }
        }

        return $result;
    }
}
