<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => ['required', 'string', 'min:2', 'max:255'],
            "price" => ['required', 'numeric', 'min:1'],
            "type_id" => ['required', 'exists:product_types,id'],
            "brand_id" => ['required', 'exists:brands,id'],
            "category_id" => ['required', 'exists:categories,id'],
            "short_description" => ['sometimes', 'nullable', 'string', 'max:255'],
            "description" => ['sometimes', 'nullable', 'string'],
            "variant" => ['required', 'array', 'min:1'],
            "variant.*.name" => ['sometimes', 'nullable', 'string', 'max:255'],
            "variant.*.variant_name" => ['required', 'string', 'min:1', 'max:255'],
            "variant.*.additional_price" => ['sometimes', 'nullable', 'numeric', 'min:1'],
            "variant.*.extra_description" => ['sometimes', 'nullable', 'string', 'min:1'],
            "variant.*.publish_date" => ['sometimes', 'nullable', 'date', 'min:1'],
            "variant.*.featured_image" => ['required', 'string', 'min:1'],
            "variant.*.image" => ['required', 'string', 'min:1'],
            "variant.*.size" => ['required', 'array', 'min:1'],
            "variant.*.size.*" => ['required', 'string', 'min:1'],
            "variant.*.stock" => ['required', 'array', 'min:1'],
            "variant.*.stock.*" => ['required', 'integer', 'min:1'],
        ];

        foreach ($this->variant as $variantID => $variant) {
            // $rules["variant.$variantID.slug"] = ['required', 'string', 'min:1', 'max:255', Rule::unique('products, slug')->ignore($variantID)];
            $rules["variant.$variantID.slug"] = ['required', 'string', 'min:1', 'max:255', 'unique:products,slug,' . $variantID];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $variants = $this->input('variant', []);

        foreach ($variants as &$variant) {
            $variant['slug'] = Str::slug($variant['slug']);
        }

        $this->merge(['variant' => $variants]);
    }
}