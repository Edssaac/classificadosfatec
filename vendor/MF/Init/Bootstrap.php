<?php

namespace MF\Init;

use MF\Init\Environment;

abstract class Bootstrap
{
    private $routes;

    abstract protected function initRoutes();

    public function __construct()
    {
        Environment::load(__DIR__ . "/../../../");
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    protected function run($url)
    {
        foreach ($this->getRoutes() as $route) {
            if (preg_match($route['route'], $url)) {
                $class = "App\\Controllers\\" . $route['controller'];
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
                exit;
            }
        }

        header("Location: /");
        exit;
    }

    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}

?>