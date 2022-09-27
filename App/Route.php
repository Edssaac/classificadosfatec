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

            $routes['autenticar'] = array(
                'route'      => '/autenticar',
                'controller' => 'AuthController',
                'action'     => 'autenticar'
            );

            $routes['redefinir'] = array(
                'route'      => '/redefinir',
                'controller' => 'IndexController',
                'action'     => 'redefinir'
            );

            $routes['redefinir_senha'] = array(
                'route'      => '/redefinir_senha',
                'controller' => 'AuthController',
                'action'     => 'redefinirSenha'
            );

            $routes['nova_senha'] = array(
                'route'      => '/nova_senha',
                'controller' => 'IndexController',
                'action'     => 'novaSenha'
            );

            $routes['cadastrar'] = array(
                'route'      => '/cadastrar',
                'controller' => 'IndexController',
                'action'     => 'cadastrar'
            );

            $routes['registrar'] = array(
                'route'      => '/registrar',
                'controller' => 'AuthController',
                'action'     => 'registrar'
            );

            $routes['perfil'] = array(
                'route'      => '/perfil',
                'controller' => 'AppController',
                'action'     => 'perfil'
            );

            $routes['sair'] = array(
                'route'      => '/sair',
                'controller' => 'AuthController',
                'action'     => 'sair'
            );


            // Anunciar:
            $routes['produto'] = array(
                'route'      => '/produto',
                'controller' => 'AnunciarController',
                'action'     => 'produto'
            );

            $routes['cadastrar_produto'] = array(
                'route'      => '/cadastrar_produto',
                'controller' => 'AnunciarController',
                'action'     => 'cadastrar_produto'
            );

            $routes['monitoria'] = array(
                'route'      => '/monitoria',
                'controller' => 'AnunciarController',
                'action'     => 'monitoria'
            );

            $routes['cadastrar_monitoria'] = array(
                'route'      => '/cadastrar_monitoria',
                'controller' => 'AnunciarController',
                'action'     => 'cadastrar_monitoria'
            );


            //Anuncios:
            $routes['produtos'] = array(
                'route'      => '/produtos',
                'controller' => 'AnunciosController',
                'action'     => 'produtos'
            );

            $routes['monitorias'] = array(
                'route'      => '/monitorias',
                'controller' => 'AnunciosController',
                'action'     => 'monitorias'
            );


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