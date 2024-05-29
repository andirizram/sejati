<?php

namespace App\Http\Requests;

use App\Traits\OpenModalValidation;

class UpdateTpbRequest extends UpdateJadwalRequest
{
    use OpenModalValidation;

    protected string $modalName = 'edit';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('jadwal-tpb.update')) {
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
            'tipe' => ['required'],
            'mata_kuliah' => ['required'],
            'sks' => ['required', 'numeric'],
            'semester' => ['required', 'numeric'],
            'dosen' => ['required'],
            'deskripsi' => ['nullable'],
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipe.required' => 'Kode Mata Kuliah wajib diisi',
            'mata_kuliah.required' => 'Nama Mata Kuliah wajib diisi',
            'sks.required' => 'SKS wajib diisi.',
            'sks.numeric' => 'SKS harus berupa angka.',
            'sks.between' => 'SKS harus antara 1 dan 4.',
            'semester.required' => 'Semester wajib diisi',
            'semester.numeric' => 'Semester harus berupa angka',
            'dosen.required' => 'Dosen wajib diisi',
        ];
    }
}
