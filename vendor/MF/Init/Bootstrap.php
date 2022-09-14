<?php

    namespace MF\Init;

    use MF\Init\Environment;

    abstract class Bootstrap {
       
        private $routes;

        abstract protected function initRoutes();

        public function __construct() {
            $this->initRoutes();
            $this->run($this->getUrl());
            Environment::load(__DIR__."/../../../");
        }

        public function getRoutes() {
            return $this->routes;
        }

        public function setRoutes(array $routes) {
            $this->routes = $routes;
        }

        protected function run($url) {

            foreach ($this->getRoutes() as $key => $route) {
                if ($route['route'] == $url) {
                    $class = "App\\Controllers\\".$route['controller'];
                    $controller = new $class;
                    $action = $route['action'];
                    $controller->$action();
                    exit;
                }
            }

            header("Location: /");
            exit;
        }

        protected function getUrl() {
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

    }

?>