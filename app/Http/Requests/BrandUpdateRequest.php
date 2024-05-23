<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class BrandUpdateRequest extends FormRequest
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
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'max:255', 'unique:brands,slug,' . $this->brand->id],
            'logo' => ['sometimes', 'nullable', 'mimes:jpeg,jpf,webp,png', 'max:2048'],
            'order' => ['sometimes', 'nullable', 'integer'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Ad alani zorunludur.',
        ];
    }

    public function prepareForValidation()
    {
        if (!is_null($this->slug)) {
            $this->merge(['slug' => Str::slug($this->slug)]);
        }
    }
}