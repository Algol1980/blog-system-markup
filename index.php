<?php

session_start();

const PER_PAGE = 5;

spl_autoload_register(function($className){
    if(strpos($className, "Controller")) {
        require_once 'controllers' . DIRECTORY_SEPARATOR . $className . '.php';
    } else if(strpos($className, "Model")) {
        require_once 'models' . DIRECTORY_SEPARATOR . $className . '.php';
    } else {
        require_once 'components' . DIRECTORY_SEPARATOR . $className . '.php';
    }
});


Router::getInstance()->handle($_SERVER['REQUEST_URI']);
