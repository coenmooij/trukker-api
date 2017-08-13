<?php

namespace App\Http\Controllers;

use App\Managers\JobProfileManager;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobProfileController extends AbstractController
{
    const POST_VALIDATION = [
        'title' => 'required|max:255',
        'description' => 'required|max:1000',
        'vehicle_type' => 'required|max:255',
        'license' => 'required|max:255',
        'code_95' => 'boolean',
    ];
    /**
     * @var JobProfileManager
     */
    private $jobProfileManager;

    public function __construct(JobProfileManager $jobProfileManager)
    {
        $this->jobProfileManager = $jobProfileManager;
    }

    public function getAll(Request $request)
    {
        $jobProfiles = $this->jobProfileManager->getJobProfilesForUser($this->getUserId($request));

        return $this->createResponse(['jobProfiles' => $jobProfiles], Response::HTTP_OK);
    }

    public function get(Request $request, int $jobProfileId)
    {
        try {
            $jobProfile = $this->jobProfileManager->getJobProfileForUser($jobProfileId, $this->getUserId($request));
        } catch (ModelNotFoundException $exception) {
            return $this->resourceNotAccessible();
        }
        return $this->createResponse(['jobProfile' => $jobProfile->toArray()], Response::HTTP_OK);
    }

    public function post(Request $request): JsonResponse
    {
        $this->validate($request, self::POST_VALIDATION);

        try {
            $jobProfile = $this->jobProfileManager->createJobProfileForUser(
                $request->only(array_keys(self::POST_VALIDATION)),
                $this->getUserId($request)
            );
        } catch (ModelNotFoundException $exception) {
            return $this->resourceNotAccessible();
        }
        return $this->createResponse($jobProfile->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUserId(Request $request)
    {
        return $request->attributes->get('user')[User::ID];
    }
}
