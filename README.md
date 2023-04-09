# f7k - Yet another framework

<img src="https://img.shields.io/badge/PHP-8.1.2-orange" /> <img src="https://img.shields.io/badge/Latte-3.x-green" /> <img src="https://img.shields.io/badge/Opis ORM-1.x-yellow" /> <img src="https://img.shields.io/badge/PHPUnit-10.x-blue" />

- f7k is the numeronym of the word 'framework'.
- f7k follows the PHP Standard Recommendations (PSR).

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

# How to

## Enable Google User Authentication

1. In the Google Cloud Console go to API credentials:  
https://console.developers.google.com/apis/credentials?hl=de

1. Create a new Project

1. Click on "Configure Consent Screen", choose "External".

1. Enter a name and provide your email address.

1. Skip "Scopes" and "Test Users" and finish the configuration.

1. Go back to Credentials, click on "Create Credentials" and choose "OAuth Client ID".

1. Select "Web Application" as application type.

1. Add "http(s)://your.domain.tld/Auth/login" as authorised redirect URI.

1. Save it.

1. Copy and paste the Client ID and Secret from the final screen and the redirect URI into your `.env` file.


## Extend f7k with Controllers
Create a file `YourController.php` in the `controllers` directory:

````php
// controllers/YourController.php

<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;
use f7k\Sources\{ControllerBase, Request, Response};

class YourController extends ControllerBase {

    private array $data;

    public function __construct(
        protected Request $request, 
        protected Response $response)
    {
        $this->data = $this->request->getData();
    }

    #[Route(path: '/YourController', method: 'get')]
    public function main(): void
    {
        $this->response->write("Hello " . $this->data['name']);
    }
    
    //...
}
````
Check it out in the web browser (provide your name):  
http://localhost:2400/YourController?name=<YourName\>

### Render HTML

Now you probably want to return a beautiful HTML template. Therefor you need a Template Engine. The Latte Engine is already available as a Service. 

Here is how you use it:

Create an HTML file named `Your.layout.html` with the following content in the `templates` folder:  

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
Inject the Template Engine as shown below:  

````php
// controllers/YourController.php

<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\TemplateEngine;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{ControllerBase, Request, Response};

class YourController extends ControllerBase {

    #[Inject(TemplateEngine::class)]
    protected $template;

    protected array $data;

    public function __construct(
        protected Request $request, 
        protected Response $response)
    {
        $this->data = $this->request->getData();
    }

    #[Route(path: '/YourRoute', method: 'get')]
    public function yourMethod(): void
    {
        $this->injectServices();

        $this->template->assign([
            'title' => 'Your Controller',
            'header' => 'f7k is cool!',
            'message' => 'But ' . $this->data['name'] . ' is even cooler :p'
        ]);
        $this->template->parse('Your.layout.html');
        $this->template->render($this->request, $this->response);
    }
    
    //...
}
````
Check it out again:  
http://localhost:2400/YourController?name=<YourName\>

Learn more about Services next.

## Extend f7k with Services
In addition to installing libraries with Composer, you can create your own Services. To do this, you simply create a Class in the `lib` directory and inject it via Attributes in the Classes where you need the Service. 

Below is a template of a Service class. The constructor with an array of options is mandatory, although using options is optional.

````php
// lib/YourService.php

<?php declare(strict_types=1);

namespace f7k\Service;

class YourService {

    public function __construct(private array|null $options = [])
    {

    }
    ...
}
````
Here is how you inject Services in another Class. Note how the constructor triggers the parent constructor:
````php
// controllers/AnyController.php

<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\ControllerBase;
use f7k\Service\YourService;
use f7k\Service\AnotherService;

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
    
    public function __construct()
    {
        parent::__construct();
    }

    // ...
}
````
It is also possible to use Services in a Service. Therefore the Service must inherit from the ServiceBase. Like so:
````php
// lib/YourService.php

<?php declare(strict_types=1);

namespace f7k\Service;

use f7k\Sources\ServiceBase;

class YourService extends ServiceBase {

    #[Inject(AnyService::class)]
    protected $anyService;

    #[Inject(AnotherService::class)]
    protected $anotherService;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();
    }
    
    // ...
}
````

# Available Services 
There are some Services already available which you can use and/or modify to your needs.

## CommentsService
### *Dependencies*: 
* Services: `DatabaseLayer`
* Controllers: `CommentsController`
* Entities: `CommentsEntity`, `RepliesEntity`
* Templates: `Comments.partial.html`
### *Description*:  
Add a commentary feature to a page. Users can add comments and reply to them.
  
## DatabaseLayer
### *Description*:  
A database abstraction layer. f7k uses the DBAL and ORM from Opis. Check it out here: https://opis.io/orm/1.x/quick-start.html

## Navigation
### *Dependencies*:
* Templates: `Nav.partial.html`
### *Description*:  
Creates and renders the navigation bar according the specification in `menu.json`.

## TemplateEngine
### *Description*:  
Parses HTML templates. The Template Engine is build upon the Latte library. Learn more about Latte here: https://latte.nette.org/en/guide


