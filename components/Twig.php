<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 31.07.2016
 * Time: 0:26
 */

namespace app\components;

require_once 'vendor/autoload.php';


class Twig
{
    protected static $instance;

    private $loader;
    private $twig;

    private function __construct() {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
        $this->twig = new \Twig_Environment($this->loader, ['cache' => false, 'debug' => true]);
        $this->twig->addExtension(new \Twig_Extension_Debug());

        $this->twig->addFunction(new \Twig_SimpleFunction('isGuest', function(){
            return !isset($_SESSION['user']);
        }));

        $this->twig->addFunction(new \Twig_SimpleFunction('getUser', function(){
            return [
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'id' => $_SESSION['userId'],
            ];
        }));
    }

    public function render ($template, $array = []) {
        return $this->twig->render($template, $array);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }
}