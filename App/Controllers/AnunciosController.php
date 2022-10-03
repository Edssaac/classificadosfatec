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

        public function getProduto() {
            $this->autenticarPagina();
            $produto = Container::getModel("Produto");

            $cod_anuncio = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[2];
            $this->view->produto = $produto->getProduto($cod_anuncio);

            if ( !isset($this->view->produto["cod_anuncio"]) ) {
                header("Location: /produtos");
                exit;
            }

            $this->view->estado = [
                1 => "Novo",
                2 => "Seminovo",
                3 => "Usado"
            ];

            $this->view->operacao = [
                "V" => "Venda",
                "T" => "Troca",
                "A" => "Venda/Troca"
            ];
            
            $this->render("produto");
        }

        public function monitorias() {
            $this->autenticarPagina();
            $this->render("monitorias");
        }

    }

?>