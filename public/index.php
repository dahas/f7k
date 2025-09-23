<?php

!defined('ROOT') && define('ROOT', dirname(__DIR__, 1));

require ROOT . '/vendor/autoload.php';

use f7k\Sources\Application;

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();

//$cookie = new \f7k\Sources\Cookie("biscuit");
//$cookie->set("color", "black");
//$cookie->set("taste", "sweet");
//
//print_r($_COOKIE["biscuit"]); exit();

$app = new Application();
$app->execute();