<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;  // ← TAMBAHKAN INI

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Email atau username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];
    }

    // TAMBAHKAN TYPE HINT Validator
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $login = $this->input('login');
            
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                if (!str_ends_with($login, '@gmail.com')) {
                    $validator->errors()->add('login', 'Email harus menggunakan domain @gmail.com');
                }
            }
        });
    }
}