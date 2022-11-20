<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AnunciosController extends Action
{
    public function produtos()
    {
        $this->autenticarPagina();
        $produto = Container::getModel("Produto");
        $produtos = $produto->getProdutos();

        $this->view->quantidade_produtos = count($produtos);
        $this->view->produtos = $produtos;

        $this->view->filtrar = true;

        $this->render("produtos");
    }

    public function produto()
    {
        $this->autenticarPagina();
        $produto = Container::getModel("Produto");

        $cod_anuncio = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[2];
        $this->view->produto = $produto->getProduto($cod_anuncio);

        if (!isset($this->view->produto["cod_anuncio"])) {
            header("Location: /produtos");
            exit;
        }

        $this->view->estado = [
            1 => "Novo",
            2 => "Seminovo",
            3 => "Usado"
        ];

        $this->view->operacao = [
            "V" => "Venda",
            "T" => "Troca",
            "A" => "Venda/Troca"
        ];

        $this->render("produto");
    }

    public function monitorias()
    {
        $this->autenticarPagina();
        $monitoria = Container::getModel("Monitoria");
        $monitorias = $monitoria->getMonitorias();

        $this->view->quantidade_monitorias = count($monitorias);
        $this->view->monitorias = $monitorias;

        $this->view->filtrar = true;

        $this->render("monitorias");
    }

    public function monitoria()
    {
        $this->autenticarPagina();
        $monitoria = Container::getModel("Monitoria");

        $cod_anuncio = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[2];
        $this->view->monitoria = $monitoria->getMonitoria($cod_anuncio);

        if (!isset($this->view->monitoria["cod_anuncio"])) {
            header("Location: /monitorias");
            exit;
        }

        $this->view->horarios = json_decode($this->view->monitoria["horarios"], true);
        $this->view->dias = [
            1 => "Domingo",
            2 => "Segunda",
            3 => "Terça",
            4 => "Quarta",
            5 => "Quinta",
            6 => "Sexta",
            7 => "Sábado"
        ];

        $this->render("monitoria");
    }

    public function solicitados()
    {
        $this->autenticarPagina();
        $solicitacao = Container::getModel("Solicitacao");
        $solicitados = $solicitacao->getSolicitacoes();

        $this->view->quantidade_solicitados = count($solicitados);
        $this->view->solicitados = $solicitados;

        $this->view->categorias = [
            "P" => "Produto",
            "M" => "Monitoria",
        ];

        $this->view->filtrar = true;

        $this->render("solicitados");
    }

    public function solicitado()
    {
        $this->autenticarPagina();
        $solicitacao = Container::getModel("Solicitacao");

        $cod_solicitacao = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[2];
        $this->view->solicitacao = $solicitacao->getSolicitacao($cod_solicitacao);

        if (!isset($this->view->solicitacao["cod_solicitacao"])) {
            header("Location: /solicitados");
            exit;
        }

        $this->view->categorias = [
            "P" => "Produto",
            "M" => "Monitoria",
        ];

        $this->render("solicitado");
    }
}

?>