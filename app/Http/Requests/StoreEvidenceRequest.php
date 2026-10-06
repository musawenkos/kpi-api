<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file'          => ['required', 'file', 'max:5120', 'mimes:pdf,png,jpg,jpeg'],  // max is in KB
            'evidence_name' => ['nullable', 'string', 'max:150'],
        ];
    }
}
