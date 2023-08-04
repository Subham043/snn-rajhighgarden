<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class OtpService
{

    public function sendOtp(Int $phone, Int $otp): void
    {
        Http::get('https://sms.sendmsg.in/smpp?username='.config('app.sms_username').'&password='.config('app.sms_password').'&from='.config('app.sms_from').'&to=91'.$phone.'&text='.$otp.' is your One Time Password - OTP for inquiry on the website. Please do not share this with anyone. Thanks %26 regards, Team SNN Properties LLP.&urlshortening=1');
    }

}
