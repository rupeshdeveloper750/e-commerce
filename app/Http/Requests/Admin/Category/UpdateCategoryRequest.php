<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [

            'parent_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($category),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

        ];
    }

    /**
     * Custom Error Messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Category name is required.',

            'slug.required' => 'Category slug is required.',

            'slug.unique' => 'This slug already exists.',

            'image.image' => 'Please upload a valid image.',

            'image.mimes' => 'Image must be jpg, jpeg, png or webp.',

            'image.max' => 'Image size must not exceed 2 MB.',

            'parent_id.exists' => 'Selected parent category is invalid.',

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'parent_id' => 'Parent Category',

            'sort_order' => 'Sort Order',

            'meta_title' => 'Meta Title',

            'meta_description' => 'Meta Description',

        ];
    }
}