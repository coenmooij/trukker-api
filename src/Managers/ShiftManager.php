<?php

namespace App\Managers;

use App\Models\Shift;
use Symfony\Component\HttpFoundation\Request;

class ShiftManager extends AbstractManager
{
    /**
     * @var JobProfileManager
     */
    private $jobProfileManager;

    public function __construct(JobProfileManager $jobProfileManager)
    {
        $this->jobProfileManager = $jobProfileManager;
    }

    public function getShiftsForUser(int $jobProfileId, int $userId): array
    {
        $this->jobProfileManager->getJobProfileForUser($jobProfileId, $userId);

        $collection = Shift::where(Shift::JOB_PROFILE_ID, $jobProfileId)->get([
            Shift::ID,
            Shift::TITLE,
            Shift::DESCRIPTION,
            Shift::COMPENSATION,
            Shift::START_DATE,
            Shift::END_DATE,
            Shift::START_LOCATION,
            Shift::END_LOCATION,
            Shift::IS_RETOUR,
            Shift::OUTBOUND_CARGO,
            Shift::INBOUND_CARGO,
        ]);

        return $collection->toArray();
    }

    public function getShiftForUser(int $shiftId, int $userId): Shift
    {
        $shift = Shift::where(Shift::ID, $shiftId)->firstOrFail();

        $this->jobProfileManager->getJobProfileForUser($shift->{Shift::JOB_PROFILE_ID}, $userId);

        return $shift;

    }

    public function createShiftForUser(Request $request, int $jobProfileId, int $userId): Shift
    {
        $this->jobProfileManager->getJobProfileForUser($jobProfileId, $userId);
        $shift = new Shift($request);
        $shift->{Shift::JOB_PROFILE_ID} = $jobProfileId;
        $shift->saveOrFail();

        return $shift;
    }
}
