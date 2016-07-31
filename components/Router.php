<?php
namespace app\components;

class Router {
    private static $instance;
    private function __construct() {

    }
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone() { /* ... @return Singleton */ }  // Защищаем от создания через клонирование
    private function __wakeup() { /* ... @return Singleton */ }  // Защищаем от создания через unserialize

    private function routes() {
        return [
            '/^\/$/' => [
                'controller' => 'app\controllers\SiteController',
                'action' => 'actionIndex'
            ],
            '/^\/site\/register$/' => [
                'controller' => 'app\controllers\SiteController',
                'action' => 'actionRegister'
            ],
            '/^\/site\/login$/' => [
                'controller' => 'app\controllers\SiteController',
                'action' => 'actionLogin'
            ],
            '/^\/site\/logout$/' => [
                'controller' => 'app\controllers\SiteController',
                'action' => 'actionLogout'
            ],
            '/^\/user\/profile\/(\d+)$/' => [
                'controller' => 'app\controllers\UserController',
                'action' => 'actionProfile'
            ],
            '/^\/blog\/index\/(\d+)\/?(\d+)?\/?$/' => [
                'controller' => 'app\controllers\BlogController',
                'action' => 'actionIndex'
            ],
            '/^\/blog\/search\/(\d+)\/?(\d+)?\/?(\w+)\/?$/' => [
                'controller' => 'app\controllers\BlogController',
                'action' => 'actionSearch'
            ],
            '/^\/blog\/add$/' => [
                'controller' => 'app\controllers\BlogController',
                'action' => 'actionAdd',
                'auth' => true,
            ],
        ];
    }

    public static function redirect($url) {
        header("Location: " . $url);
    }

    public function handle($route) {
        $routes = $this->routes();
        foreach(array_keys($routes) as $r) {
            $matches = [];
            if(preg_match($r, $route, $matches)) {
                $className = $routes[$r]['controller'];
                $methodName = $routes[$r]['action'];

                if (class_exists($className)) {
                    $controller = new $className();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName($matches);
                        return;
                    }

                }
            }
        }

        $route = array_values(
            array_filter(explode('/', $route))
        );

        if(count($route) == 2) {
            $className = ucfirst($route[0]) . 'Controller';
            if (class_exists($className)) {
                $controller = new $className;
                $methodName = 'action' . ucfirst($route[1]);
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                    return;
                }
            }
        }


        throw new Exception("No route found.");
    }
}