<?php

use PHPSkeleton\Sources\Application;

!defined('ROOT') && define('ROOT', __DIR__);

require ROOT .'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();


# Set Request Params:
# -----------------------------------------
// $_SERVER['REQUEST_URI'] = "/Text/reverse?flip=MeinerEiner";
// $_SERVER['REQUEST_URI'] = "/Text/bold";
$_SERVER['REQUEST_URI'] = "/Data/load";
// $_SERVER['REQUEST_URI'] = "/Arithmetic/multiply";
// $_SERVER['REQUEST_URI'] = "/Arithmetic/add";
// $_SERVER['REQUEST_URI'] = "/Arithmetic/subtract";
// $_SERVER['REQUEST_URI'] = "/";
$_SERVER['REQUEST_METHOD'] = "GET";
$_GET['a'] = 123;
$_GET['b'] = 210;
$_POST['flop'] = "Reverse";
# -----------------------------------------


$app = new Application();
$app->execute();
