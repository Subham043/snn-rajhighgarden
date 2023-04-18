<?php

namespace App\Http\Requests;

use App\Http\Requests\BannerCreatePostRequest;

class BannerUpdatePostRequest extends BannerCreatePostRequest
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
            'alt' => 'nullable|string',
            'title' => 'nullable|string',
        ];
    }
}
