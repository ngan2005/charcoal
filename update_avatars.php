<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Assign avatar to Admin (User 1)
$admin = User::find(1);
$admin->Avatar = asset('storage/avatars/ngan.jpg');
$admin->save();
echo "Updated User 1 (Admin) avatar to: " . $admin->Avatar . "\n";

// Assign avatar to User 2 (Kim Ngân)  
$user2 = User::find(2);
$user2->Avatar = asset('storage/avatars/ngan.jpg');
$user2->save();
echo "Updated User 2 (Kim Ngân) avatar to: " . $user2->Avatar . "\n";

// Fix User 3's avatar URL (it was using http://charcoal.test which is wrong)
$user3 = User::find(3);
$user3->Avatar = asset('storage/avatars/95uzhqLuDZ5KbySmnytFj4fcOD9uZolzpqAYHJAA.jpg');
$user3->save();
echo "Updated User 3 avatar to: " . $user3->Avatar . "\n";

echo "\n=== All users after update ===\n";
$users = User::all(['UserID', 'FullName', 'Avatar']);
foreach ($users as $u) {
    echo "User {$u->UserID}: {$u->FullName} - Avatar: " . ($u->Avatar ?? 'NULL') . "\n";
}




