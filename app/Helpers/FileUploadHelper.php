<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{
    /**
     * Allowed file extensions with their MIME types for strict validation
     * 
     * @var array<string, array<string>>
     */
    private static array $allowedExtensions = [
        'pdf' => [
            'application/pdf',
            'application/x-pdf',
        ],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'doc' => [
            'application/msword',
        ],
    ];

    /**
     * Validate file extension and MIME type to prevent malicious uploads
     * 
     * @param UploadedFile $file
     * @param array<string> $allowedTypes Example: ['pdf', 'docx']
     * @return bool
     */
    public static function isValidFile(UploadedFile $file, array $allowedTypes): bool
    {
        // Get real extension from file content (not from filename)
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Additional check FIRST: Ensure filename doesn't have double extensions
        $originalName = $file->getClientOriginalName();
        
        // More intelligent double extension check:
        // Only reject if there's a dangerous double extension pattern
        // Examples to REJECT: file.php.pdf, document.exe.docx, malware.js.pdf
        // Examples to ALLOW: 0. Format Penilaian.docx, file.draft.v2.docx
        
        // Get filename without the final extension
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        
        // List of dangerous extensions that should never appear before the final extension
        $dangerousExtensions = ['php', 'exe', 'bat', 'cmd', 'sh', 'js', 'jar', 'com', 'pif', 'scr', 'vbs', 'ps1'];
        
        // Check if any dangerous extension appears in the filename before the final extension
        foreach ($dangerousExtensions as $dangerousExt) {
            if (preg_match('/\.' . preg_quote($dangerousExt, '/') . '$/i', $nameWithoutExtension)) {
                return false; // Reject files like "malware.php.docx"
            }
        }

        // Check if extension is in allowed list
        if (!in_array($extension, $allowedTypes)) {
            return false;
        }

        // Check if extension is registered
        if (!isset(self::$allowedExtensions[$extension])) {
            return false;
        }

        // Try to validate MIME type - wrap in try-catch for Windows compatibility
        try {
            $mimeType = $file->getMimeType();
            
            // Validate MIME type matches extension
            if (!in_array($mimeType, self::$allowedExtensions[$extension])) {
                return false;
            }
        } catch (\Exception $e) {
            // If MIME detection fails (common on Windows), do additional checks
            \Log::warning('MIME type detection failed, using fallback validation', [
                'file' => $originalName,
                'error' => $e->getMessage()
            ]);
            
            // Fallback: Read file signature (magic bytes) manually
            try {
                $handle = fopen($file->getRealPath(), 'rb');
                if ($handle === false) {
                    return false;
                }
                
                $header = fread($handle, 8);
                fclose($handle);
                
                // Check file signatures
                if ($extension === 'pdf') {
                    // PDF files start with %PDF
                    if (substr($header, 0, 4) !== '%PDF') {
                        return false;
                    }
                } elseif ($extension === 'docx') {
                    // DOCX files are ZIP archives (PK signature)
                    if (substr($header, 0, 2) !== 'PK') {
                        return false;
                    }
                }
            } catch (\Exception $fallbackError) {
                \Log::error('Fallback file validation failed', [
                    'file' => $originalName,
                    'error' => $fallbackError->getMessage()
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Generate secure hashed filename with original extension
     * 
     * @param UploadedFile $file
     * @return array{hashed_name: string, original_name: string, extension: string}
     */
    public static function generateSecureFilename(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Generate SHA-256 hash from file content + timestamp for uniqueness
        $hash = hash('sha256', $file->get() . time() . Str::random(16));
        
        // Create hashed filename
        $hashedName = $hash . '.' . $extension;

        return [
            'hashed_name' => $hashedName,
            'original_name' => $originalName,
            'extension' => $extension,
        ];
    }

    /**
     * Upload file securely to private storage with hashed filename
     * 
     * @param UploadedFile $file
     * @param string $directory Directory in storage (e.g., 'submissions', 'submissions_paten')
     * @param array<string> $allowedTypes
     * @return array{success: bool, path: string|null, original_name: string|null, hashed_name: string|null, error: string|null}
     */
    public static function uploadSecure(UploadedFile $file, string $directory, array $allowedTypes): array
    {
        // Validate file
        if (!self::isValidFile($file, $allowedTypes)) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Provide specific error message
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            $dangerousExtensions = ['php', 'exe', 'bat', 'cmd', 'sh', 'js', 'jar', 'com', 'pif', 'scr', 'vbs', 'ps1'];
            
            // Check if dangerous double extension
            $isDangerousDouble = false;
            foreach ($dangerousExtensions as $dangerousExt) {
                if (preg_match('/\.' . preg_quote($dangerousExt, '/') . '$/i', $nameWithoutExtension)) {
                    $isDangerousDouble = true;
                    break;
                }
            }
            
            if ($isDangerousDouble) {
                return [
                    'success' => false,
                    'path' => null,
                    'original_name' => null,
                    'hashed_name' => null,
                    'error' => 'File ditolak: File "' . $originalName . '" terdeteksi sebagai file berbahaya (double extension). Ekstensi berbahaya tidak diizinkan.',
                ];
            }
            
            if (!in_array($extension, $allowedTypes)) {
                $allowed = implode(', ', $allowedTypes);
                return [
                    'success' => false,
                    'path' => null,
                    'original_name' => null,
                    'hashed_name' => null,
                    'error' => 'Tipe file tidak diizinkan. Hanya file dengan ekstensi: ' . strtoupper($allowed) . ' yang diperbolehkan.',
                ];
            }
            
            return [
                'success' => false,
                'path' => null,
                'original_name' => null,
                'hashed_name' => null,
                'error' => 'File ditolak: Validasi keamanan gagal. File mungkin rusak atau berbahaya.',
            ];
        }

        try {
            // Generate secure filename
            $fileInfo = self::generateSecureFilename($file);
            
            // Store in PRIVATE disk (not public!)
            $path = $file->storeAs($directory, $fileInfo['hashed_name'], 'local');

            return [
                'success' => true,
                'path' => $path,
                'original_name' => $fileInfo['original_name'],
                'hashed_name' => $fileInfo['hashed_name'],
                'error' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'path' => null,
                'original_name' => null,
                'hashed_name' => null,
                'error' => 'Upload gagal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete file from private storage
     * 
     * @param string $path Full path in storage (e.g., 'submissions/hash.pdf')
     * @return bool
     */
    public static function deleteSecure(string $path): bool
    {
        try {
            if (Storage::disk('local')->exists($path)) {
                return Storage::disk('local')->delete($path);
            }
            return true; // File already deleted
        } catch (\Exception $e) {
            \Log::error('File deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if file exists in private storage
     * 
     * @param string $path
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return Storage::disk('local')->exists($path);
    }
}
