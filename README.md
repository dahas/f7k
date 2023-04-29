# f7k - yet another framework

<img src="https://img.shields.io/badge/PHP-8.1.2-orange" /> <img src="https://img.shields.io/badge/Latte-3.x-green" /> <img src="https://img.shields.io/badge/Opis ORM-1.x-yellow" /> <img src="https://img.shields.io/badge/PHPUnit-10.x-blue" />

- f7k is the numeronym of the word 'framework'.
- f7k follows the PHP Standard Recommendations (PSR).
- f7k requires good knowledge of the php programming language.

## Minimum Requirements

- PHP 8.1.2
- Composer
- A Google Account

## Installation
````
$ mkdir <your_project> && cd <your_project>
$ git clone https://github.com/dahas/f7k.git .
$ composer install
````

# Setting things up

## Environment variables

Rename `.env.example` to `.env`. All sensitive informations are stored in this file. Double check that it is added to `.gitignore` so it won't accidentially appear in your public repository.

Open the `.env` file and adjust some settings:

- Leave the LOCAL_HOST setting as it is.
- Set your PUBLIC_DOMAIN if you have one already registered.

# Run locally

Configure your Apache or Nginx Webservere to serve from the `www` directory

Launch the PHP build-in web server:
````
$ php -S localhost:2400 -t www
````

# How to 

## Extend f7k with Controllers

With a Controller you can extend f7k with new Pages and new Functionality.

Create a file `MyController.php` in the `controllers` directory:

````php
// controllers/MyController.php

<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;
use f7k\Sources\ControllerBase;

class MyController extends ControllerBase {

    #[Route(path: '/SayMyName/{name}', method: 'get')]
    public function main(): void
    {
        $this->response->write("Hello " . $this->data['name']);
    }
    
    //...
}
````
Check it out in the web browser (provide your name):  
http://localhost:2400/SayMyName/<YourName\>

### Render HTML

Now, if you want to render a beautiful HTML template, you need a Template Engine. The Latte Engine is available as a Service. 

Here is how you use it:

Create an HTML file named `My.partial.html` with the following content in the `templates` folder:  

````html
{layout 'App.layout.html'}

{block content}
<div style="background-color: rgb(196, 250, 255); padding: 20px 0; font-family: 'Courier New', Courier, monospace; margin: 0px auto; text-align: center">
    <h1>{$header}</h1>
    <p>{$message}</p>
</div>
{/block}
````
Inject the Template Engine as shown below:  

````php
// controllers/MyController.php

<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\TemplateService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\ControllerBase;

class MyController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Route(path: '/SayMyName/{name}', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            'title' => 'My Controller',
            'header' => 'f7k is cool!',
            'message' => 'But ' . $this->data['name'] . ' is way cooler :p'
        ]);
        $this->template->parse('My.partial.html');
        $this->template->render();
    }
    
    //...
}
````
Check it out again:  
http://localhost:2400/SayMyName/<YourName\>

## Extend the Menu

Finally, you might want your new page to appear in the navigation bar. Therefore open `menu.json` and insert the following lines:

````
// ...
{
    "path": "/SayMyName/<YourName\>",
    "controller": "MyController",
    "title": "Say My Name",
    "enabled": true
},
// ...
````
To see the Menu you have to do one last little change in your Controller: 
````php
class MyController extends AppController {
    
    //...
}
````
The `AppController` hosts all the features and services that you need throughout the whole app (like Navigation Bar, Footer, etc.).

## Extend f7k with Services
A Service is an Object that provides additional features and/or data. Most of the time you will retrieve data from a database table within a Service.  

To create a Service you simply create a Class in the `services` directory and inject it via an Attribute in the Controller where you need the Service. Name yours `MyService.php`.

````php
// services/MyService.php

<?php declare(strict_types=1);

namespace f7k\Service;

use f7k\Sources\{Request, Response, Session};

class MyService {

    /**
     * The constructor is optional. Use it, when you require access 
     * to the Request, Response or Session instances.
     */
    public function __construct(
        protected Request $request, 
        protected Response $response, 
        protected Session $session
    ) {}
    //...

    public function myMethod(string $name): string
    {
        return "Hey $name! How can I serve you?";
    }
}
````
Now inject the Service in your Controller `MyController.php`:
````php
// controllers/AnyController.php

use f7k\Service\{TemplateService, MyService};

class MyController extends AppController {

    #[Inject(MyService::class)]
    protected $myService;

    // ...
}
````
To use the Service assign `$this->myService->myMethod(<name>)` to the 'message' marker of the template:
````php
#[Route(path: '/SayMyName/{name}', method: 'get')]
public function main(): void
{
    $this->template->assign([
        'title' => 'Your Controller',
        'header' => 'f7k is cool!',
        'message' => $this->myService->myMethod($this->data['name'])
    ]);
    $this->template->parse('My.partial.html');
    $this->template->render();
}
````

It is also possible to use Services in other Service.

## Enable Google User Authentication

All kind of authentication and authorization is done externally using the Google OAuth service. Therefore it is neccessary that you have a Google account and have the API enabled. Below is a description of how to enable the Authentication API.

1. In the Google Cloud Console go to API credentials:  
https://console.developers.google.com/apis/credentials?hl=de
1. Create a new Project
1. Click on "Configure Consent Screen", choose "External".
1. Enter a name and provide your email address.
1. Skip "Scopes" and "Test Users" and finish the configuration.
1. Go back to Credentials, click on "Create Credentials" and choose "OAuth Client ID".
1. Select "Web Application" as application type.
1. Copy the LOCAL_HOST and the PUBLIC_DOMAIN from your `.env` file, paste them into authorised redirect URIs and extend both of them with *"/Authenticate"*.  
    E. g.: http://localhost:2400/Authenticate
1. Save it.
1. Copy and paste the Client ID and Secret from the final screen into the `.env` file.

## Become the Admin. 

In the terminal run:
````
$ php encrypt.php <your_gmail_address>
````
- Copy the hash and assign it to ACCOUNT_HASH.

# Available Services 
Next you find some Services that are already available. Feel free to use and/or modify them as you like.

## ArticlesService

### *Dependencies*: 
* Services: `AuthenticationService`, `DbalService`, `PurifyService`
* Templates: `Article.partial.html`
### *Description*:  
Create and edit Blog articles.

## AuthenticationService
### *Description*:  
Use this service to authenticate and authorize users to post comments and/or write Blog articles.

## CommentsService
### *Dependencies*: 
* Services: `AuthenticationService`, `DbalService`, `MarkdownService`
* Controllers: `CommentsController`
* Entities: `CommentsEntity`, `RepliesEntity`
* Templates: `Comments.partial.html`
### *Description*:  
Add a commentary feature to a page. Users can add comments and reply to them.

## MarkdownService
### *Dependencies*:
* Services: `PurifyService`
### *Description*:  
Creates and renders HTML from Markdown.

## MenuService
### *Dependencies*:
* Templates: `Menu.partial.html`
### *Description*:  
Creates and renders the menu inside the navigation bar according to the specification in `menu.json`.

## PurifyService
### *Description*:  
Uses HTMLPurifier to remove malicious code.

## TemplateService
### *Description*:  
Parses HTML templates. The Template Engine is build upon the Latte library. Learn more about Latte here: https://latte.nette.org/en/guide


