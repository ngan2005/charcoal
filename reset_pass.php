<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = '2431540089@vaa.edu.vn';
$user = User::where('Email', $email)->first();

if ($user) {
    $user->Password = Hash::make('123456');
    $user->save();
    echo "SUCCESS: Password for $email has been reset to '123456'";
} else {
    echo "ERROR: User $email not found";
}
