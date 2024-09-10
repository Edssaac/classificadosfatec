<?php

namespace App\Controller;

use App\Controller;
use App\Model\MonitoriaModel;
use App\Model\ProdutoModel;
use App\Model\SolicitacaoModel;
use Library\Upload;

class AnunciarController extends Controller
{
    public function produto()
    {
        $this->autenticarPagina();
        $this->render("produto");
    }

    public function monitoria()
    {
        $this->autenticarPagina();
        $this->render("monitoria");
    }

    public function solicitar()
    {
        $this->autenticarPagina();
        $this->render("solicitar");
    }

    public function cadastrarMonitoria()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();
        $horarios = [];

        foreach ($_POST["dia_semana"] as $key => $dia) {
            $horarios[$key] = [
                'dia'   => $dia,
                'de'    => $_POST['de_horario'][$key],
                'ate'   => $_POST['ate_horario'][$key]
            ];
        }

        $_POST['cod_usuario'] = $_SESSION['cod_usuario'];
        $_POST['valor'] = $this->formatarNumero($_POST['valor']);
        $_POST['desconto'] = $this->formatarNumero($_POST['desconto']);
        $_POST['horarios'] = json_encode($horarios);

        if ($monitoria->anunciar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = "Não foi possível cadastrar anúncio!";
        }

        $this->output([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function cadastrarProduto()
    {
        $this->autenticarPagina();

        $produto = new ProdutoModel();

        $foto = Upload::image($_FILES);

        $_POST['cod_usuario'] = $_SESSION['cod_usuario'];
        $_POST['valor'] = $this->formatarNumero($_POST['valor']);
        $_POST['desconto'] = $this->formatarNumero($_POST['desconto']);
        $_POST['foto_name'] = $foto["name"];
        $_POST['foto_token'] = $foto["sha"];

        if ($produto->anunciar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = "Não foi possível cadastrar anúncio!";
        }

        $this->output([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function cadastrarSolicitacao()
    {
        $this->autenticarPagina();

        $solicitacao = new SolicitacaoModel();

        $_POST['cod_usuario'] = $_SESSION["cod_usuario"];

        if ($solicitacao->solicitar($_POST)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = "Não foi possível cadastrar solicitação!";
        }

        $this->output([
            "sucesso" => $sucesso,
            "mensagem" => $this->erro
        ]);
    }
}
