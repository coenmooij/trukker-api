<?php

namespace App\Http\Controllers;

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
}
