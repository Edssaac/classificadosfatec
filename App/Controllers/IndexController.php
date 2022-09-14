<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action {

        public function index() {
            // $teste = Container::getModel("Teste");
            
            // $this->view->dados = $teste->getAll();
            
            $this->render("index");
        }

    }

?>