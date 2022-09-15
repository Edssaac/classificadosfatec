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