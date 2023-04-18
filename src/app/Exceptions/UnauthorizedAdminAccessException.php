<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedAdminAccessException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}