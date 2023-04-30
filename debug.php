<?php

!defined('ROOT') && define('ROOT', __DIR__);

require ROOT .'/vendor/autoload.php';

use f7k\Sources\Application;

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();


# Set Request Params:
# -----------------------------------------
$_SERVER['REQUEST_URI'] = "/Test";
$_SERVER['REQUEST_METHOD'] = "GET";
# -----------------------------------------


$app = new Application();
$app->execute();
