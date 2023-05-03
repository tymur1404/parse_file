<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'nullable|integer',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'age' => 'nullable|integer',
            'age_range' => 'nullable|regex:/^\d+,\d+$/',
        ];
    }
}
