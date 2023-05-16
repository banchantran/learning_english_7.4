<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'bail|email|required',
            'full_name' => 'bail|required',
            'username' => [
                'bail',
                'required',
                'min:6',
                'regex:/^[a-zA-Z0-9]+$/u',
                Rule::unique('users')->where('del_flag', 0),
            ],
            'password' => 'bail|required|min:6|max:10',
            'verify_password' => 'bail|required|min:6|max:10|same:password',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => "Username should only contain letters and numbers. Ex: John123"
        ];
    }
}
