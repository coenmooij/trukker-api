<?php

namespace App\Http\Controllers;

class AbstractController extends Controller
{
    protected function createResponse($payload, $statusCode)
    {
        return response()->json($payload, $statusCode);
    }
}
