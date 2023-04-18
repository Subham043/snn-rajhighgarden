<?php

namespace App\Http\Requests;

use App\Http\Services\AuthService;
use App\Http\Services\DecryptService;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;

class OtpPostRequest extends FormRequest
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
                $user = $this->authService->getById(
                    (new DecryptService)->decryptId($user_id)
                );
                if($value!=$user->otp){
                    $fail('The '.$attribute.' entered is invalid.');
                }
            },]
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('otp');
        $this->replace(Purify::clean($request));
    }
}
