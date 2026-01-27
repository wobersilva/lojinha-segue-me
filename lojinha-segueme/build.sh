#!/bin/bash

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "Building frontend assets..."
npm run build

echo "Creating storage directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Build completed successfully!"
