<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action {

        public function perfil() {
            $this->autenticarPagina();

            $usuario = Container::getModel("Usuario");

            $this->view->usuario = $usuario->getPerfil();

            $this->render("perfil");
        }

    }

?>