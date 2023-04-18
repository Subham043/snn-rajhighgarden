<?php

namespace App\Exceptions;

use App\Enums\UserStatusEnum;
use Exception;
use App\Models\User;
use App\Jobs\SendVerificationEmailJob;
use Illuminate\Support\Facades\Crypt;

class UserAccessException extends Exception
{
    protected $data;
    protected $message = "Oops! you dont have the permission to access this!";
    protected $error_code = "AUTH_ERROR_0";
    public function __construct($data = null)
    {
        parent::__construct($data);

        $this->data = $data;
    }

    public function showCustomErrorMessage()
    {
        if($this->data->status==UserStatusEnum::VERIFICATION_PENDING->label()){
            $this->message = 'Oops! Please verify your email address.';
            $user = User::where('email', $this->data->email)->firstOrFail();
            //dispatch(new SendVerificationEmailJob($user));
        }
        if($this->data->status==UserStatusEnum::BLOCKED->label()){
            $this->message = 'Oops! Your account has been blocked by admin. Kindly contact us for further details!';
        }
        return $this->message;
    }

    public function showCustomErrorCode()
    {
        if($this->data->status==UserStatusEnum::VERIFICATION_PENDING->label()){
            $this->error_code = "AUTH_ERROR_0";
        }
        if($this->data->status==UserStatusEnum::BLOCKED->label()){
            $this->error_code = "AUTH_ERROR_2";
        }
        return $this->error_code;
    }

    public function showUserId()
    {
        return Crypt::encryptString($this->data->id);
    }
}
