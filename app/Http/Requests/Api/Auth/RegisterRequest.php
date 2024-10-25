<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:2',
            'password' => 'required|string|min:8|regex:/^(?=.*[!#$@])(?=.*[a-zA-Z])(?=.*[0-9]).+$/|confirmed',
            'phone' => 'required|digits:11|numeric|unique:users,phone',
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
            'name.required' => 'name is required',
            'name.string' => 'name must be a string',
            'name.max' => 'name max length is 255',
            'name.min' => 'name min length is 2',
            'password.required' => 'password is required',
            'password.string' => 'password must be a string',
            'password.min' => 'password min length is 8',
            'password.regex' => 'password must contain at least one special character, one letter and one number',
            'phone.required' => 'phone is required',
            'phone.string' => 'phone must be a string',
            'phone.unique' => 'phone is already exists',
            'phone.max' => 'phone max length is 15',
        ];
    }
}
