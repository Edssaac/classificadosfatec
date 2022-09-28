<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action {

        public function index() {
            $produto = Container::getModel("Produto");
            $produtos = $produto->getUltimos(5);

            $this->view->quantidade_produtos = count($produtos);
            $this->view->produtos = $produtos;

            $this->sessao();

            if ( isset($_SESSION["tentativa_acesso"]) && $_SESSION["tentativa_acesso"] ) {
                $this->view->aviso = true;
                unset($_SESSION["tentativa_acesso"]);
            } else {
                $this->view->aviso = false;
            }

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

        public function redefinir() {
            $this->autenticarPagina(true);
            $this->render("redefinir");
        }

        public function novaSenha() {
            $this->autenticarPagina(true);

            if ( !isset($_GET["token"]) || empty($_GET["token"]) ) {
                header("Location: /redefinir");
            }

            $this->view->token = $_GET["token"];

            $this->render("nova_senha");
        }

    }

?>