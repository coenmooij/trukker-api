<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginFailedException extends HttpException
{
    const MESSAGE = 'Login Failed';

    public function __construct()
    {
        parent::__construct(401, self::MESSAGE);
    }
}
