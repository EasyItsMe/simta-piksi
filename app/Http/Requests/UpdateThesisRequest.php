<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateThesisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];
        if (auth()->user()->isAdmin()) {
            $rules['status'] = ['required', 'in:pending,approved,rejected,in_progress,completed'];
            $rules['pembimbing_1'] = ['nullable', 'exists:users,id'];
            $rules['pembimbing_2'] = ['nullable', 'exists:users,id', 'different:pembimbing_1'];
        }
        if (auth()->user()->isMahasiswa()) {
            $rules['title'] = ['required', 'string', 'max:255'];
            $rules['abstract'] = ['nullable', 'string'];
            $rules['proposal_file'] = ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'];
        }
        return $rules;
    }
}
