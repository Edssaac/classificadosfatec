<?php

namespace App\Controller;

use App\Controller;
use App\Model\SolicitacaoModel;
use App\Model\MonitoriaModel;
use App\Model\ProdutoModel;
use Library\Upload;

class EditarController extends Controller
{
    public function solicitado()
    {
        $this->autenticarPagina();

        $solicitacao = new SolicitacaoModel();

        $cod_solicitacao = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->solicitacao = $solicitacao->getSolicitacao($cod_solicitacao);

        if (empty($this->view->solicitacao) || $this->view->solicitacao['cod_usuario'] != $_SESSION['cod_usuario']) {
            header('Location: /solicitados');
            exit;
        }

        $this->render('solicitado');
    }

    public function editarSolicitacao()
    {
        $this->autenticarPagina();

        $solicitacao = new SolicitacaoModel();

        if ($solicitacao->atualizar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível atualizar solicitação!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function excluirSolicitacao()
    {
        $this->autenticarPagina();

        $solicitacao = new SolicitacaoModel();

        if ($solicitacao->excluir($_POST['cod_solicitacao'])) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível excluir solicitação!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function monitoria()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();

        $cod_anuncio = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->monitoria = $monitoria->getMonitoria($cod_anuncio);

        if (empty($this->view->monitoria) || $this->view->monitoria['cod_usuario'] != $_SESSION['cod_usuario']) {
            header('Location: /monitorias');
            exit;
        }

        $this->view->horarios = json_decode($this->view->monitoria['horarios'], true);

        $this->render('monitoria');
    }

    public function editarMonitoria()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();

        $horarios = [];

        foreach ($_POST['dia_semana'] as $key => $dia) {
            $horarios[$key] = [
                'dia'   => $dia,
                'de'    => $_POST['de_horario'][$key],
                'ate'   => $_POST['ate_horario'][$key]
            ];
        }

        $_POST['valor'] = $this->formatarNumero($_POST['valor']);
        $_POST['desconto'] = $this->formatarNumero($_POST['desconto']);
        $_POST['horarios'] = json_encode($horarios);

        if ($monitoria->atualizar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível atualizar anúncio!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function excluirMonitoria()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();

        if ($monitoria->excluir($_POST['cod_anuncio'])) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível excluir a monitoria!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function produto()
    {
        $this->autenticarPagina();

        $produto = new ProdutoModel();

        $cod_anuncio = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->produto = $produto->getProduto($cod_anuncio);

        if (empty($this->view->produto) || $this->view->produto['cod_usuario'] != $_SESSION['cod_usuario']) {
            header('Location: /produtos');
            exit;
        }

        $this->render('produto');
    }

    public function editarProduto()
    {
        $this->autenticarPagina();

        $produto = new ProdutoModel();

        $foto = Upload::image($_FILES);

        $_POST['valor'] = $this->formatarNumero($_POST['valor']);
        $_POST['desconto'] = $this->formatarNumero($_POST['desconto']);
        $_POST['foto_name'] = $foto['name'];
        $_POST['foto_token'] = $foto['sha'];

        if ($produto->atualizar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível atualizar o anúncio!';
        }
        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function excluirProduto()
    {
        $this->autenticarPagina();

        $produto = new ProdutoModel();

        if ($produto->excluir($_POST['cod_anuncio'])) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível atualizar o produto!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }
}
