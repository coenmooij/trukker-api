<?php

namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends Controller
{
    const RESOURCE_NOT_ACCESSIBLE = 'Resource not accessible';

    protected function createResponse($payload, $statusCode)
    {
        return response()->json($payload, $statusCode);
    }

    protected function resourceNotAccessible()
    {
        return $this->createResponse(['message' => self::RESOURCE_NOT_ACCESSIBLE], Response::HTTP_FORBIDDEN);
    }

    protected function getUserId(Request $request)
    {
        return $request->attributes->get('user')[User::ID];
    }
}
