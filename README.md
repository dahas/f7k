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

## Extend f7k with Services
In addition to installing libraries with Composer, you can create your own Services. To do this, you simply create a Class in the `lib` directory and inject it via Attributes in the Classes where you need the Service. 

Below is a template of a Service class. The constructor with an array of options is mandatory, although using options is optional.

````php
// lib/YourService.php

<?php declare(strict_types=1);

namespace PHPSkeleton\Library;

class YourService {

    public function __construct(private array|null $options = [])
    {

    }

    ...
}
````
Here is how you inject the Service in another Class:
````php
// controllers/AnyController.php

<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\YourService;
use PHPSkeleton\Library\AnotherService;

class AnyController extends ControllerBase {

    /**
     * Service without Options:
     */
    #[Inject(YourService::class)]
    protected $yourService;

    /**
     * Service with Options:
     */
    #[Inject(AnotherService::class, [
        "opt1" => "Option 1", 
        "opt2" => "Option 2"
    ])]
    protected $anotherService;

    ...
}
````
It is also possible to use Services in a Service. Therefore the Service must inherit from the ServiceBase and the constructor must trigger the injection. Like so:
````php
// lib/YourService.php

<?php declare(strict_types=1);

namespace PHPSkeleton\Library;

use PHPSkeleton\Sources\ServiceBase;

class YourService extends ServiceBase {

    #[Inject(AnyService::class)]
    protected $anyService;

    #[Inject(AnotherService::class)]
    protected $anotherService;

    public function __construct(private array|null $options = [])
    {
        $this->injectServices();
    }

    ...
}
````