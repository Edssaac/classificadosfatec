<?php

    namespace MF\Controller;

    use stdClass;

    abstract class Action {
        
        protected $view;
        protected $erro;

        public function __construct() {
            $this->view = new stdClass();
        }

        protected function render($view, $layout="layout") {
            $this->view->page = $view;

            $this->view->login = $this->validaSessao();
            
            if (file_exists("../App/Views/{$layout}.phtml")) {
                require_once("../App/Views/{$layout}.phtml");
            } else {
                $this->content();
            }
        }

        protected function content() {
            $class = get_class($this);
            $class = str_replace("App\\Controllers\\", "", $class);
            $class = str_replace("Controller", "", $class);
            $class = strtolower($class);

            if (file_exists("../App/Views/{$class}/{$this->view->page}.phtml")) {
                require_once("../App/Views/{$class}/{$this->view->page}.phtml");
            }
        }

        public function validaSessao() {
            if ( session_status() !== PHP_SESSION_ACTIVE ) {
                session_start();
            }

            if ( !isset($_SESSION["cod_usuario"]) || empty($_SESSION["cod_usuario"]) ) {
                return false;
            }

            return true;
        }

        public function autenticarPagina( $index = false ) {
            if ( $this->validaSessao() && $index ) {
                header("Location: /");
                exit;
            } else if ( !$this->validaSessao() && !$index ) {
                header("Location: /");
                exit;
            }
        }

    }

?>