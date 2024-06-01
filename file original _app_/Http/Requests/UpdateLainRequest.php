<?php

namespace App\Http\Requests;

use App\Traits\OpenModalValidation;

class UpdateLainRequest extends UpdateJadwalRequest
{
    use OpenModalValidation;

    protected string $modalName = 'edit';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('jadwal-lain.update')) {
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
        return parent::rules() + [
                'tipe' => ['required'],
                'dosen' => ['required'],
                'deskripsi' => ['required'],
            ];
    }

    public function messages(): array
    {
        return [
            'tipe.required' => 'Jenis seminar wajib diisi',
            'dosen.required' => 'Dosen wajib diisi',
        ];
    }
}
