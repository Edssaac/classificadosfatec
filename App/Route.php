<?php

namespace App;

class Route
{
    private array $routes;

    public function __construct()
    {
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    private function initRoutes()
    {
        // Home:
        $routes['home'] = array(
            'route'      => "/^\/\/*$/",
            'controller' => 'IndexController',
            'action'     => 'index'
        );

        $routes['entrar'] = array(
            'route'      => "/^\/entrar\/*$/",
            'controller' => 'IndexController',
            'action'     => 'signin'
        );

        $routes['autenticar'] = array(
            'route'      => "/^\/autenticar\/*$/",
            'controller' => 'AuthController',
            'action'     => 'authenticate'
        );

        $routes['redefinir'] = array(
            'route'      => "/^\/redefinir\/*$/",
            'controller' => 'IndexController',
            'action'     => 'recover'
        );

        $routes['redefinir_senha'] = array(
            'route'      => "/^\/redefinir_senha\/*$/",
            'controller' => 'AuthController',
            'action'     => 'recoverPassword'
        );

        $routes['nova_senha'] = array(
            'route'      => "/^\/nova_senha\/*$/",
            'controller' => 'IndexController',
            'action'     => 'resetPassword'
        );

        $routes['cadastrar'] = array(
            'route'      => "/^\/cadastrar\/*$/",
            'controller' => 'IndexController',
            'action'     => 'signup'
        );

        $routes['registrar'] = array(
            'route'      => "/^\/registrar\/*$/",
            'controller' => 'AuthController',
            'action'     => 'register'
        );

        $routes['atualizar_perfil'] = array(
            'route'      => "/^\/atualizar_perfil\/*$/",
            'controller' => 'IndexController',
            'action'     => 'updateProfile'
        );

        $routes['perfil'] = array(
            'route'      => "/^\/perfil\/*$/",
            'controller' => 'IndexController',
            'action'     => 'profile'
        );

        $routes['sair'] = array(
            'route'      => "/^\/sair\/*$/",
            'controller' => 'AuthController',
            'action'     => 'logout'
        );

        $routes['relatorio'] = array(
            'route'      => "/^\/relatorio\/*$/",
            'controller' => 'IndexController',
            'action'     => 'report'
        );


        // Anunciar:
        $routes['produto'] = array(
            'route'      => "/^\/produto\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'product'
        );

        $routes['cadastrar_produto'] = array(
            'route'      => "/^\/cadastrar_produto\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'registerProduct'
        );

        $routes['monitoria'] = array(
            'route'      => "/^\/monitoria\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'tutoring'
        );

        $routes['cadastrar_monitoria'] = array(
            'route'      => "/^\/cadastrar_monitoria\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'registerTutoring'
        );

        $routes['solicitar'] = array(
            'route'      => "/^\/solicitar\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'solicitation'
        );

        $routes['cadastrar_solicitacao'] = array(
            'route'      => "/^\/cadastrar_solicitacao\/*$/",
            'controller' => 'AdvertiseController',
            'action'     => 'registerSolicitation'
        );


        //Anuncios:
        $routes['produtos'] = array(
            'route'      => "/^\/produtos\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'products'
        );

        $routes['get_produtos'] = array(
            'route'      => "/^\/produtos\/(\d+)\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'product'
        );

        $routes['monitorias'] = array(
            'route'      => "/^\/monitorias\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'tutorings'
        );

        $routes['get_monitorias'] = array(
            'route'      => "/^\/monitorias\/(\d+)\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'tutoring'
        );

        $routes['solicitados'] = array(
            'route'      => "/^\/solicitados\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'solicitations'
        );

        $routes['get_solicitados'] = array(
            'route'      => "/^\/solicitados\/(\d+)\/*$/",
            'controller' => 'AdvertisementController',
            'action'     => 'solicitation'
        );

        $routes['duvidas'] = array(
            'route'      => "/^\/duvidas\/*$/",
            'controller' => 'ChatController',
            'action'     => 'getQuestions'
        );

        $routes['comentar_duvida'] = array(
            'route'      => "/^\/comentar_duvida\/*$/",
            'controller' => 'ChatController',
            'action'     => 'question'
        );

        $routes['responder_duvida'] = array(
            'route'      => "/^\/responder_duvida\/*$/",
            'controller' => 'ChatController',
            'action'     => 'answer'
        );

        $routes['avaliacoes'] = array(
            'route'      => "/^\/avaliacoes\/*$/",
            'controller' => 'ChatController',
            'action'     => 'getReviews'
        );

        $routes['avaliar'] = array(
            'route'      => "/^\/avaliar\/*$/",
            'controller' => 'ChatController',
            'action'     => 'rate'
        );

        $routes['comentarios'] = array(
            'route'      => "/^\/comentarios\/*$/",
            'controller' => 'ChatController',
            'action'     => 'getComments'
        );

        $routes['comentar'] = array(
            'route'      => "/^\/comentar\/*$/",
            'controller' => 'ChatController',
            'action'     => 'comment'
        );


        // Editar:
        $routes['editar_produtos'] = array(
            'route'      => "/^\/produtos\/editar\/(\d+)\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'loadProduct'
        );

        $routes['editar_produto'] = array(
            'route'      => "/^\/editar_produto\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'editProduct'
        );

        $routes['excluir_produto'] = array(
            'route'      => "/^\/excluir_produto\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'deleteProduct'
        );

        $routes['editar_monitorias'] = array(
            'route'      => "/^\/monitorias\/editar\/(\d+)\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'loadTutoring'
        );

        $routes['editar_monitoria'] = array(
            'route'      => "/^\/editar_monitoria\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'editTutoring'
        );

        $routes['excluir_monitoria'] = array(
            'route'      => "/^\/excluir_monitoria\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'deleteTutoring'
        );

        $routes['editar_solicitados'] = array(
            'route'      => "/^\/solicitados\/editar\/(\d+)\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'loadSolicitation'
        );

        $routes['editar_solicitacao'] = array(
            'route'      => "/^\/editar_solicitacao\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'editSolicitation'
        );

        $routes['excluir_solicitacao'] = array(
            'route'      => "/^\/excluir_solicitacao\/*$/",
            'controller' => 'ManagerController',
            'action'     => 'deleteSolicitation'
        );


        // Filtrar:
        $routes['filtrar_produto'] = array(
            'route'      => "/^\/filtrar_produto\/*$/",
            'controller' => 'FilterController',
            'action'     => 'filterProducts'
        );

        $routes['filtrar_monitoria'] = array(
            'route'      => "/^\/filtrar_monitoria\/*$/",
            'controller' => 'FilterController',
            'action'     => 'filterTutorings'
        );

        $routes['filtrar_solicitacao'] = array(
            'route'      => "/^\/filtrar_solicitacao\/*$/",
            'controller' => 'FilterController',
            'action'     => 'filterSolicitations'
        );

        $routes['pesquisar'] = array(
            'route'      => "/^\/pesquisar\/*$/",
            'controller' => 'FilterController',
            'action'     => 'search'
        );

        $routes['filtrar_pesquisa'] = array(
            'route'      => "/^\/filtrar_pesquisa\/*$/",
            'controller' => 'FilterController',
            'action'     => 'filterSearch'
        );


        // Sobre:
        $routes['fale_conosco'] = array(
            'route'      => "/^\/fale_conosco\/*$/",
            'controller' => 'AboutController',
            'action'     => 'contact'
        );

        $routes['mensagem'] = array(
            'route'      => "/^\/mensagem\/*$/",
            'controller' => 'AboutController',
            'action'     => 'message'
        );

        $routes['politicas'] = array(
            'route'      => "/^\/politicas\/*$/",
            'controller' => 'AboutController',
            'action'     => 'policies'
        );

        $routes['equipe'] = array(
            'route'      => "/^\/equipe\/*$/",
            'controller' => 'AboutController',
            'action'     => 'team'
        );

        $this->setRoutes($routes);
    }

    private function getRoutes(): array
    {
        return $this->routes;
    }

    private function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    private function run(string $url): void
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            if (preg_match($route['route'], $url)) {
                $class = 'App\\Controller\\' . $route['controller'];
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
                exit;
            }
        }

        header('Location: /');
        exit;
    }

    private function getUrl(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
