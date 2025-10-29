<?php

// Test script untuk similar titles feature
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Submission;

echo "=== Testing Similar Titles Feature ===\n\n";

// Test case 1: Cari judul yang sama persis
echo "1. Testing exact match:\n";
$testTitle = "Penerapan Algoritma Fuzzy";
$similar = Submission::findSimilarTitles($testTitle);
echo "Searching for: '$testTitle'\n";
echo "Found: " . $similar->count() . " similar titles\n\n";

// Test case 2: Cari judul dengan case berbeda
echo "2. Testing case-insensitive match:\n";
$testTitle2 = "PENERAPAN ALGORITMA FUZZY";
$similar2 = Submission::findSimilarTitles($testTitle2);
echo "Searching for: '$testTitle2'\n";
echo "Found: " . $similar2->count() . " similar titles\n\n";

// Test case 3: List semua judul submissions
echo "3. All existing submissions:\n";
$allSubmissions = Submission::select('id', 'title', 'created_at')->orderBy('created_at')->get();
foreach ($allSubmissions as $submission) {
    echo "ID: {$submission->id} - Title: '{$submission->title}' - Date: {$submission->created_at}\n";
}

echo "\n=== Test Complete ===\n";