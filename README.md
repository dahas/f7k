# PHP Application Skeleton
Everything you need to build a Vanilla PHP App from scratch.

## Installation
````
$ git clone https://github.com/dahas/PHPSkeleton.git
$ cd PHPSkeleton
$ composer install
````

## Environment variables
Put all your sensitive informations into the `.env` file and replace them in your code with the global Environment variables of PHP. E. g.: `$_ENV['API_KEY']`.

Make sure the `.env` file is added to `.gitignore` so it won't be added to your public repository.

## Running tests
This App Skeleton uses PHPUnit to run unit tests.
````
$ composer test
````
