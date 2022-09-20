<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action {

        public function index() {
            $this->render("index");
        }

        public function entrar() {
            $this->autenticarPagina(true);
            $this->render("entrar");
        }

        public function cadastrar() {
            $this->autenticarPagina(true);
            $this->render("cadastrar");
        }

    }

?>