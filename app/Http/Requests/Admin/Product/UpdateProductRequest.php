<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');
        if (is_object($productId)) {
            $productId = $productId->id;
        }

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $productId],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $productId],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            
            // Variants
            'variants' => ['nullable', 'array'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.quantity' => ['required', 'integer', 'min:0'],
            'variants.*.attribute_values' => ['nullable', 'array'],
            'variants.*.attribute_values.*' => ['exists:attribute_values,id'],
        ];
    }
}
