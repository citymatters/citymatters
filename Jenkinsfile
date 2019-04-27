#!/usr/bin/env groovy

node('php7.3') {
    try {
        stage('Dependencies') {
            checkout scm
            sh "cp .env.travis .env"
            sh "composer self-update"
            sh "composer install --no-interaction"
            sh "php artisan key:generate"
            sh "php artisan migrate"
        }
        stage('Testing') {
            sh "vendor/bin/phpunit"
            sh "if [ -f storage/logs/laravel.log ]; then cat storage/logs/laravel.log; fi"
        }
        stage('Infection Testing') {
            sh "vendor/bin/infection"
        }
        stage('Auto-Merge') {
            sh "echo automerge is not happening yet"
        }
    } catch(error) {
        throw error
    } finally {
        sh "rm -rf ./*"
    }
    
}