<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'code',
    'type',
    'value',
    'cart_value',
    'expiry_date',
    'status',
])]
class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'expiry_date' => 'date',
            'value' => 'decimal:2',
            'cart_value' => 'decimal:2',
        ];
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid(float $cartTotal = 0): bool
    {
        if (!$this->status) {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->endOfDay()->isPast()) {
            return false;
        }

        if ($cartTotal < (float) $this->cart_value) {
            return false;
        }

        return true;
    }
}
