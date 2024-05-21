<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
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
            'slug' => ['sometimes', 'nullable', 'max:255', 'unique:brands,slug'],
            'logo' => ['sometimes', 'nullable', 'mimes:jpeg,jpf,webp,png', 'max:2048'],
            'order' => ['sometimes', 'nullable', 'integer'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Ad alani zorunludur.',
            'short_description.max' => 'Kisa aciklama max 64 karakter olabilir.'
        ];
    }

    public function prepareForValidation()
    {
        if (!is_null($this->slug)) {
            $this->merge(['slug' => Str::slug($this->slug)]);
        }
    }
}