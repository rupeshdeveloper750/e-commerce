<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'product_id',
    'image_path',
    'sort_order',
    'is_featured',
])]
class ProductImage extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
