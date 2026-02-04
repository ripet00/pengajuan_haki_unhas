<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionPaten;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    /**
     * Check if current session is admin
     */
    private function isAdmin(): bool
    {
        $adminId = session('admin_id');
        return $adminId && Admin::find($adminId);
    }

    /**
     * Download submission file (Hak Cipta)
     * Only authenticated users who own the submission or admins can download
     */
    public function downloadSubmission(Request $request, Submission $submission): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Authorization check: user owns submission OR is admin
        if (!$isAdmin && (!$user || $submission->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if file exists
        if (!$submission->file_path || !Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'File not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($submission->file_path);

        // Use original filename for download
        $downloadName = $submission->original_filename ?? $submission->file_name ?? 'document.pdf';

        // Return file response with proper headers
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Download submission file (Paten)
     * Only authenticated users who own the submission or admins can download
     */
    public function downloadSubmissionPaten(Request $request, SubmissionPaten $submissionPaten): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Authorization check: user owns submission OR is admin
        if (!$isAdmin && (!$user || $submissionPaten->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if file exists
        if (!$submissionPaten->file_path || !Storage::disk('local')->exists($submissionPaten->file_path)) {
            abort(404, 'File not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($submissionPaten->file_path);

        // Use original filename for download
        $downloadName = $submissionPaten->original_filename ?? $submissionPaten->file_name ?? 'document.docx';

        // Return file response with proper headers
        return response()->file($filePath, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Download review file (for users to see admin review)
     */
    public function downloadReviewFile(Request $request, string $type, int $id): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Determine submission based on type
        if ($type === 'hak-cipta') {
            $submission = Submission::findOrFail($id);
            $ownerId = $submission->user_id;
            $reviewFilePath = $submission->review_file_path ?? null;
        } elseif ($type === 'paten') {
            $submission = SubmissionPaten::findOrFail($id);
            $ownerId = $submission->user_id;
            $reviewFilePath = $submission->review_file_path ?? null;
        } else {
            abort(404, 'Invalid type');
        }

        // Authorization check
        if (!$isAdmin && (!$user || $ownerId !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if review file exists
        if (!$reviewFilePath || !Storage::disk('local')->exists($reviewFilePath)) {
            abort(404, 'Review file not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($reviewFilePath);

        // Generate download name
        $downloadName = 'review_' . $type . '_' . $id . '.pdf';

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Download patent documents (Deskripsi, Klaim, Abstrak, Gambar)
     */
    public function downloadPatentDocument(Request $request, SubmissionPaten $submissionPaten, string $documentType): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Authorization check
        if (!$isAdmin && (!$user || $submissionPaten->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Map document type to database column
        $documentPaths = [
            'deskripsi' => $submissionPaten->biodataPaten->deskripsi_pdf_path ?? null,
            'klaim' => $submissionPaten->biodataPaten->klaim_pdf_path ?? null,
            'abstrak' => $submissionPaten->biodataPaten->abstrak_pdf_path ?? null,
            'gambar' => $submissionPaten->biodataPaten->gambar_pdf_path ?? null,
        ];

        if (!isset($documentPaths[$documentType])) {
            abort(404, 'Invalid document type');
        }

        $filePath = $documentPaths[$documentType];

        // Check if file exists
        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            abort(404, 'Document not found');
        }

        // Get full path from private storage
        $fullPath = Storage::disk('local')->path($filePath);

        // Generate download name
        $downloadName = $documentType . '_paten_' . $submissionPaten->id . '.pdf';

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }
}
