<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Controller\Upload;
use MF\Model\Container;

class EditarController extends Action
{
    public function solicitado()
    {
        $this->autenticarPagina();

        $solicitacao = Container::getModel("Solicitacao");
        $cod_solicitacao = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[3];
        $this->view->solicitacao = $solicitacao->getSolicitacao($cod_solicitacao);

        if (empty($this->view->solicitacao) || $this->view->solicitacao["cod_usuario"] != $_SESSION["cod_usuario"]) {
            header("Location: /solicitados");
            exit;
        }

        $this->render("solicitado");
    }

    public function editarSolicitacao()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $solicitacao = Container::getModel("Solicitacao");

        $solicitacao->__set("cod_solicitacao",  $_POST["cod_solicitacao"]);
        $solicitacao->__set("titulo",           $_POST["titulo"]);
        $solicitacao->__set("descricao",        $_POST["descricao"]);
        $solicitacao->__set("tipo",             $_POST["tipo"]);
        $solicitacao->__set("data_vencimento",  $_POST["data_vencimento"]);

        if (!$solicitacao->atualizar()) {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar solicitação!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function excluirSolicitacao()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $solicitacao = Container::getModel("Solicitacao");

        $solicitacao->__set("cod_solicitacao",  $_POST["cod_solicitacao"]);

        if (!$solicitacao->excluir()) {
            $sucesso = false;
            $this->erro = "Não foi possível excluir solicitação!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function monitoria()
    {
        $this->autenticarPagina();

        $monitoria = Container::getModel("Monitoria");
        $cod_anuncio = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[3];
        $this->view->monitoria = $monitoria->getMonitoria($cod_anuncio);

        if (empty($this->view->monitoria) || $this->view->monitoria["cod_usuario"] != $_SESSION["cod_usuario"]) {
            header("Location: /monitorias");
            exit;
        }

        $this->view->horarios = json_decode($this->view->monitoria["horarios"], true);

        $this->render("monitoria");
    }

    public function editarMonitoria()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $monitoria = Container::getModel("Monitoria");
        $horarios = [];

        foreach ($_POST["dia_semana"] as $key => $dia) {
            $horarios[$key] = [
                "dia"   => $dia,
                "de"    => $_POST["de_horario"][$key],
                "ate"   => $_POST["ate_horario"][$key]
            ];
        }

        $monitoria->__set("cod_anuncio",    $_POST["cod_anuncio"]);
        $monitoria->__set("titulo",         $_POST["titulo"]);
        $monitoria->__set("descricao",      $_POST["descricao"]);
        $monitoria->__set("valor",          $this->formatarNumero($_POST["valor"]));
        $monitoria->__set("desconto",       $this->formatarNumero($_POST["desconto"]));
        $monitoria->__set("data_desconto",  $_POST["data_desconto"]);
        $monitoria->__set("horarios",       json_encode($horarios));
        $monitoria->__set("materia",        $_POST["materia"]);
        $monitoria->__set("data_vencimento",  $_POST["data_vencimento"]);

        if (!$monitoria->atualizar()) {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar anúncio!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function excluirMonitoria()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $monitoria = Container::getModel("Monitoria");

        $monitoria->__set("cod_anuncio",    $_POST["cod_anuncio"]);

        if (!$monitoria->excluir()) {
            $sucesso = false;
            $this->erro = "Não foi possível excluir a monitoria!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function produto()
    {
        $this->autenticarPagina();

        $produto = Container::getModel("Produto");
        $cod_anuncio = array_filter(explode("/", $_SERVER["REQUEST_URI"]))[3];
        $this->view->produto = $produto->getProduto($cod_anuncio);

        if (empty($this->view->produto) || $this->view->produto["cod_usuario"] != $_SESSION["cod_usuario"]) {
            header("Location: /produtos");
            exit;
        }

        $this->render("produto");
    }

    public function editarProduto()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $produto = Container::getModel("Produto");
        $upload = new Upload();

        $foto = $upload->uploadImagem($_FILES);

        if (!$foto) {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar as informações do anúncio!";
        } else {
            $produto->__set("cod_anuncio",    $_POST["cod_anuncio"]);
            $produto->__set("titulo",         $_POST["titulo"]);
            $produto->__set("descricao",      $_POST["descricao"]);
            $produto->__set("valor",          $this->formatarNumero($_POST["valor"]));
            $produto->__set("desconto",       $this->formatarNumero($_POST["desconto"]));
            $produto->__set("data_desconto",  $_POST["data_desconto"]);
            $produto->__set("foto_name",      $foto["name"]);
            $produto->__set("foto_token",     $foto["sha"]);
            $produto->__set("estado",         $_POST["estado"]);
            $produto->__set("operacao",       $_POST["operacao"]);
            $produto->__set("quantidade",     $_POST["quantidade"]);
            $produto->__set("data_vencimento",  $_POST["data_vencimento"]);

            if (!$produto->atualizar()) {
                $sucesso = false;
                $this->erro = "Não foi possível atualizar o anúncio!";
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function excluirProduto()
    {
        $this->autenticarPagina();

        $sucesso = true;
        $produto = Container::getModel("Produto");

        $produto->__set("cod_anuncio",    $_POST["cod_anuncio"]);

        if (!$produto->excluir()) {
            $sucesso = false;
            $this->erro = "Não foi possível atualizar o produto!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }
}

?>