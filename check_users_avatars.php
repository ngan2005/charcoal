<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Check all users
echo "=== All users ===\n";
$users = User::all(['UserID', 'FullName', 'Avatar']);
foreach ($users as $u) {
    echo "User {$u->UserID}: {$u->FullName} - Avatar: " . ($u->Avatar ?? 'NULL') . "\n";
}

// Check existing avatar files
echo "\n=== Avatar files in storage/app/public/avatars ===\n";
$storagePath = 'c:/laragon/www/charcoal/storage/app/public/avatars';
if (is_dir($storagePath)) {
    $files = glob($storagePath . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    foreach ($files as $file) {
        echo basename($file) . " - " . filesize($file) . " bytes\n";
    }
}








