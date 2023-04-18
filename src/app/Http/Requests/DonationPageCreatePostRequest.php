<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class DonationPageCreatePostRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,avif',
            'image_alt' => 'nullable|string',
            'image_title' => 'nullable|string',
            'donation_title' => 'required|string',
            'slug' => 'required|string|alpha_dash:ascii|unique:donation_pages',
            'funds_required' => 'required|numeric',
            'fund_required_within' => 'required|date_format:"Y-m-d H:i:s"',
            'campaigner_name' => 'required|string',
            'campaigner_funds_collected' => 'required|numeric',
            'beneficiary_name' => 'required|string',
            'beneficiary_relationship_with_campaigner' => 'required|string',
            'beneficiary_funds_collected' => 'required|numeric',
            'beneficiary_bank_name' => 'required|string',
            'beneficiary_bank_account_number' => 'required|numeric',
            'beneficiary_bank_ifsc_code' => 'required|string',
            'beneficiary_upi_id' => 'required|string',
            'donation_detail' => 'required',
            'terms_condition' => 'required',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $request = $this->safe()->only('image',
        'image_alt',
        'image_title',
        'donation_title',
        'slug',
        'funds_required',
        'fund_required_within',
        'campaigner_name',
        'campaigner_funds_collected',
        'beneficiary_name',
        'beneficiary_relationship_with_campaigner',
        'beneficiary_funds_collected',
        'beneficiary_bank_name',
        'beneficiary_bank_account_number',
        'beneficiary_bank_ifsc_code',
        'beneficiary_upi_id',
        'donation_detail',
        'terms_condition');
        $request['user_id'] = Auth::user()->id;
        $this->replace(Purify::clean($request));
    }
}
