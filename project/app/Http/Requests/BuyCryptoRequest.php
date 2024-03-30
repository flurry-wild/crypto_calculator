<?php

namespace App\Http\Requests;

use App\Services\BuyCryptoService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyCryptoRequest extends FormRequest
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
            'course' => 'required|numeric',
            'currency' => 'required', Rule::in(BuyCryptoService::CRYPTO_COINS),
            'purchase_date' => 'required|date_format:Y-m-d'
        ];
    }
}
