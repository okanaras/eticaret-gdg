<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;


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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => ['required', 'string', 'min:2', 'max:255'],
            "price" => ['required', 'numeric', 'min:1'],
            "type_id" => ['required', 'exists:product_types,id'],
            "brand_id" => ['required', 'exists:brands,id'],
            "category_id" => ['required', 'exists:categories,id'],
            "gender" => ['required', Rule::in(array_column(GenderEnum::cases(), 'value'))],
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
            if (isset($variant['variant_index'])) {
                $rules["variant.$variantID.slug"] = [
                    'required',
                    'string',
                    'min:1',
                    'max:255',
                    Rule::unique('products', 'slug')->ignore($variant['variant_index'], 'id')
                ];
            } else {
                $rules["variant.$variantID.slug"] = ['required', 'string', 'min:1', 'max:255', 'unique:products,slug'];
            }
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