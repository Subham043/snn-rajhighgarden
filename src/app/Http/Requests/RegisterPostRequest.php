<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Validation\Rules\Enum;

class RegisterPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:10|unique:users',
            'is_volunteer' => 'required|boolean',
            'gender' => ['required', new Enum(GenderEnum::class)],
            'password' => ['required',
                'string',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
            'confirm_password' => 'string|min:6|required_with:password|same:password',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('first_name', 'last_name', 'email', 'phone', 'password', 'gender', 'is_volunteer');
        $request['password'] = Hash::make($this->password);
        $request['otp'] = rand(1000,9999);
        if($request['is_volunteer']){
            $request['userType'] = RoleEnum::VOLUNTEER->label();
        }
        $this->replace(Purify::clean($request));
    }
}
