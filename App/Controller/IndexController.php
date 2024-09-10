<?php

namespace App\Controller;

use App\Controller;
use App\Model\AnuncioModel;
use App\Model\ProdutoModel;
use App\Model\SolicitacaoModel;
use App\Model\UsuarioModel;

class IndexController extends Controller
{
    public function index()
    {
        $produto = new ProdutoModel();
        $produtos = $produto->getUltimos(5);

        $this->view->quantidade_produtos = count($produtos);
        $this->view->produtos = $produtos;
        $this->view->aviso = false;

        $this->sessao();

        if (isset($_SESSION["tentativa_acesso"]) && $_SESSION["tentativa_acesso"]) {
            $this->view->aviso = true;
            unset($_SESSION["tentativa_acesso"]);
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

        $usuario = new UsuarioModel();
        $anuncio = new AnuncioModel();
        $solicitacao = new SolicitacaoModel();

        $this->view->usuario = $usuario->getPerfil($_SESSION["cod_usuario"]);

        if ($this->view->usuario["avaliacoes"] > 0) {
            $this->view->reputacao = intval(round($this->view->usuario["notas"] / $this->view->usuario["avaliacoes"]));
        } else {
            $this->view->reputacao = 0;
        }

        $this->view->anuncios = $anuncio->getAnunciosPorUsuario($_SESSION["cod_usuario"]);
        $this->view->solicitacoes = $solicitacao->getSolicitacoesPorUsuario($_SESSION["cod_usuario"]);

        $this->render("perfil");
    }

    public function atualizarPerfil()
    {
        $this->autenticarPagina();

        $usuario = new UsuarioModel();

        $_POST['cod_usuario'] = $_SESSION["cod_usuario"];

        if ($usuario->atualizarPerfil($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar o perfil de usuário!";
        }

        $this->output([
            "sucesso" => $sucesso,
            "mensagem" => $this->erro
        ]);
    }

    public function relatorio()
    {
        $this->autenticarPagina(false, true);

        if (!empty($_POST)) {
            $usuario = new UsuarioModel();

            $headers = array_merge(['nome'], array_keys($_POST));

            $this->view->tabela['cabecalhos'] = array_intersect_key([
                'nome'                  => 'Nome',
                'cb_data_nascimento'    => 'Data Nascimento',
                'cb_telefone'           => 'Telefone',
                'cb_email'              => 'Email',
                'cb_data_acesso'        => 'Último Acesso'
            ], array_flip($headers));

            $this->view->tabela['linhas'] = $usuario->relatorioUsuarios($headers);

            $this->render("relatorio");
        } else {
            $this->render("relatorio_formulario");
        }
    }
}
