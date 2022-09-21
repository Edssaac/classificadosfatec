<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AnunciarController extends Action {

        public function produtos() {
            //$this->autenticarPagina();

            $this->render("produtos");
        }

        public function monitorias() {
            //$this->autenticarPagina();

            $this->render("monitorias");
        }

    }

?>