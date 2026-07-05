<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'category_id',
    'brand_id',
    'name',
    'slug',
    'sku',
    'short_description',
    'description',
    'price',
    'sale_price',
    'quantity',
    'status',
    'is_featured',
    'meta_title',
    'meta_description',
])]
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
