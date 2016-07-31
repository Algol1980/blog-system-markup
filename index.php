<?php
namespace app;

require_once 'vendor/autoload.php';

use app\components\Router;

session_start();


const PER_PAGE = 5;

spl_autoload_register(function($className){
    $parts = explode("\\", $className);
    array_shift($parts);
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    require_once $path . '.php';
});


Router::getInstance()->handle($_SERVER['REQUEST_URI']);
