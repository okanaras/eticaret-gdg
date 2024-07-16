<?php

namespace App\Http\Requests;

use App\Enums\DiscountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
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
        $discountTypes = implode(',', array_map(fn ($case) => $case->value, DiscountTypeEnum::cases()));
        // dd($discountTypes);

        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'string', "in:{$discountTypes}"],
            // 'type' => ['required', 'string', Rule::in(array_column(DiscountTypeEnum::cases(), 'value'))], // bu sekil de kullanabilirdik map yerine
            'value' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ];
    }
}