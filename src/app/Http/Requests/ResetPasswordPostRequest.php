<?php

namespace App\Http\Requests;

use App\Http\Services\DecryptService;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordPostRequest extends FormRequest
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
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
            'otp' => ['required','integer','digits:4', function ($attribute, $value, $fail) {
                $user_id = $this->route('user_id');
                $user = $this->userService->getById((new DecryptService)->decryptId($user_id));
                if($value!=$user->otp){
                    $fail('The '.$attribute.' entered is invalid.');
                }
            },],
            'confirm_password' => 'string|min:6|required_with:password|same:password',
            'password' => ['required',
                'string',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('otp', 'password');
        $request['password'] = Hash::make($this->password);
        $request['otp'] = rand(1000,9999);
        $this->replace(Purify::clean($request));
    }
}
