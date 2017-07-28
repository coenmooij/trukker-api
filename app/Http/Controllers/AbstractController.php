<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class AbstractController extends Controller
{
    protected function createResponse($payload, $statusCode)
    {
        return response()->json($payload, $statusCode);
    }
}
