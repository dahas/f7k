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

## Extend f7k with Controllers
With Controllers you bring your Application to life.

````php
// controllers/YourController.php

<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Sources\ControllerBase;
use PHPSkeleton\Sources\attributes\{Route};
use PHPSkeleton\Sources\{Request, Response};

class YourController extends ControllerBase {

    #[Route(path: '/YourController', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $data = $data = $request->getData();
        $response->write("Hello " . $data['name']);
    }
    
    //...
}
````
Check it out in the web browser (provide your name):  
http://localhost:2400/YourController?name=\<YourName\>

Now you probably want to return a beautiful HTML template. Therefor you need a Template Engine. The Latte Engine is already available as a Service. 

### Here is how you use it:

1. Create an HTML file named `Your.layout.html` with the following content in the `templates` folder:  

    ````html
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title}</title>
    </head>
    <body style="font-family: 'Courier New', Courier, monospace; margin: 60px auto; text-align: center">
        <div style="background-color: rgb(196, 250, 255); padding: 20px 0;">
            <h1>{$header}</h1>
            <p>{$message}</p>
        </div>
    </body>
    </html>
    ````
1. Inject the Template Engine as shown below:  

    ````php
    // controllers/YourController.php

    <?php declare(strict_types=1);

    namespace PHPSkeleton\Controller;

    use PHPSkeleton\Library\TemplateEngine;
    use PHPSkeleton\Sources\ControllerBase;
    use PHPSkeleton\Sources\attributes\{Inject, Route};
    use PHPSkeleton\Sources\{Request, Response};

    class YourController extends ControllerBase {

        #[Inject(TemplateEngine::class)]
        protected $template;

        #[Route(path: '/YourController', method: 'get')]
        public function main(Request $request, Response $response): void
        {
            $this->injectServices();

            $data = $request->getData();

            $this->template->assign([
                'title' => 'Your Controller',
                'header' => 'f7k is cool!',
                'message' => 'But ' . $data['name'] . ' is even cooler :p'
            ]);
            $this->template->parse('Your.layout.html');
            $this->template->render($request, $response);
        }
        
        //...
    }
    ````
1. Check it out again:  
    http://localhost:2400/YourController?name=\<YourName\>

Learn more about Services next.

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

use PHPSkeleton\Sources\ControllerBase;
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