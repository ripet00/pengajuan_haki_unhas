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
            $fileType = $request->input('file_type', 'pdf'); // Get file type from request
            
            // Check file size before processing
            if ($file) {
                $fileSize = $file->getSize();
                $maxSize = 20 * 1024 * 1024; // 20MB in bytes
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        $fileField => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 20MB untuk {$fileType}."
                    ])->withInput();
                }
                
                // Check file type based on user selection
                if ($fileType === 'pdf') {
                    // Check if file is actually a PDF
                    if ($file->getClientMimeType() !== 'application/pdf' && 
                        !str_ends_with(strtolower($file->getClientOriginalName()), '.pdf')) {
                        return back()->withErrors([
                            $fileField => 'Hanya file PDF yang diperbolehkan untuk jenis file PDF.'
                        ])->withInput();
                    }
                } elseif ($fileType === 'video') {
                    // Check if file is actually an MP4
                    $allowedMimes = ['video/mp4'];
                    $isValidMime = in_array($file->getClientMimeType(), $allowedMimes);
                    $isValidExtension = str_ends_with(strtolower($file->getClientOriginalName()), '.mp4');
                    
                    if (!$isValidMime && !$isValidExtension) {
                        return back()->withErrors([
                            $fileField => 'Hanya file MP4 yang diperbolehkan untuk jenis file video.'
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
                return back()->withErrors([
                    $fileField => 'Terjadi kesalahan saat mengupload file. Pastikan file PDF Anda tidak melebihi 20MB.'
                ])->withInput();
            }

            // Re-throw the exception if it's not file upload related
            throw $e;
        }
    }
}
