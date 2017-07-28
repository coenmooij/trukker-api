<?php

namespace App\Managers;

use App\Models\Client;
use App\Models\XorUserClient;

class ClientManager extends AbstractManager
{
    public function createClient(array $request): int
    {
        $client = new Client();
        $client->{Client::NAME} = $request[Client::NAME];
        $client->saveOrFail();

        return $client->{Client::ID};
    }

    public function addUser(int $clientId, int $userId): void
    {
        $xorUserClient = new XorUserClient();
        $xorUserClient->{XorUserClient::CLIENT_ID} = $clientId;
        $xorUserClient->{XorUserClient::USER_ID} = $userId;
        $xorUserClient->saveOrFail();
    }
}
