<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DecryptService;
use App\Http\Services\OtpService;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{

    public function index(Request $request){

        $rules = array(
            'name' => 'required|string|max:255',
            'phone' => 'nullable|integer|digits:10',
            'email' => 'nullable|string|email|max:255',
            'page_url' => 'nullable|string',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(["form_error"=>$validator->errors()], 400);
        }
        try {
            //code...
            $data = Enquiry::create([
                ...$request->validate(),
                'otp' => rand(1000,9999),
                'ip_address' => $request->ip(),
                'is_verified' => false,
            ]);
            (new OtpService)->sendOtp($data->phone, $data->otp);
            $uuid = (new DecryptService)->encryptId($data->id);
            return response()->json(["uuid" => $uuid, "link" => route('enquiry.verifyOtp', $uuid)], 201);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['message'=>'Oops! Something went wrong. Please try again!'], 400);
        }

    }

    public function resendOtp(Request $request){
        $rules = array(
            'uuid' => 'required|string',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(["form_error"=>$validator->errors()], 400);
        }

        try {
            //code...
            $id = (new DecryptService)->decryptId($request->uuid);
            $data = Enquiry::findOrFail($id);
            $data->update([
                'otp' => rand(1000,9999),
            ]);
            (new OtpService)->sendOtp($data->phone, $data->otp);
            return response()->json(["message" => "Otp sent successfully."], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Invalid uuid"], 400);
        }
    }

    public function verifyOtp(Request $request, $uuid){
        $rules = array(
            'otp' => 'required|numeric|digits:4',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(["form_error"=>$validator->errors()], 400);
        }

        try {
            //code...
            $id = (new DecryptService)->decryptId($uuid);
            $data = Enquiry::findOrFail($id);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => "Invalid uuid"], 400);
        }

        try {
            //code...
            if($request->otp===$data->otp){
                $data->update(
                    [
                        'otp' => rand(1000,9999),
                        'is_verified' => true,
                    ]
                );
                return response()->json(["message" => "Enquiry recieved successfully."], 201);
            }
            return response()->json(["message" => "Invalid OTP. Please try again"], 400);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Something went wrong. Please try again"], 400);
        }

    }

}
