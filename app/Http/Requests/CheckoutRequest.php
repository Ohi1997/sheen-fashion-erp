<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasVerifiedEmail() ?? false;
    }

    public function rules(): array
    {
        return [
            'address' => ['required', 'array'],
            'address.line1' => ['required', 'string'],
            'address.city' => ['required', 'string'],
            'address.country' => ['required', 'string'],
            'payment.payment_method' => ['required', 'string'],
        ];
    }
}
