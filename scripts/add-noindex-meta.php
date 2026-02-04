#!/usr/bin/env php
<?php

/**
 * Script to add noindex meta tags to all Blade views
 * Run: php scripts/add-noindex-meta.php
 */

$viewsPath = __DIR__ . '/../resources/views';

// Files to update (all blade files with <!DOCTYPE html>)
$command = "grep -rl '<!DOCTYPE html>' " . escapeshellarg($viewsPath);
$files = shell_exec($command);
$filesList = array_filter(explode("\n", $files));

$updated = 0;
$skipped = 0;

foreach ($filesList as $file) {
    $content = file_get_contents($file);
    
    // Check if already has noindex meta
    if (str_contains($content, 'name="robots"')) {
        echo "⏭️  Skipped (already has robots meta): " . basename($file) . "\n";
        $skipped++;
        continue;
    }
    
    // Add meta robots after viewport meta tag
    $pattern = '/(<meta name="viewport"[^>]*>)/';
    $replacement = "$1\n    <meta name=\"robots\" content=\"noindex, nofollow\">\n    <meta name=\"googlebot\" content=\"noindex, nofollow\">";
    
    $newContent = preg_replace($pattern, $replacement, $content, 1);
    
    if ($newContent !== $content) {
        file_put_contents($file, $newContent);
        echo "✅ Updated: " . basename($file) . "\n";
        $updated++;
    } else {
        echo "⚠️  No viewport meta found: " . basename($file) . "\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════\n";
echo "📊 Summary:\n";
echo "   Updated: $updated files\n";
echo "   Skipped: $skipped files\n";
echo "═══════════════════════════════════════\n";
