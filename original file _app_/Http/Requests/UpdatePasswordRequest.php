<?php

namespace App\Http\Requests;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()->check()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', new MatchOldPassword()],
            'password' => ['required', 'confirmed', 'different:current_password', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Password sekarang tidak boleh kosong',
            'password.required' => 'Password baru tidak boleh kosong',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.different' => 'Password baru tidak boleh sama dengan password sekarang',
            'password.min' => 'Password minimal 8 karakter',
        ];
    }
}
