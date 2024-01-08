<?php

namespace App\Http\Requests\Game\Market;

use Illuminate\Foundation\Http\FormRequest;

class MarketPurchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:0', 'max:4'],
        ];
    }
}
