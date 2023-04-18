<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationPageCollection extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => Crypt::encryptString($this->id),
            'donation_title' => $this->donation_title,
            'image' => $this->image_path,
            'image_alt' => $this->image_alt,
            'image_title' => $this->image_title,
            'slug' => $this->slug,
            'funds_required' => $this->funds_required,
            'fund_required_within' => $this->fund_required_within,
            'campaigner_name' => $this->campaigner_name,
            'campaigner_funds_collected' => $this->campaigner_funds_collected,
            'beneficiary_name' => $this->beneficiary_name,
            'beneficiary_relationship_with_campaigner' => $this->beneficiary_relationship_with_campaigner,
            'beneficiary_funds_collected' => $this->beneficiary_funds_collected,
            'beneficiary_bank_name' => $this->beneficiary_bank_name,
            'beneficiary_bank_account_number' => $this->beneficiary_bank_account_number,
            'beneficiary_bank_ifsc_code' => $this->beneficiary_bank_ifsc_code,
            'beneficiary_upi_id' => $this->beneficiary_upi_id,
            'donation_detail' => $this->donation_detail,
            'terms_condition' => $this->terms_condition,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
