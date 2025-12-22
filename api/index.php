<?php
/**
 * ❌ VERCEL ISSUE: Laravel's bootstrap cache doesn't work in serverless environment
 * ✅ FIX: Clear bootstrap cache on every request to ensure fresh service provider loading
 *
 * This is necessary because:
 * 1. Vercel's filesystem is read-only after deployment
 * 2. Bootstrap cache files can't be written during runtime
 * 3. Service providers must be loaded fresh on each serverless function invocation
 */

// Clear any cached bootstrap files that might cause issues
$bootstrapCachePath = __DIR__ . '/../bootstrap/cache';
if (is_dir($bootstrapCachePath)) {
    $cacheFiles = ['services.php', 'packages.php', 'config.php', 'routes-v7.php', 'events.php'];
    foreach ($cacheFiles as $file) {
        $filePath = $bootstrapCachePath . '/' . $file;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
