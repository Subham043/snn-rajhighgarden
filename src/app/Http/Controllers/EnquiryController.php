<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
            $data = Enquiry::create($request->validated());
            return response()->json(['message'=>'Enquiry created successfully.'], 201);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['message'=>'Oops! Something went wrong. Please try again!'], 400);
        }

    }
}
