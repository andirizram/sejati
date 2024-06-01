<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Traits\OpenModalValidation;


class StoreUserRequest extends FormRequest
{
    use OpenModalValidation;

    protected string $modalName = 'create-user';

    protected $redirectRoute = "pengelolaan-akun";

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
            'email' => ['required', 'email', 'unique:users'],
            'name' => ['required'],
            'role' => ['required'],
            'password' => ['required', 'min:8']
        ];
    }

}
