<?php

return [
    'runtime' => 'vercel-php@0.7.2',
    'includeFiles' => [
        'app/**',
        'bootstrap/**',
        'config/**',
        'database/**',
        'public/**',
        'resources/**',
        'routes/**',
        'storage/framework/cache/.gitkeep',
        'storage/framework/sessions/.gitkeep',
        'storage/framework/views/.gitkeep',
        'storage/logs/.gitignore',
        'vendor/**',
        'artisan',
        'composer.json',
        'composer.lock',
    ],
];
