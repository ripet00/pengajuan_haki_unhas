<?php
/**
 * Script untuk update original filename pada data yang sudah ada
 * Jalankan script ini setelah migration untuk mengisi kolom original_filename
 * 
 * Cara menjalankan:
 * php scripts/update-admin-original-filenames.php
 */

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BiodataPaten;
use App\Models\SubmissionPaten;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "===========================================\n";
echo "Update Original Filenames for Admin Files\n";
echo "===========================================\n\n";

// 1. Update BiodataPaten - application_document
echo "1. Processing biodatas_paten table...\n";

$biodataCount = 0;
$biodataSkipped = 0;

$biodataPatens = BiodataPaten::whereNotNull('application_document')
                             ->get();

foreach ($biodataPatens as $biodata) {
    // Skip if already has original_filename
    if ($biodata->original_filename) {
        $biodataSkipped++;
        continue;
    }
    
    // Extract extension from file path
    $extension = pathinfo($biodata->application_document, PATHINFO_EXTENSION);
    if (!$extension) {
        $extension = 'pdf'; // Default to pdf
    }
    
    // Generate original filename based on pattern
    $originalFilename = 'dokumen_permohonan_paten_' . $biodata->id . '.' . $extension;
    
    $biodata->update([
        'original_filename' => $originalFilename
    ]);
    
    $biodataCount++;
    echo "  ✓ Updated BiodataPaten #{$biodata->id}: {$originalFilename}\n";
}

echo "\nBiodataPaten Summary:\n";
echo "  - Updated: {$biodataCount}\n";
echo "  - Skipped (already has filename): {$biodataSkipped}\n";
echo "  - Total processed: " . ($biodataCount + $biodataSkipped) . "\n\n";

// 2. Update SubmissionPaten - substance_review_file
echo "2. Processing submissions_paten table...\n";

$submissionCount = 0;
$submissionSkipped = 0;

$submissionPatens = SubmissionPaten::whereNotNull('substance_review_file')
                                   ->get();

foreach ($submissionPatens as $submission) {
    // Skip if already has original_substance_review_filename
    if ($submission->original_substance_review_filename) {
        $submissionSkipped++;
        continue;
    }
    
    // Extract extension from file path
    $extension = pathinfo($submission->substance_review_file, PATHINFO_EXTENSION);
    if (!$extension) {
        $extension = 'pdf'; // Default to pdf
    }
    
    // Generate original filename based on pattern
    $originalFilename = 'substance_review_' . $submission->id . '.' . $extension;
    
    $submission->update([
        'original_substance_review_filename' => $originalFilename
    ]);
    
    $submissionCount++;
    echo "  ✓ Updated SubmissionPaten #{$submission->id}: {$originalFilename}\n";
}

echo "\nSubmissionPaten Summary:\n";
echo "  - Updated: {$submissionCount}\n";
echo "  - Skipped (already has filename): {$submissionSkipped}\n";
echo "  - Total processed: " . ($submissionCount + $submissionSkipped) . "\n\n";

// 3. Check if any files need to be moved from public to private storage
echo "3. Checking storage locations...\n";

$publicFiles = 0;
$privateFiles = 0;

foreach ($biodataPatens as $biodata) {
    if (!$biodata->application_document) continue;
    
    if (str_starts_with($biodata->application_document, 'application_documents/')) {
        // Check if in public storage
        if (Storage::disk('public')->exists($biodata->application_document)) {
            $publicFiles++;
            echo "  ⚠️  BiodataPaten #{$biodata->id} file is in PUBLIC storage: {$biodata->application_document}\n";
        } elseif (Storage::disk('local')->exists($biodata->application_document)) {
            $privateFiles++;
        }
    }
}

foreach ($submissionPatens as $submission) {
    if (!$submission->substance_review_file) continue;
    
    if (str_starts_with($submission->substance_review_file, 'substance_review_files/')) {
        // Check if in public storage
        if (Storage::disk('public')->exists($submission->substance_review_file)) {
            $publicFiles++;
            echo "  ⚠️  SubmissionPaten #{$submission->id} file is in PUBLIC storage: {$submission->substance_review_file}\n";
        } elseif (Storage::disk('local')->exists($submission->substance_review_file)) {
            $privateFiles++;
        }
    }
}

echo "\nStorage Summary:\n";
echo "  - Files in PUBLIC storage: {$publicFiles}\n";
echo "  - Files in PRIVATE storage: {$privateFiles}\n";

if ($publicFiles > 0) {
    echo "\n⚠️  WARNING: Ada {$publicFiles} file yang masih di public storage!\n";
    echo "   File-file ini masih dapat diakses langsung via URL.\n";
    echo "   Pertimbangkan untuk memindahkan ke private storage secara manual.\n";
}

echo "\n===========================================\n";
echo "✅ Script completed successfully!\n";
echo "===========================================\n";

exit(0);
