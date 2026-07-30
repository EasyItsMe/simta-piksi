<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSidangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_penguji_1' => 'required|string|max:255',
            'nama_penguji_2' => 'nullable|string|max:255',
            'tanggal_sidang' => 'required|date',
            'ruangan' => 'required|string|max:255',
            'status_lulus' => 'required|in:terjadwal,selesai,revisi',
            'nilai_kerapihan' => 'nullable|numeric|min:0|max:100',
            'nilai_penguasaan_materi' => 'nullable|numeric|min:0|max:100',
            'nilai_presentasi' => 'nullable|numeric|min:0|max:100',
            'catatan_revisi' => 'nullable|string',
        ];
    }
}
