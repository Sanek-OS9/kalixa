<?php
namespace app\core;

use \app\core\App;

class Router{
    public static $routes = [];
    public static $route;

    public static function get(string $pattern, string $run)
    {
        self::add($pattern, $run, 'GET');
    }

    public static function post(string $pattern, string $run)
    {
        self::add($pattern, $run, 'POST');
    }

    private static function add(string $pattern, string $run, string $method = 'GET')
    {
        self::$routes[] = ['pattern' => AVAILABLE_LANG . $pattern, 'run' => $run, 'method' => $method];
    }
    private static function matchRoute()
    {
        $routes_default = App::config('routes', true);
        $routes = array_merge($routes_default, self::$routes);
        foreach ($routes as $route) {
            # сравниваем метод передачи данных
            if (strpos($route['method'], $_SERVER['REQUEST_METHOD']) === false) {
                continue;
            }
            if (preg_match('#^' . $route['pattern'] . '$#i', App::getURI(), $matches)) {
                $run = explode('@', $route['run']);
                $route['controller'] = $run[0];
                $route['action'] = $run[1];
                /*
                * планировалось что нужные параметры будут с указанными строчными ключами
                * но не срослось...
                $arr = array_filter($matches, function($v, $k){
                    if (is_string($k)) {
                        return $v;
                    }
                }, ARRAY_FILTER_USE_BOTH);*/
                unset($route['method']); // метод передачи данных, убираем его
                unset($route['run']); // контроллер/экшен, убираем его
                unset($route['pattern']); // паттерн не нужен, убираем его
                unset($matches[0]); // тут строка в которой найдено совпадение, тоже убираем
                unset($matches[1]); // тут локализация в параметре, она не нужна
                self::$route = array_merge($route, $matches);
                return true;
            }
        }
        return false;
    }
    public static function dispatch()
    {
        if (self::matchRoute()) {
            $controller = '\app\http\controllers\\' . ucfirst(array_shift(self::$route)) . 'Controller';
            $action = ucfirst(array_shift(self::$route));

            $obj = new $controller;

            call_user_func_array([$obj, $action], self::$route);
        } else {
            throw new \Exception('Route not exists');
            http_response_code(404);
        }
    }
    public static function getRoutes()
    {
        return self::$routes;
    }
}