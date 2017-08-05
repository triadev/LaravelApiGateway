<?php
namespace App\Providers\PassportExtension\Controller;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ClientRepository;

/**
 * Class OAuthClientController
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\PassportExtension\Controller
 */
class OAuthClientController
{
    /**
     * Get all of the clients for the authenticated user.
     *
     * @param Request $request
     * @param ClientRepository $clients)
     * @return Response
     */
    public function forUser(Request $request, ClientRepository $clients)
    {
        $userId = $request->user()->getKey();

        $clientsCollection = new Collection();
        foreach ($clients->activeForUser($userId)->makeVisible('secret') as $client) {
            if ((bool)$client->getAttribute('personal_access_client') == false) {
                $clientsCollection->add($client);
            }
        }

        return $clientsCollection;
    }
}
