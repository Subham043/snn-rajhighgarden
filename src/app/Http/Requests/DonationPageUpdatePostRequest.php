<?php

namespace App\Http\Requests;

use App\Http\Requests\DonationPageCreatePostRequest;
use App\Http\Services\DecryptService;

class DonationPageUpdatePostRequest extends DonationPageCreatePostRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif',
            'image_alt' => 'nullable|string',
            'image_title' => 'nullable|string',
            'donation_title' => 'required|string',
            'slug' => 'required|string|alpha_dash:ascii|unique:donation_pages,slug,'.(new DecryptService)->decryptId($this->route('id')),
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
}
