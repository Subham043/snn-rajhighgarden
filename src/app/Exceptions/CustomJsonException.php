<?php

namespace App\Exceptions;

use Exception;

class CustomJsonException extends Exception
{
    protected $status = 'error';
    protected $message = "Oops! Some error occured!";
    protected $error_code = "AUTH_ERROR_0";
    public function __construct($message, $error_code)
    {
        parent::__construct($message, $error_code);
        $this->error_code = $error_code;
        $this->message = $message;
    }

    public function showCustomErrorMessage()
    {
        return $this->message;
    }

    public function showCustomErrorCode()
    {
        return $this->error_code;
    }

    public function showCustomErrorStatus()
    {
        return $this->status;
    }

}
