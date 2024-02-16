<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => "regex:/\b[^\d\W]+\b/|string|max:250",
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Название города может содержать только заглавные и строчные буквы, пробелы и знак дефис',
            'name.max' => 'Название города может содержать только до 250 символов в названии',
        ];
    }
}
