<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon');
        if (is_object($couponId)) {
            $couponId = $couponId->id;
        }

        return [
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $couponId],
            'type' => ['required', 'string', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'cart_value' => ['nullable', 'numeric', 'min:0'],
            'expiry_date' => ['required', 'date'],
            'status' => ['required', 'boolean'],
        ];
    }
}
