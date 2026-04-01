<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'PinkCharcoal');

// Git repository
set('repository', 'https://github.com/ngan2005/charcoal.git');

// Deploy to server
host('production')
    ->hostname('YOUR_VPS_IP')
    ->port(22)
    ->user('deployer')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/charcoal');

// Laravel shared files
set('shared_files', [
    '.env',
    'storage/logs/*.log',
]);

set('shared_dirs', [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
]);

// Writable directories
set('writable_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Clear cache after deploy
after('deploy:shared', 'artisan:storage:link');
after('deploy:shared', 'artisan:config:cache');
after('deploy:shared', 'artisan:route:cache');

// Restart queue after deploy
desc('Restart PHP-FPM');
task('php-fpm:restart', function () {
    run('sudo systemctl restart php8.2-fpm');
});

after('deploy:symlink', 'php-fpm:restart');

// Unlock if deploy fails
after('deploy:failed', 'deploy:unlock');
