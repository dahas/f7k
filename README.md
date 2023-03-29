# Yet another f7k
f7k is the numeronym of the word 'framework'.

## Minimum Requirements

- PHP 8.1.2
- Composer

## Installation
````
$ mkdir your_project
$ cd your_project
$ git clone https://github.com/dahas/f7k.git .
$ composer install
````

## Template Engine
f7k uses the Latte engine. Learn more about it here: https://latte.nette.org/en/guide

## ORM
f7k uses the ORM (Object Relational Mapper) from Opis. Check it out here: https://opis.io/orm/1.x/quick-start.html

## Environment variables
Rename example.env to `.env`. Put all your sensitive informations into this file and use the global Environment variables of PHP to access them. E. g.: `$_ENV['API_KEY']`.

Make sure your `.env` file is added to `.gitignore` so it doesn't appear in your public repository.

## Test locally
````
$ php -S localhost:2400 -t public
````

## Running tests
This App Skeleton uses PHPUnit to run unit tests.
````
$ composer test
````
