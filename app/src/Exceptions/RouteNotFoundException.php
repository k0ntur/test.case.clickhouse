<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message = 'Requested URI not found';
}