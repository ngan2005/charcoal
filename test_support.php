<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Test get all messages
$messages = DB::table('support_messages')->get();

echo "Total messages: " . count($messages) . "\n";

foreach ($messages as $msg) {
    echo "ID: " . $msg->MessageID . " | UserID: " . $msg->UserID . " | Message: " . substr($msg->Message, 0, 30) . " | IsFromAdmin: " . $msg->IsFromAdmin . "\n";
}
