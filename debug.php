<?php

use f7k\Sources\Application;

!defined('ROOT') && define('ROOT', __DIR__);

require ROOT .'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();


# Set Request Params:
# -----------------------------------------
$_SERVER['REQUEST_URI'] = "/";
$_SERVER['REQUEST_METHOD'] = "get";

$_POST['title'] = "This is the Title";
$_POST['comment'] = "And here comes a really sweet comment. <script>alert('XSS')</script>";
$_POST['name'] = 'Frank Drebin';
$_POST['email'] = "f.drebin@lapd.org";
# -----------------------------------------


$app = new Application();
$app->execute();
