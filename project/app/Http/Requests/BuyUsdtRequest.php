<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyUsdtRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sum' => 'required|numeric',
            'course' => 'required|numeric'
        ];
    }
}
