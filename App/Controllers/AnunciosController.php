<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AnunciosController extends Action {

        public function produtos() {
            $this->autenticarPagina();
            $produto = Container::getModel("Produto");
            $produtos = $produto->getProdutos();

            $this->view->quantidade_produtos = count($produtos);
            $this->view->produtos = $produtos;

            $this->render("produtos");
        }

        public function monitorias() {
            $this->autenticarPagina();
            $this->render("monitorias");
        }

    }

?>