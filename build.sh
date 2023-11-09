#!/usr/bin/env bash

# Create a raw Laravel website without finalising
set -ex
mkdir build
composer create-project --no-install --no-scripts laravel/laravel build
cd build

# Symlink our .env, set our composer repo for the project files
ln -s ../.env ./.env
composer config repositories.dmlogic/google-photos-slideshow path ../
composer config minimum-stability dev
composer install
composer require dmlogic/google-photos-slideshow

# We don't want any included routes
> routes/api.php
> routes/channels.php
> routes/console.php
> routes/web.php

# Complete the Laravel install
composer run-script post-root-package-install
composer run-script post-create-project-cmd

# Create a placeholder DB and run migrations
rm database/migrations/*.php
touch database/database.sqlite
php artisan migrate

# Symlink the public photo path to the private storage folder
source .env
ln -s "$PHOTO_STORAGE_PATH" "public/photos"
