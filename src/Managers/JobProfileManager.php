<?php

namespace App\Managers;

use App\Models\JobProfile;
use App\Models\XorUserClient;

class JobProfileManager extends AbstractManager
{
    public function getJobProfileForUser(int $jobProfileId, int $userId): JobProfile
    {
        $clientId = $this->getClientId($userId);

        return JobProfile::where(JobProfile::ID, $jobProfileId)
            ->where(JobProfile::CLIENT_ID, $clientId)
            ->firstOrFail();

    }

    public function getJobProfilesForUser($userId): array
    {
        $clientId = $this->getClientId($userId);
        $collection = JobProfile::where(JobProfile::CLIENT_ID, $clientId)->get([
            JobProfile::TITLE,
            JobProfile::DESCRIPTION,
            JobProfile::LICENSE,
            JobProfile::CODE_95,
            JobProfile::VEHICLE_TYPE
        ]);

        return $collection->toArray();
    }

    public function createJobProfileForUser($request, $userId): JobProfile
    {
        $clientId = $this->getClientId($userId);
        $jobProfile = new JobProfile($request);
        $jobProfile->{JobProfile::CLIENT_ID} = $clientId;
        $jobProfile->saveOrFail();

        return $jobProfile;
    }

    private function getClientId(int $userId): int
    {
        return XorUserClient::where(XorUserClient::USER_ID, $userId)
            ->select(XorUserClient::CLIENT_ID)
            ->firstOrFail()->{XorUserClient::CLIENT_ID};
    }
}
