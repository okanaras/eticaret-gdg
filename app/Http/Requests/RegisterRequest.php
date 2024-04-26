<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // authorize kismini yetkilendirme yapmayi saglar. Gelistirme asamasinda genellikle true yapilir
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:125'],
            'email' => ["required", 'email', 'unique:users', 'max:125'],
            // 'eposta' => ['required', 'email', 'unique:users,email'] eger field name ile tablodaki ad eslesmiyorsa , ile tablodaki email adi verilemsi gerek

            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->symbols()->numbers()],
            //->numbers()->uncompromised(5) 5 ksii max ayni parolayi kullanabilir
            // 'password' => ['required', 'min:6', 'confirmed', 'password:letters,numbers,symbols'], seklinde de kullanilabilinir uncompromised(5)
        ];
    }

    public function messages()
    {
        /**
         * return parent::messages();
         * .env app_locale deki dil i refarans alip hepsini dondurur. Ben tr dil paketini indirdim githubtan.
         * .env de tr yapip turkcelestirdim asagida sekildeki gibi de ozellestirilebilinir
         */
        return [
            'name.required' => 'Ad Soyad alani zorunludur.',
            'name.min' => 'Ad Soyad alani en az 2 karakter olmali.',
            'name.max' => 'Ad Soyad alani en fazla 125 karakter olmali.',
        ];
    }
}