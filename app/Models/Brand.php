<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'slug',
    'description',
    'image',
    'status',
    'sort_order',
    'is_featured',
])]
class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
