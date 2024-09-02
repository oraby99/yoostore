<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'code'          => ['required', 'exists:users,verification_code'],
            'device_token'  => ['sometimes', 'nullable', 'string']
        ];
    }

    public function messages(): array
    {
        if(request()->is('api/*')) {
            return [
                'code.required'         => 'code_required',
                'code.exists'           => 'code_not_correct',
                'device_token.string'   => 'device_token_format_not_valid'
            ];
        }

        return [];
    }
}
