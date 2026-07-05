<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'string', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'cart_value' => ['nullable', 'numeric', 'min:0'],
            'expiry_date' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', 'boolean'],
        ];
    }
}
