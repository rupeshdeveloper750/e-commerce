<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id ?? $this->route('brand');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug,' . $brandId],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
        ];
    }
}
