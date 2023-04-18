<?php

namespace App\Http\Requests;

use App\Enums\PaymentStatusEnum;
use App\Http\Services\RazorpayService;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;
use Uuid;

class DonationVerifyPostRequest extends FormRequest
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
            'razorpay_order_id' => 'required|string|exists:donations,order_id',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => ['required','string', function ($attribute, $value, $fail) {
                $request = $this->safe()->only('razorpay_order_id', 'razorpay_payment_id');
                $is_verified = (new RazorpayService)->verify_signature($request['razorpay_order_id'], $request['razorpay_payment_id'], $value);
                if(!$is_verified){
                    $fail('The '.$attribute.' entered is invalid therefore donation verification failed!.');
                }
            }],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('razorpay_order_id', 'razorpay_payment_id');
        $data = [];
        $data['order_id'] = $request['razorpay_order_id'];
        $data['payment_id'] = $request['razorpay_payment_id'];
        $data['status'] = PaymentStatusEnum::COMPLETED->label();
        $this->replace(Purify::clean($data));
    }
}
