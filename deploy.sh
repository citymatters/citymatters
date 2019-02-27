#!/usr/bin/env sh
composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader
yarn install
yarn prod