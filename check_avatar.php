<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::find(1);
echo "User ID: " . $user->UserID . "\n";
echo "FullName: " . $user->FullName . "\n";
echo "Avatar field: " . ($user->Avatar ?? 'NULL') . "\n";

// Check if file exists
$avatarPath = $user->Avatar;
if ($avatarPath) {
    $filename = basename($avatarPath);
    $fullPath = 'c:/laragon/www/charcoal/storage/app/public/avatars/' . $filename;
    echo "Filename: " . $filename . "\n";
    echo "Full storage path: " . $fullPath . "\n";
    echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    
    // Also check public storage
    $publicPath = 'c:/laragon/www/charcoal/public/storage/avatars/' . $filename;
    echo "Public storage path: " . $publicPath . "\n";
    echo "File exists in public: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
}




