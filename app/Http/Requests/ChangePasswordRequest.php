<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'verification_code'          => ['required', 'exists:users,verification_code'],
            'password'      => ['required', 'string', 'min:6', 'max:30'],
            'device_token'  => ['sometimes', 'nullable', 'string']
        ];
    }

    public function messages(): array
    {
        if(request()->is('api/*')) {
            return [
                'verification_code.required'    => 'code_required',
                'verification_code.exists'      => 'code_not_correct',
                'password.required'     => 'password_required',
                'password.string'       => 'password_format_not_valid',
                'password.min'          => 'password_min_6',
                'password.max'          => 'password_max_30',
                'device_token.string'   => 'device_token_format_not_valid'
            ];
        }

        return [];
    }
}
