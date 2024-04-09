<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellCryptoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sell_course' => 'required|numeric',
            'sell_date' => 'required|date_format:Y-m-d'
        ];
    }
}
