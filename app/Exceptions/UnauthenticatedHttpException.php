<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthenticatedHttpException extends HttpException
{
    public function __construct(string $message = 'Login invalido.', int $statusCode = 401)
    {
        parent::__construct($statusCode, $message);
    }
}
