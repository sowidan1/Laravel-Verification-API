<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => [
                'required',
                'digits:11',
                'numeric',
            ],
            'password' => 'required|string|min:8|regex:/^(?=.*[!#$@])(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'password is required',
            'password.string' => 'password must be a string',
            'password.min' => 'password min length is 8',
            'password.regex' => 'password must contain at least one special character, one letter and one number',
            'phone.required' => 'phone is required',
            'phone.string' => 'phone must be a string',
            'phone.digits' => 'phone must be 11 digits',
            'phone.numeric' => 'phone must be a number',
        ];
    }
}
