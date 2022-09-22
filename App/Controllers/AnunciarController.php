<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AnunciarController extends Action {

        public function produto() {
            //$this->autenticarPagina();

            $this->render("produto");
        }

        public function monitoria() {
            //$this->autenticarPagina();

            $this->render("monitoria");
        }

    }

?>