<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'order_number',
    'subtotal',
    'discount',
    'shipping',
    'total',
    'status',
    'payment_method',
    'payment_status',
    'first_name',
    'last_name',
    'email',
    'phone',
    'address',
    'city',
    'state',
    'zip_code',
    'notes',
])]
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
