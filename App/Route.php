<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{
    protected function initRoutes()
    {
        // Home:
        $routes["home"] = array(
            "route"      => "/^\/\/*$/",
            "controller" => "IndexController",
            "action"     => "index"
        );

        $routes["entrar"] = array(
            "route"      => "/^\/entrar\/*$/",
            "controller" => "IndexController",
            "action"     => "entrar"
        );

        $routes["autenticar"] = array(
            "route"      => "/^\/autenticar\/*$/",
            "controller" => "AuthController",
            "action"     => "autenticar"
        );

        $routes["redefinir"] = array(
            "route"      => "/^\/redefinir\/*$/",
            "controller" => "IndexController",
            "action"     => "redefinir"
        );

        $routes["redefinir_senha"] = array(
            "route"      => "/^\/redefinir_senha\/*$/",
            "controller" => "AuthController",
            "action"     => "redefinirSenha"
        );

        $routes["nova_senha"] = array(
            "route"      => "/^\/nova_senha\/*$/",
            "controller" => "IndexController",
            "action"     => "novaSenha"
        );

        $routes["cadastrar"] = array(
            "route"      => "/^\/cadastrar\/*$/",
            "controller" => "IndexController",
            "action"     => "cadastrar"
        );

        $routes["registrar"] = array(
            "route"      => "/^\/registrar\/*$/",
            "controller" => "AuthController",
            "action"     => "registrar"
        );

        $routes["atualziar_perfil"] = array(
            "route"      => "/^\/atualziar_perfil\/*$/",
            "controller" => "IndexController",
            "action"     => "atualizarPerfil"
        );

        $routes["perfil"] = array(
            "route"      => "/^\/perfil\/*$/",
            "controller" => "IndexController",
            "action"     => "perfil"
        );

        $routes["sair"] = array(
            "route"      => "/^\/sair\/*$/",
            "controller" => "AuthController",
            "action"     => "sair"
        );

        $routes["relatorio"] = array(
            "route"      => "/^\/relatorio\/*$/",
            "controller" => "IndexController",
            "action"     => "relatorio"
        );


        // Anunciar:
        $routes["produto"] = array(
            "route"      => "/^\/produto\/*$/",
            "controller" => "AnunciarController",
            "action"     => "produto"
        );

        $routes["cadastrar_produto"] = array(
            "route"      => "/^\/cadastrar_produto\/*$/",
            "controller" => "AnunciarController",
            "action"     => "cadastrarProduto"
        );

        $routes["monitoria"] = array(
            "route"      => "/^\/monitoria\/*$/",
            "controller" => "AnunciarController",
            "action"     => "monitoria"
        );

        $routes["cadastrar_monitoria"] = array(
            "route"      => "/^\/cadastrar_monitoria\/*$/",
            "controller" => "AnunciarController",
            "action"     => "cadastrarMonitoria"
        );

        $routes["solicitar"] = array(
            "route"      => "/^\/solicitar\/*$/",
            "controller" => "AnunciarController",
            "action"     => "solicitar"
        );

        $routes["cadastrar_solicitacao"] = array(
            "route"      => "/^\/cadastrar_solicitacao\/*$/",
            "controller" => "AnunciarController",
            "action"     => "cadastrarSolicitacao"
        );


        //Anuncios:
        $routes["produtos"] = array(
            "route"      => "/^\/produtos\/*$/",
            "controller" => "AnunciosController",
            "action"     => "produtos"
        );

        $routes["get_produtos"] = array(
            "route"      => "/^\/produtos\/(\d+)\/*$/",
            "controller" => "AnunciosController",
            "action"     => "produto"
        );

        $routes["monitorias"] = array(
            "route"      => "/^\/monitorias\/*$/",
            "controller" => "AnunciosController",
            "action"     => "monitorias"
        );

        $routes["get_monitorias"] = array(
            "route"      => "/^\/monitorias\/(\d+)\/*$/",
            "controller" => "AnunciosController",
            "action"     => "monitoria"
        );

        $routes["solicitados"] = array(
            "route"      => "/^\/solicitados\/*$/",
            "controller" => "AnunciosController",
            "action"     => "solicitados"
        );

        $routes["get_solicitados"] = array(
            "route"      => "/^\/solicitados\/(\d+)\/*$/",
            "controller" => "AnunciosController",
            "action"     => "solicitado"
        );

        $routes["duvidas"] = array(
            "route"      => "/^\/duvidas\/*$/",
            "controller" => "ChatController",
            "action"     => "getDuvidas"
        );

        $routes["comentar_duvida"] = array(
            "route"      => "/^\/comentar_duvida\/*$/",
            "controller" => "ChatController",
            "action"     => "comentarDuvida"
        );

        $routes["responder_duvida"] = array(
            "route"      => "/^\/responder_duvida\/*$/",
            "controller" => "ChatController",
            "action"     => "responderDuvida"
        );

        $routes["avaliacoes"] = array(
            "route"      => "/^\/avaliacoes\/*$/",
            "controller" => "ChatController",
            "action"     => "getAvaliacoes"
        );

        $routes["avaliar"] = array(
            "route"      => "/^\/avaliar\/*$/",
            "controller" => "ChatController",
            "action"     => "avaliar"
        );

        $routes["comentarios"] = array(
            "route"      => "/^\/comentarios\/*$/",
            "controller" => "ChatController",
            "action"     => "getComentarios"
        );

        $routes["comentar"] = array(
            "route"      => "/^\/comentar\/*$/",
            "controller" => "ChatController",
            "action"     => "comentar"
        );

        
        // Editar:
        $routes["editar_solicitados"] = array(
            "route"      => "/^\/solicitados\/editar\/(\d+)\/*$/",
            "controller" => "EditarController",
            "action"     => "solicitado"
        );

        $routes["editar_solicitacao"] = array(
            "route"      => "/^\/editar_solicitacao\/*$/",
            "controller" => "EditarController",
            "action"     => "editarSolicitacao"
        );

        $routes["excluir_solicitacao"] = array(
            "route"      => "/^\/excluir_solicitacao\/*$/",
            "controller" => "EditarController",
            "action"     => "excluirSolicitacao"
        );

        $routes["editar_monitorias"] = array(
            "route"      => "/^\/monitorias\/editar\/(\d+)\/*$/",
            "controller" => "EditarController",
            "action"     => "monitoria"
        );

        $routes["editar_monitoria"] = array(
            "route"      => "/^\/editar_monitoria\/*$/",
            "controller" => "EditarController",
            "action"     => "editarMonitoria"
        );

        $routes["excluir_monitoria"] = array(
            "route"      => "/^\/excluir_monitoria\/*$/",
            "controller" => "EditarController",
            "action"     => "excluirMonitoria"
        );

        $routes["editar_produtos"] = array(
            "route"      => "/^\/produtos\/editar\/(\d+)\/*$/",
            "controller" => "EditarController",
            "action"     => "produto"
        );

        $routes["editar_produto"] = array(
            "route"      => "/^\/editar_produto\/*$/",
            "controller" => "EditarController",
            "action"     => "editarProduto"
        );

        $routes["excluir_produto"] = array(
            "route"      => "/^\/excluir_produto\/*$/",
            "controller" => "EditarController",
            "action"     => "excluirProduto"
        );


        // Filtrar:
        $routes["filtrar_monitoria"] = array(
            "route"      => "/^\/filtrar_monitoria\/*$/",
            "controller" => "FiltrarController",
            "action"     => "filtrarPorMonitoria"
        );

        $routes["filtrar_produto"] = array(
            "route"      => "/^\/filtrar_produto\/*$/",
            "controller" => "FiltrarController",
            "action"     => "filtrarPorProduto"
        );

        $routes["filtrar_solicitacao"] = array(
            "route"      => "/^\/filtrar_solicitacao\/*$/",
            "controller" => "FiltrarController",
            "action"     => "filtrarPorSolicitacao"
        );

        $routes["pesquisar"] = array(
            "route"      => "/^\/pesquisar\/*$/",
            "controller" => "FiltrarController",
            "action"     => "pesquisar"
        );

        $routes["filtrar_pesquisa"] = array(
            "route"      => "/^\/filtrar_pesquisa\/*$/",
            "controller" => "FiltrarController",
            "action"     => "filtrarPorPesquisa"
        );


        // Sobre:
        $routes["faleconosco"] = array(
            "route"      => "/^\/faleconosco\/*$/",
            "controller" => "SobreController",
            "action"     => "faleConosco"
        );

        $routes["mensagem"] = array(
            "route"      => "/^\/mensagem\/*$/",
            "controller" => "SobreController",
            "action"     => "mensagem"
        );

        $routes["politicas"] = array(
            "route"      => "/^\/politicas\/*$/",
            "controller" => "SobreController",
            "action"     => "politicas"
        );

        $routes["equipe"] = array(
            "route"      => "/^\/equipe\/*$/",
            "controller" => "SobreController",
            "action"     => "equipe"
        );

        $this->setRoutes($routes);
    }
}

?>