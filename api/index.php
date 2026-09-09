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

// Copy bundled CA certificate to /tmp for MySQL SSL
$bundledCa = dirname(__DIR__) . '/database/certs/ca.pem';
$tmpCa = '/tmp/ca.pem';
if (file_exists($bundledCa) && !file_exists($tmpCa)) {
    copy($bundledCa, $tmpCa);
}
if (file_exists($tmpCa)) {
    $_SERVER['MYSQL_ATTR_SSL_CA'] = $tmpCa;
    $_ENV['MYSQL_ATTR_SSL_CA'] = $tmpCa;
    putenv("MYSQL_ATTR_SSL_CA=$tmpCa");
}

// Forward to Laravel
require __DIR__ . '/../public/index.php';
