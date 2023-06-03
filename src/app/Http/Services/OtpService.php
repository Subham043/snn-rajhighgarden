<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class OtpService
{

    public function sendOtp(Int $phone, Int $otp): void
    {
        Http::get('https://sms.sendmsg.in/smpp?username='.env('SMS_USERNAME').'&password='.env('SMS_PASSWORD').'&from='.env('SMS_FROM').'&to=91'.$phone.'&text='.$otp.' is your One Time Password - OTP for inquiry on the website. Please do not share this with anyone. Thanks %26 regards, Team SNN Properties LLP.&urlshortening=1');
    }

}
