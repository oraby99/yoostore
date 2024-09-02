<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LanguageEnum;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:50'],
            'email'         => ['required', 'email'],
            'phone'         => ['required', 'string'],
            'country_code'  => ['required', 'string'],
            'password'      => ['required', 'string', 'min:6', 'max:30'],
        ];
    }

    public function messages(): array
    {
        if(request()->is('api/*')) {
            return [
                'name.required'         => 'name_required',
                'name.string'           => 'name_format_not_valid',
                'country_code.required' => 'country_code_required',
                'country_code.string'   => 'country_code_format_not_valid',
                'name.min'              => 'name_min_3',
                'name.max'              => 'name_max_50',
                'email.required'        => 'email_required',
                'email.email'           => 'email_format_not_valid',
                'email.unique'          => 'email_used_before',
                'phone.required'        => 'phone_required',
                'phone.string'          => 'phone_format_not_valid',
                'phone.unique'          => 'phone_used_before',
                'password.required'     => 'password_required',
                'password.string'       => 'password_format_not_valid',
                'password.min'          => 'password_min_6',
                'password.max'          => 'password_max_30',
            ];
        }

        return [];
    }
}
