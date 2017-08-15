<?php

namespace App\Managers;

use App\Models\JobProfile;
use Symfony\Component\HttpFoundation\Request;

class JobProfileManager extends AbstractManager
{
    public function getJobProfileForUser(int $jobProfileId, int $userId): JobProfile
    {
        $clientId = $this->getClientId($userId);

        return JobProfile::where(JobProfile::ID, $jobProfileId)
            ->where(JobProfile::CLIENT_ID, $clientId)
            ->firstOrFail();

    }

    public function getJobProfilesForUser(int $userId): array
    {
        $clientId = $this->getClientId($userId);
        $collection = JobProfile::where(JobProfile::CLIENT_ID, $clientId)->get([
            JobProfile::ID,
            JobProfile::TITLE,
            JobProfile::DESCRIPTION,
            JobProfile::LICENSE,
            JobProfile::CODE_95,
            JobProfile::VEHICLE_TYPE
        ]);

        return $collection->toArray();
    }

    public function createJobProfileForUser(Request $request, int $userId): JobProfile
    {
        $clientId = $this->getClientId($userId);
        $jobProfile = new JobProfile($request);
        $jobProfile->{JobProfile::CLIENT_ID} = $clientId;
        $jobProfile->saveOrFail();

        return $jobProfile;
    }
}
