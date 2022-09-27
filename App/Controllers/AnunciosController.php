<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AnunciosController extends Action {

        public function produtos() {

            $this->render("produtos");
        }

        public function monitorias() {

            $this->render("monitorias");
        }

    }

?>