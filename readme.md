<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## Transactions API

## Install
In the root folder of the project Run the following commands

cp .env.example .env
Edit the .env file in order to set the database name and credentials

composer install

php artisan passport:install

php artisan passport:client --personal

php artisan migrate

php artisan serve

## RUN TESTS
phpunit OR ./vendor/phpunit/phpunit/phpunit