<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{
    public function index()
    {
        $produto = Container::getModel("Produto");
        $produtos = $produto->getUltimos(5);

        $this->view->quantidade_produtos = count($produtos);
        $this->view->produtos = $produtos;

        $this->sessao();

        if (isset($_SESSION["tentativa_acesso"]) && $_SESSION["tentativa_acesso"]) {
            $this->view->aviso = true;
            unset($_SESSION["tentativa_acesso"]);
        } else {
            $this->view->aviso = false;
        }

        $this->render("index");
    }

    public function entrar()
    {
        $this->autenticarPagina(true);

        if (isset($_GET['hash'])) {
            $this->view->hash = filter_var($_GET['hash']);
        } else {
            $this->view->hash = "";
        }

        $this->render("entrar");
    }

    public function cadastrar()
    {
        $this->autenticarPagina(true);
        $this->render("cadastrar");
    }

    public function redefinir()
    {
        $this->autenticarPagina(true);
        $this->render("redefinir");
    }

    public function novaSenha()
    {
        $this->autenticarPagina(true);

        if (!isset($_GET["token"]) || empty($_GET["token"])) {
            header("Location: /redefinir");
        }

        $this->view->token = $_GET["token"];

        $this->render("nova_senha");
    }

    public function perfil()
    {
        $this->autenticarPagina();

        $usuario = Container::getModel("Usuario");
        $usuario->__set("cod_usuario", $_SESSION["cod_usuario"]);
        $anuncio = Container::getModel("Anuncio");
        $anuncio->__set("cod_usuario", $_SESSION["cod_usuario"]);
        $solicitacao = Container::getModel("Solicitacao");
        $solicitacao->__set("cod_usuario", $_SESSION["cod_usuario"]);

        $this->view->usuario = $usuario->getPerfil();

        if ($this->view->usuario["avaliacoes"] > 0) {
            $this->view->reputacao = intval(round($this->view->usuario["notas"] / $this->view->usuario["avaliacoes"]));
        } else {
            $this->view->reputacao = 0;
        }

        $this->view->anuncios = $anuncio->getAnunciosPorUsuario();
        $this->view->solicitacoes = $solicitacao->getSolicitacoesPorUsuario();

        $this->render("perfil");
    }

    public function atualizarPerfil()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $usuario = Container::getModel("Usuario");
        $usuario->__set("cod_usuario",      $_SESSION["cod_usuario"]);
        $usuario->__set("nome",             $_POST["nome"]);
        $usuario->__set("data_nascimento",  $_POST["data_nascimento"]);
        $usuario->__set("telefone",         $_POST["telefone"]);
        $usuario->__set("instituicao",      $_POST["instituicao"]);

        if (!$usuario->atualizarPerfil()) {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar o perfil de usuário!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function relatorio()
    {
        $this->autenticarPagina(false, true);

        if (count($_POST) > 0) {
            $usuario = Container::getModel("Usuario");

            $this->view->tabela = $usuario->relatorioUsuarios(array_keys($_POST));

            $this->render("relatorio");
        } else {
            $this->render("relatorio_formulario");
        }
    }
}
