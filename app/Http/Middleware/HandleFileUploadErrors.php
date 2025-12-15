<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class HandleFileUploadErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a file upload request
        if ($request->hasFile('document') || $request->hasFile('pdf_document')) {
            $fileField = $request->hasFile('document') ? 'document' : 'pdf_document';
            $file = $request->file($fileField);
            
            // Detect file type based on route or input
            $isPatenRoute = str_contains($request->path(), 'paten') || str_contains($request->url(), 'paten');
            $fileType = $request->input('file_type', $isPatenRoute ? 'docx' : 'pdf');
            
            // Debug logging
            Log::info('File upload middleware check', [
                'file_field' => $fileField,
                'is_paten_route' => $isPatenRoute,
                'file_type_from_request' => $fileType,
                'file_original_name' => $file ? $file->getClientOriginalName() : 'no file',
                'file_mime_type' => $file ? $file->getClientMimeType() : 'no file',
                'file_size' => $file ? $file->getSize() : 0,
                'request_path' => $request->path()
            ]);
            
            // Check file size before processing
            if ($file) {
                $fileSize = $file->getSize();
                // Set max size based on file type: 5MB for DOCX, 20MB for PDF
                $maxSize = ($fileType === 'docx') ? 5 * 1024 * 1024 : 20 * 1024 * 1024;
                $maxSizeLabel = ($fileType === 'docx') ? '5MB' : '20MB';
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    $fileTypeLabel = ($fileType === 'docx') ? 'DOCX' : strtoupper($fileType);
                    return back()->withErrors([
                        $fileField => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah {$maxSizeLabel} untuk {$fileTypeLabel}."
                    ])->withInput();
                }
                
                // Check file type based on user selection
                if ($fileType === 'pdf') {
                    // Check if file is actually a PDF - be more lenient with mime types
                    $validMimeTypes = [
                        'application/pdf',
                        'application/x-pdf',
                        'application/acrobat',
                        'applications/vnd.pdf',
                        'text/pdf',
                        'text/x-pdf'
                    ];
                    
                    $isValidMime = in_array($file->getClientMimeType(), $validMimeTypes);
                    $isValidExtension = str_ends_with(strtolower($file->getClientOriginalName()), '.pdf');
                    
                    if (!$isValidMime && !$isValidExtension) {
                        Log::warning('PDF validation failed', [
                            'file_mime' => $file->getClientMimeType(),
                            'file_name' => $file->getClientOriginalName(),
                            'valid_mime' => $isValidMime,
                            'valid_extension' => $isValidExtension
                        ]);
                        
                        return back()->withErrors([
                            $fileField => 'Hanya file PDF yang diperbolehkan untuk jenis file PDF. File yang diupload: ' . $file->getClientMimeType()
                        ])->withInput();
                    }
                } elseif ($fileType === 'docx') {
                    // Check if file is actually a DOCX (Word document)
                    $validMimeTypes = [
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/msword',
                        'application/vnd.ms-word',
                        'application/zip', // DOCX is essentially a ZIP file
                    ];
                    
                    $isValidMime = in_array($file->getClientMimeType(), $validMimeTypes);
                    $isValidExtension = str_ends_with(strtolower($file->getClientOriginalName()), '.docx');
                    
                    if (!$isValidMime && !$isValidExtension) {
                        Log::warning('DOCX validation failed', [
                            'file_mime' => $file->getClientMimeType(),
                            'file_name' => $file->getClientOriginalName(),
                            'valid_mime' => $isValidMime,
                            'valid_extension' => $isValidExtension
                        ]);
                        
                        return back()->withErrors([
                            $fileField => 'Hanya file DOCX (Microsoft Word) yang diperbolehkan untuk draft paten. File yang diupload: ' . $file->getClientMimeType()
                        ])->withInput();
                    }
                } elseif ($fileType === 'video') {
                    // Check if file is actually an MP4
                    $allowedMimes = ['video/mp4', 'video/mpeg4', 'application/mp4'];
                    $isValidMime = in_array($file->getClientMimeType(), $allowedMimes);
                    $isValidExtension = str_ends_with(strtolower($file->getClientOriginalName()), '.mp4');
                    
                    if (!$isValidMime && !$isValidExtension) {
                        Log::warning('MP4 validation failed', [
                            'file_mime' => $file->getClientMimeType(),
                            'file_name' => $file->getClientOriginalName(),
                            'valid_mime' => $isValidMime,
                            'valid_extension' => $isValidExtension
                        ]);
                        
                        return back()->withErrors([
                            $fileField => 'Hanya file MP4 yang diperbolehkan untuk jenis file video. File yang diupload: ' . $file->getClientMimeType()
                        ])->withInput();
                    }
                }
            }
        }

        try {
            return $next($request);
        } catch (\Exception $e) {
            // Log the error
            Log::error('File upload middleware error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error_trace' => $e->getTraceAsString()
            ]);

            // Check if it's a file upload related error
            if (str_contains($e->getMessage(), 'upload') || 
                str_contains($e->getMessage(), 'file') ||
                str_contains($e->getMessage(), 'memory') ||
                str_contains($e->getMessage(), 'size')) {
                
                $fileField = $request->hasFile('document') ? 'document' : 'pdf_document';
                $isPatenRoute = str_contains($request->path(), 'paten');
                $errorMsg = $isPatenRoute 
                    ? 'Terjadi kesalahan saat mengupload file. Pastikan file DOCX Anda tidak melebihi 5MB.'
                    : 'Terjadi kesalahan saat mengupload file. Pastikan file PDF Anda tidak melebihi 20MB.';
                return back()->withErrors([
                    $fileField => $errorMsg
                ])->withInput();
            }

            // Re-throw the exception if it's not file upload related
            throw $e;
        }
    }
}
