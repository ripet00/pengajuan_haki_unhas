<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // request ini untuk user yang submit, bukan admin
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'in:Universitas,Umum'],
            'document' => ['required', 'file', 'mimes:pdf', 'max:20480'], // KB = 20MB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul karya wajib diisi.',
            'title.max' => 'Judul karya maksimal 255 karakter.',
            'categories.required' => 'Kategori pengajuan wajib dipilih.',
            'categories.in' => 'Kategori pengajuan tidak valid.',
            'document.required' => 'Dokumen wajib diunggah.',
            'document.file' => 'Pastikan Anda mengunggah file yang valid.',
            'document.mimes' => 'Hanya file PDF yang diperbolehkan.',
            'document.max' => 'Ukuran file maksimal adalah 20MB.',
        ];
    }
}