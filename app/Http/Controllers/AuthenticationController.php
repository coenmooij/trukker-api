<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController
{
    public function login(Request $request)
    {
        var_dump($request->request->all());
    }

    public function register(Request $request)
    {
        var_dump($request->request->all());
    }

    public function logout(Request $request)
    {
        var_dump($request->request->all());
    }

    public function forgotPassword(Request $request)
    {
        var_dump($request->request->all());
    }

    public function resetPassword(Request $request)
    {
        var_dump($request->request->all());
    }
}
