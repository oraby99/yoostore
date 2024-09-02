<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'         => ['required', 'email'],
            'password'      => ['required'],
            'device_token'  => ['sometimes', 'nullable', 'string']
        ];
    }

    public function messages(): array
    {
        if(request()->is('api/*')) {
            return [
                'email.required'        => 'email_required',
                'email.email'           => 'email_format_not_valid',
                'password.required'     => 'password_required',
                'device_token.string'   => 'device_token_format_not_valid'
            ];
        }

        return [];
    }
}
