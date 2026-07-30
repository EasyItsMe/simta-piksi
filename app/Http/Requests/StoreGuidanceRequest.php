<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuidanceRequest extends FormRequest
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
            'thesis_id' => ['required', 'exists:theses,id'],
            'date' => ['required', 'date'],
            'summary' => ['required', 'string'],
            'attachment_file' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
