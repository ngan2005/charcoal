<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing email...\n";

try {
    Mail::raw('Test email from PinkCharcoal', function($msg) {
        $msg->to('luu.kimngan205@gmail.com')
            ->subject('Test Email');
    });
    echo "Email sent successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
