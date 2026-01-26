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
            'jenis_karya_id' => ['required', 'exists:jenis_karyas,id'],
            'file_type' => ['required', 'in:pdf,video'],
            'creator_name' => ['required', 'string', 'max:255'],
            'creator_whatsapp' => ['required', 'string', 'regex:/^0[0-9]{8,13}$/'],
            'creator_country_code' => ['required', 'string', 'max:5'],
        ];

        // Conditional validation based on file type
        /** @var string|null $fileType */
        $fileType = request('file_type');
        if ($fileType === 'pdf') {
            // PDF requires file upload
            $rules['document'] = ['required', 'file', 'max:20480']; // 20MB
        } elseif ($fileType === 'video') {
            // Video only requires link, no file upload
            $rules['video_link'] = ['required', 'url'];
        }

        return $rules;
    }

    public function messages(): array
    {
        /** @var string|null $fileType */
        $fileType = request('file_type');
        
        return [
            'title.required' => 'Judul karya wajib diisi.',
            'title.max' => 'Judul karya maksimal 255 karakter.',
            'categories.required' => 'Kategori pengajuan wajib dipilih.',
            'categories.in' => 'Kategori pengajuan tidak valid.',
            'jenis_karya_id.required' => 'Jenis karya wajib dipilih.',
            'jenis_karya_id.exists' => 'Jenis karya yang dipilih tidak valid.',
            'file_type.required' => 'Jenis file wajib dipilih.',
            'file_type.in' => 'Jenis file tidak valid.',
            'creator_name.required' => 'Nama pencipta pertama wajib diisi.',
            'creator_name.max' => 'Nama pencipta pertama maksimal 255 karakter.',
            'creator_whatsapp.required' => 'Nomor WhatsApp pencipta pertama wajib diisi.',
            'creator_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 0xxxxxxxx.',
            'creator_country_code.required' => 'Kode negara wajib dipilih.',
            'creator_country_code.max' => 'Kode negara tidak valid.',
            'document.required' => 'File PDF wajib diunggah.',
            'document.file' => 'Pastikan Anda mengunggah file yang valid.',
            'document.mimes' => 'Hanya file PDF yang diperbolehkan.',
            'document.max' => 'Ukuran file PDF terlalu besar. Maksimal 20MB.',
            'video_link.required' => 'Link video wajib diisi untuk jenis file video.',
            'video_link.url' => 'Link video harus berupa URL yang valid (misal: https://drive.google.com/... atau https://youtube.com/...).',
        ];
    }
}