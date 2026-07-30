<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSidangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pengajuan_judul_id' => 'required|exists:pengajuan_judul,id|unique:sidang,pengajuan_judul_id',
            'naskah_final' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'surat_persetujuan' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
