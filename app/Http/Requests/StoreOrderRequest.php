<?php

namespace App\Http\Requests;

use App\Rules\QuantityIsNotOutOfRange;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'profile_id' => ['required', 'exists:profiles,id'],
            'service_id' => ['required', 'exists:services,service'],
            'quantity' => ['required', 'numeric', app(QuantityIsNotOutOfRange::class)],
        ];
    }
}
