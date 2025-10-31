<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ResubmitSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'in:Universitas,Umum'],
            'file_type' => ['required', 'in:pdf,video'],
            'creator_name' => ['required', 'string', 'max:255'],
            'creator_whatsapp' => ['required', 'string', 'regex:/^0[0-9]{8,13}$/'],
            'creator_country_code' => ['required', 'string', 'max:5'],
        ];

        // More flexible file validation - let middleware handle the strict checking
        /** @var string|null $fileType */
        $fileType = request('file_type');
        if ($fileType === 'pdf') {
            // More lenient PDF validation - accept various PDF mime types
            $rules['document'] = ['required', 'file', 'max:20480']; // 20MB, let middleware check PDF
        } elseif ($fileType === 'video') {
            // More lenient video validation
            $rules['document'] = ['required', 'file', 'max:20480']; // 20MB, let middleware check MP4
            $rules['youtube_link'] = ['nullable', 'url', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/'];
        } else {
            // Default validation if file_type is not set properly
            $rules['document'] = ['required', 'file', 'max:20480'];
        }

        return $rules;
    }

    /**
     * Get the validation error messages.
     */
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
            'creator_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 0xxxxxxxx.',
            'creator_country_code.required' => 'Kode negara wajib dipilih.',
            'creator_country_code.max' => 'Kode negara tidak valid.',
            'document.required' => 'File wajib diunggah.',
            'document.file' => 'Pastikan Anda mengunggah file yang valid.',
            'document.max' => 'Ukuran file terlalu besar. Maksimal 20MB untuk PDF atau video MP4.',
            'youtube_link.url' => 'Link YouTube harus berupa URL yang valid.',
            'youtube_link.regex' => 'Link harus berupa URL YouTube yang valid.',
        ];
    }
}
