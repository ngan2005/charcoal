<?php
$env = file_get_contents('.env');

$lines = explode("\n", $env);
$newLines = [];

foreach ($lines as $line) {
    if (preg_match('/^MAIL_MAILER=/', $line)) {
        $newLines[] = 'MAIL_MAILER=smtp';
    } elseif (preg_match('/^MAIL_HOST=/', $line)) {
        $newLines[] = 'MAIL_HOST=smtp.gmail.com';
    } elseif (preg_match('/^MAIL_PORT=/', $line)) {
        $newLines[] = 'MAIL_PORT=587';
    } elseif (preg_match('/^MAIL_USERNAME=/', $line)) {
        $newLines[] = 'MAIL_USERNAME=luu.kimngan205@gmail.com';
    } elseif (preg_match('/^MAIL_PASSWORD=/', $line)) {
        $newLines[] = 'MAIL_PASSWORD=thykhtlwpfhrryyu';
    } elseif (preg_match('/^MAIL_ENCRYPTION=/', $line)) {
        $newLines[] = 'MAIL_ENCRYPTION=tls';
    } elseif (preg_match('/^MAIL_FROM_ADDRESS=/', $line)) {
        $newLines[] = 'MAIL_FROM_ADDRESS=luu.kimngan205@gmail.com';
    } elseif (preg_match('/^MAIL_FROM_NAME=/', $line)) {
        $newLines[] = 'MAIL_FROM_NAME=PinkCharcoal';
    } else {
        $newLines[] = $line;
    }
}

file_put_contents('.env', implode("\n", $newLines));
echo "Updated!\n";
