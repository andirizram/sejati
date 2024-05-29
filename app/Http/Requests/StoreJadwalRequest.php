<?php

namespace App\Http\Requests;

use App\Traits\OpenModalValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class StoreJadwalRequest extends FormRequest
{
    use OpenModalValidation;

    protected string $modalName = 'create';

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
            'tanggal' => [new RequiredIf(!isset($this->pengulangan))],
            'hari' => [new RequiredIf(isset($this->pengulangan))],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'ruangan' => ['required']
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
            'tanggal.required_if' => 'Tanggal wajib diisi ketika pengulangan tidak diaktifkan.',
            'hari.required_if' => 'Hari wajib diisi ketika pengulangan diaktifkan.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date_format' => 'Format waktu mulai harus HH:MM.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.date_format' => 'Format waktu selesai harus HH:MM.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'ruangan.required' => 'Ruangan wajib diisi.',
        ];
    }
}
