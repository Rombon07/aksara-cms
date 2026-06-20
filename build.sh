#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Installing npm dependencies and building assets..."
npm install
npm run build
