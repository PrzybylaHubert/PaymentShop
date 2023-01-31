## Payment shop
Master branch has the whole shop without payment, while payment-implement branch has dotpay integration with dotpay info to fill.

## Requirements
- PHP 8+
- MySQL MariaDB
- Composer

I recommend to use Symfony CLI - [link](https://symfony.com/download)

Project was set up on MariaDB, xampp MySQL recommended - [link](https://www.apachefriends.org/)

## Setting up

Just write these commands in your project folder, after starting MySQL in xampp.

install dependencies
> composer install

Create the database
> symfony console doctrine:database:create

Create tables
> symfony console doctrine:migrations:migrate

Fill beers data
> symfony console doctrine:fixtures:load

Start the webserver
> symfony console server:start

Done!






