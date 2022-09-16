<?php

    namespace App;

    use MF\Init\Bootstrap;

    class Route extends Bootstrap {

        protected function initRoutes() {

            // Home:
            $routes['home'] = array(
                'route'      => '/',
                'controller' => 'IndexController',
                'action'     => 'index'
            );

            $routes['entrar'] = array(
                'route'      => '/entrar',
                'controller' => 'IndexController',
                'action'     => 'entrar'
            );

            $routes['cadastrar'] = array(
                'route'      => '/cadastrar',
                'controller' => 'IndexController',
                'action'     => 'cadastrar'
            );

            $routes['perfil'] = array(
                'route'      => '/perfil',
                'controller' => 'IndexController',
                'action'     => 'perfil'
            );

            $routes['sair'] = array(
                'route'      => '/sair',
                'controller' => 'AuthController',
                'action'     => 'sair'
            );

            // Anunciar:
            // $routes['monitorias'] = array(
            //     'route'      => '/monitorias',
            //     'controller' => 'AnunciarController',
            //     'action'     => 'monitorias'
            // );

            // $routes['produtos'] = array(
            //     'route'      => '/produtos',
            //     'controller' => 'AnunciarController',
            //     'action'     => 'produtos'
            // );

            //Anuncios:

            // Sobre:
            $routes['faleconosco'] = array(
                'route'      => '/faleconosco',
                'controller' => 'SobreController',
                'action'     => 'faleConosco'
            );
            
            $routes['mensagem'] = array(
                'route'      => '/mensagem',
                'controller' => 'SobreController',
                'action'     => 'mensagem'
            );

            $routes['politicas'] = array(
                'route'      => '/politicas',
                'controller' => 'SobreController',
                'action'     => 'politicas'
            );

            $routes['equipe'] = array(
                'route'      => '/equipe',
                'controller' => 'SobreController',
                'action'     => 'equipe'
            );

            $this->setRoutes($routes);
        }

    }

?>