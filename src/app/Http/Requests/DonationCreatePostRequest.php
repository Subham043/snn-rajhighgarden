<?php

namespace App\Http\Requests;

use App\Http\Services\RazorpayService;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;
use Uuid;

class DonationCreatePostRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|integer|digits:10',
            'amount' => 'required|integer|digits_between:1,10',
            'message' => 'required',
            'donation_page_id' => 'required|numeric|exists:donation_pages,id',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('name', 'email', 'phone', 'amount', 'message', 'donation_page_id');
        $request['receipt'] = Uuid::generate(4)->string;
        $request['order_id'] = (new RazorpayService)->generate_order_id($request['receipt'], $request['amount']);
        $this->replace(Purify::clean($request));
    }
}
