<?php

namespace App\Http\Controllers;

use App\Managers\ShiftManager;
use App\Models\Shift;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShiftController extends AbstractController
{
    const POST_VALIDATION = [
        Shift::TITLE => 'required|max:255',
        Shift::DESCRIPTION => 'max:1000',
        Shift::COMPENSATION => 'required|integer|min:1|max:1000',
        Shift::START_DATE => 'required|max:255',
        Shift::END_DATE => 'required|max:255',
        Shift::START_LOCATION => 'required|max:255',
        Shift::END_LOCATION => 'required|max:255',
        Shift::IS_RETOUR => 'boolean',
        Shift::OUTBOUND_CARGO => 'required|max:255',
        Shift::INBOUND_CARGO => 'max:255',
    ];
    /**
     * @var ShiftManager
     */
    private $shiftManager;

    public function __construct(ShiftManager $shiftManager)
    {
        $this->shiftManager = $shiftManager;
    }

    public function getAll(Request $request, int $jobProfileId)
    {
        $shifts = $this->shiftManager->getShiftsForUser($jobProfileId, $this->getUserId($request));

        return $this->createResponse(['shifts' => $shifts], Response::HTTP_OK);
    }

    public function get(Request $request, int $shiftId)
    {
        try {
            $shift = $this->shiftManager->getShiftForUser($shiftId, $this->getUserId($request));
        } catch (ModelNotFoundException $exception) {
            return $this->resourceNotAccessible();
        }
        return $this->createResponse(['shift' => $shift->toArray()], Response::HTTP_OK);
    }

    public function post(Request $request, int $jobProfileId): JsonResponse
    {
        $this->validate($request, self::POST_VALIDATION);

        try {
            $shift = $this->shiftManager->createShiftForUser(
                $request->only(array_keys(self::POST_VALIDATION)),
                $jobProfileId,
                $this->getUserId($request)
            );
        } catch (ModelNotFoundException $exception) {
            return $this->resourceNotAccessible();
        }
        return $this->createResponse($shift->toArray(), Response::HTTP_CREATED);
    }
}
