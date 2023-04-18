<?php

namespace App\Http\Requests;
use Stevebauman\Purify\Facades\Purify;

class AdminLoginPostRequest extends LoginPostRequest
{
    protected function passedValidation()
    {
        $request = $this->safe()->only('email', 'password');
        $request['userType'] = 1;
        $this->replace(Purify::clean($request));
    }
}
