<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    /**
     * Check if current user is admin
     */
    private function isAdmin(): bool
    {
        return Auth::guard('admin')->check();
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

    /**
     * Download application document (admin uploaded file)
     */
    public function downloadApplicationDocument(Request $request, $biodataPatenId): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Find biodata paten
        $biodataPaten = \App\Models\BiodataPaten::findOrFail($biodataPatenId);
        
        // Get submission to check ownership
        $submission = $biodataPaten->submissionPaten;

        // Authorization check
        if (!$isAdmin && (!$user || $submission->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if file exists
        if (!$biodataPaten->application_document || !Storage::disk('local')->exists($biodataPaten->application_document)) {
            abort(404, 'Application document not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($biodataPaten->application_document);

        // Use original filename or generate one
        $downloadName = $biodataPaten->original_filename ?? 'dokumen_permohonan_paten_' . $biodataPaten->id . '.pdf';

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Download substance review file (pendamping paten uploaded file)
     */
    public function downloadSubstanceReviewFile(Request $request, SubmissionPaten $submissionPaten): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Authorization check
        if (!$isAdmin && (!$user || $submissionPaten->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if file exists
        if (!$submissionPaten->substance_review_file || !Storage::disk('local')->exists($submissionPaten->substance_review_file)) {
            abort(404, 'Substance review file not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($submissionPaten->substance_review_file);

        // Use original filename or generate one
        $downloadName = $submissionPaten->original_substance_review_filename ?? 'substance_review_' . $submissionPaten->id . '.pdf';

        return response()->file($filePath, [
            'Content-Type' => Storage::mimeType($submissionPaten->substance_review_file),
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Download format review file (admin paten uploaded file)
     */
    public function downloadFormatReviewFile(Request $request, SubmissionPaten $submissionPaten): BinaryFileResponse
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin();

        // Authorization check
        if (!$isAdmin && (!$user || $submissionPaten->user_id !== $user->id)) {
            abort(403, 'Unauthorized to download this file');
        }

        // Check if file exists
        if (!$submissionPaten->file_review_path || !Storage::disk('local')->exists($submissionPaten->file_review_path)) {
            abort(404, 'Format review file not found');
        }

        // Get full path from private storage
        $filePath = Storage::disk('local')->path($submissionPaten->file_review_path);

        // Use original filename or generate one
        $downloadName = $submissionPaten->original_file_review_filename ?? 'format_review_' . $submissionPaten->id . '.docx';

        return response()->file($filePath, [
            'Content-Type' => Storage::mimeType($submissionPaten->file_review_path),
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }
}
