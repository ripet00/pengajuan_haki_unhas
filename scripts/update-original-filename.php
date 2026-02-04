<?php

/**
 * Script to update existing records with original_filename
 * This will copy file_name to original_filename for existing records
 * that don't have original_filename set yet.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Submission;
use App\Models\SubmissionPaten;

echo "=== Updating Original Filename for Existing Records ===\n\n";

// Update Submissions (Hak Cipta)
echo "Updating submissions table...\n";
$submissionsUpdated = Submission::whereNull('original_filename')
    ->orWhere('original_filename', '')
    ->update(['original_filename' => \DB::raw('file_name')]);
echo "✓ Updated {$submissionsUpdated} records in submissions table\n\n";

// Update Submissions Paten
echo "Updating submissions_paten table...\n";
$patenUpdated = SubmissionPaten::whereNull('original_filename')
    ->orWhere('original_filename', '')
    ->update(['original_filename' => \DB::raw('file_name')]);
echo "✓ Updated {$patenUpdated} records in submissions_paten table\n\n";

echo "=== Update Complete ===\n";
echo "Total records updated: " . ($submissionsUpdated + $patenUpdated) . "\n";
