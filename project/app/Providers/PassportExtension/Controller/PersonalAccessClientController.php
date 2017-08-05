<?php
namespace App\Providers\PassportExtension\Controller;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;

/**
 * Class PersonalAccessClientController
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package App\Providers\PassportExtension\Controller
 */
class PersonalAccessClientController
{
    use ValidatesRequests;

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
            if ((bool)$client->getAttribute('personal_access_client') == true) {
                $clientsCollection->add($client);
            }
        }

        return [
            'userId' => $request->user()->getAttribute('id'),
            'clients' => $clientsCollection
        ];
    }

    /**
     * Store a new client.
     *
     * @param Request $request
     * @param ClientRepository $clients
     * @return Response
     */
    public function store(Request $request, ClientRepository $clients) : Response
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'name' => 'required|max:255'
        ]);

        $client = $clients->createPersonalAccessClient(
            $request->user_id,
            $request->name,
            'http://localhost'
        );

        $accessClient = new PersonalAccessClient();
        $accessClient->client_id = $client->id;
        $accessClient->save();

        return new Response([
            'Personal Access Client was created.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the given client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param ClientRepository $clients
     * @param  string  $clientId
     * @return \Illuminate\Http\Response|\Laravel\Passport\Client
     */
    public function update(Request $request, ClientRepository $clients, string $clientId)
    {
        $client = $clients->findForUser($clientId, $request->user()->getKey());

        if (! $client) {
            return new Response('', 404);
        }

        $this->validate($request, [
            'user_id' => 'required|integer',
            'name' => 'required|max:255'
        ]);

        return $clients->update(
            $client,
            $request->name,
            $request->redirect
        );
    }

    /**
     * Delete the given client.
     *
     * @param  Request  $request
     * @param ClientRepository $clients
     * @param  string  $clientId
     * @return Response
     */
    public function destroy(Request $request, ClientRepository $clients, $clientId)
    {
        $client = $clients->findForUser($clientId, $request->user()->getKey());

        if (! $client) {
            return new Response('', 404);
        }

        $clients->delete(
            $client
        );
    }
}
