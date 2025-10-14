<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // request ini untuk user yang submit, bukan admin
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'document' => ['required', 'file', 'mimes:pdf', 'max:20480'], // KB = 20MB
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Dokumen wajib diunggah.',
            'document.file' => 'Pastikan Anda mengunggah file yang valid.',
            'document.mimes' => 'Hanya file PDF yang diperbolehkan.',
            'document.max' => 'Ukuran file maksimal adalah 20MB.',
        ];
    }
}