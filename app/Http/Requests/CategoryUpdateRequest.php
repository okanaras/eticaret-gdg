<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'short_description' => ['sometimes', 'nullable', 'max:63'],
            'description' => ['sometimes', 'nullable', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'max:255', 'unique:categories,slug, ' . $this->category->id], // kullani eger ki editte slug degistirmediyse buradaki unique kontrolune takilir bu yuzden en sonda this category id ekledik
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ad alani zorunludur.',
            'short_description.max' => 'Kisa aciklama max 64 karakter olabilir.'
        ];
    }

    // verileri requestteyken kontrol etmek icin asagidaki fonk kullanmak gerek
    public function prepareForValidation()
    {
        // dd(request()->slug, $this->slug); buradaki iki kullanim da ayni sey thisslug dedigimiz sey aslinda rq teki slug


        // bu sinif icerisnde gelen slug null degilse eger requestten gelen istekle merge et
        if (!is_null($this->slug)) {
            $this->merge(['slug' => Str::slug($this->slug)]);
        }
    }
}