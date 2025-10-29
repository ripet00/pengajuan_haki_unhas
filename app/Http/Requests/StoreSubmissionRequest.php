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
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'in:Universitas,Umum'],
            'file_type' => ['required', 'in:pdf,video'],
            'creator_name' => ['required', 'string', 'max:255'],
            'creator_whatsapp' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{9,13}$/'],
        ];

        // Conditional validation based on file type
        /** @var string|null $fileType */
        $fileType = request('file_type');
        if ($fileType === 'pdf') {
            $rules['document'] = ['required', 'file', 'mimes:pdf', 'max:20480']; // 20MB
        } elseif ($fileType === 'video') {
            $rules['document'] = ['required', 'file', 'mimes:mp4', 'max:20480']; // 20MB for video
            $rules['youtube_link'] = ['nullable', 'url', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/'];
        }

        return $rules;
    }

    public function messages(): array
    {
        /** @var string|null $fileType */
        $fileType = request('file_type');
        $expectedFormat = $fileType === 'video' ? 'MP4' : 'PDF';
        
        return [
            'title.required' => 'Judul karya wajib diisi.',
            'title.max' => 'Judul karya maksimal 255 karakter.',
            'categories.required' => 'Kategori pengajuan wajib dipilih.',
            'categories.in' => 'Kategori pengajuan tidak valid.',
            'file_type.required' => 'Jenis file wajib dipilih.',
            'file_type.in' => 'Jenis file tidak valid.',
            'creator_name.required' => 'Nama pencipta pertama wajib diisi.',
            'creator_name.max' => 'Nama pencipta pertama maksimal 255 karakter.',
            'creator_whatsapp.required' => 'Nomor WhatsApp pencipta pertama wajib diisi.',
            'creator_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 081234567890 atau +6281234567890.',
            'document.required' => 'File wajib diunggah.',
            'document.file' => 'Pastikan Anda mengunggah file yang valid.',
            'document.mimes' => "Hanya file {$expectedFormat} yang diperbolehkan untuk jenis file yang dipilih.",
            'document.max' => 'Ukuran file terlalu besar. Maksimal 20MB untuk PDF atau video MP4.',
            'youtube_link.url' => 'Link YouTube harus berupa URL yang valid.',
            'youtube_link.regex' => 'Link harus berupa URL YouTube yang valid.',
        ];
    }
}