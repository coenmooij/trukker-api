<?php

namespace App\Managers;

use App\Models\XorUserClient;

class AbstractManager
{
    protected function getClientId(int $userId): int
    {
        return XorUserClient::where(XorUserClient::USER_ID, $userId)
            ->select(XorUserClient::CLIENT_ID)
            ->firstOrFail()->{XorUserClient::CLIENT_ID};
    }
}
