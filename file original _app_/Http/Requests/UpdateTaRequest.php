<?php

namespace App\Http\Requests;

use App\Traits\OpenModalValidation;

class UpdateTaRequest extends UpdateJadwalRequest
{
    use OpenModalValidation;

    protected string $modalName = 'edit';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('jadwal-ta.update')) {
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
                'nama_mahasiswa' => ['required'],
                'nim' => ['required'],
                'dosen_pembimbing_1' => ['required'],
                'dosen_pembimbing_2' => ['required'],
                'judul' => ['required'],
                'dosen_penguji_1' => ['required'],
                'dosen_penguji_2' => ['required'],
                'tautan' => ['required'],
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
            'tipe.required' => 'Tipe Sidang wajib diisi.',
            'nama_mahasiswa.required' => 'Nama mahasiswa wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'dosen_pembimbing_1.required' => 'Dosen pembimbing 1 wajib diisi.',
            'dosen_pembimbing_2.required' => 'Dosen pembimbing 2 wajib diisi.',
            'judul.required' => 'Judul wajib diisi.',
            'dosen_penguji_1.required' => 'Dosen penguji 1 wajib diisi.',
            'dosen_penguji_2.required' => 'Dosen penguji 2 wajib diisi.',
        ];
    }
}
