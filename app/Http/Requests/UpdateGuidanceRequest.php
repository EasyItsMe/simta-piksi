<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGuidanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        if (auth()->user()->isDosen()) {
            return [
                'status' => ['required', 'in:submitted,reviewed'],
                'feedback' => ['nullable', 'string'],
            ];
        }
        return [
            'date' => ['required', 'date'],
            'summary' => ['required', 'string'],
            'attachment_file' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
