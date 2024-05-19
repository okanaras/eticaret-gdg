<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'short_description' => ['sometimes', 'nullable', 'max:63'], // sometimes ve nullable ile bazen gelebiliri ekleyip idger kosullari kontrol edebiliriz.
            'description' => ['sometimes', 'nullable', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'max:255', 'unique:categories,slug'],
        ];
    }

    // mesajlari istedigimiz sekilde asagidaki gibi yonlendirebiliriz.
    public function messages()
    {
        return [
            'name.required' => 'Ad alani zorunludur.',
            'short_description.max' => 'Kisa aciklama max 64 karakter olabilir.'
        ];
    }
}