<?php

// ============================================
// Vercel Serverless Bootstrap
// ============================================
// Vercel's filesystem is read-only except /tmp.
// We must redirect ALL writable paths to /tmp
// BEFORE Laravel boots.
// ============================================

// Set storage base in /tmp
$storagePath = '/tmp/storage';

// Create all required Laravel storage directories
$dirs = [
    "$storagePath/framework/views",
    "$storagePath/framework/cache/data",
    "$storagePath/framework/sessions",
    "$storagePath/logs",
    "$storagePath/app/public",
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set environment variables to use /tmp paths
// These MUST be set before Laravel boots
$_SERVER['APP_STORAGE'] = $storagePath;
$_ENV['APP_STORAGE'] = $storagePath;
putenv("APP_STORAGE=$storagePath");

// Forward to Laravel
require __DIR__ . '/../public/index.php';
