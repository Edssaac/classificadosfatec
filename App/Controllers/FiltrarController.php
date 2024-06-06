<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class FiltrarController extends Action
{
    public function filtrarPorMonitoria()
    {
        $this->autenticarPagina();
        $monitoria = Container::getModel("Monitoria");

        $materia    = $_POST["materia"] ?? "";
        $dias       = $_POST["dias"] ?? "";

        $monitorias = $monitoria->getMonitoriaFiltrada($materia, $dias);
        $html = "";

        if (empty($monitorias)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhuma monitoria cadastrada corresponde a esse filtro.
                </div>";
        } else {
            $html .= $this->montarAnuncio($monitorias, "M");
        }

        echo $html;
    }

    public function filtrarPorProduto()
    {
        $this->autenticarPagina();
        $produto = Container::getModel("Produto");

        $estado     = $_POST["estado"]   ?? "";
        $operacao   = $_POST["operacao"] ?? "";

        $produtos = $produto->getProdutoFiltrada($estado, $operacao);
        $html = "";

        if (empty($produtos)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhum produto cadastrado corresponde a esse filtro.
                </div>";
        } else {
            $html .= $this->montarAnuncio($produtos, "P");
        }

        echo $html;
    }

    public function filtrarPorSolicitacao()
    {
        $this->autenticarPagina();
        $solicitacao = Container::getModel("Solicitacao");

        $tipo = $_POST["tipo"] ?? "";

        $solicitacoes = $solicitacao->getSolicitacaoFiltrada($tipo);
        $html = "";

        if (empty($solicitacoes)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhuma solicitação cadastrada corresponde a esse filtro.
                </div>";
        } else {
            $html .= $this->montarAnuncio($solicitacoes, "S");
        }

        echo $html;
    }

    public function pesquisar()
    {
        $this->autenticarPagina();

        $pesquisar = $_POST["pesquisar"] ?? "";
        $html = "";

        if (!empty($pesquisar)) {
            $monitoria = Container::getModel("Monitoria");
            $produto = Container::getModel("Produto");
            $solicitacao = Container::getModel("Solicitacao");

            $pesquisar = filter_var($pesquisar);

            $monitorias = $monitoria->getMonitoriaFiltrada(null, null, $pesquisar);
            $produtos = $produto->getProdutoFiltrada(null, null, $pesquisar);
            $solicitacoes = $solicitacao->getSolicitacaoFiltrada(null, $pesquisar);

            $html .= $this->montarAnuncio($monitorias, "M");
            $html .= $this->montarAnuncio($produtos, "P");
            $html .= $this->montarAnuncio($solicitacoes, "S");
        }

        if (empty($html)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhum anúncio cadastrado corresponde a essa pesquisa.
                </div>";
        }

        $this->view->filtrar = true;
        $this->view->pesquisar = $pesquisar;
        $this->view->anuncios = $html;

        $this->render("anuncios");
    }

    public function filtrarPorPesquisa()
    {
        $this->autenticarPagina();

        $pesquisar = $_POST["filtrar"] ?? "";
        $html = "";

        if (!empty($pesquisar)) {
            $pesquisar = filter_var($pesquisar);

            if (in_array("M", $_POST["tipo"])) {
                $monitoria = Container::getModel("Monitoria");
                $monitorias = $monitoria->getMonitoriaFiltrada(null, null, $pesquisar);
                $html .= $this->montarAnuncio($monitorias, "M");
            }

            if (in_array("P", $_POST["tipo"])) {
                $produto = Container::getModel("Produto");
                $produtos = $produto->getProdutoFiltrada(null, null, $pesquisar);
                $html .= $this->montarAnuncio($produtos, "P");
            }

            if (in_array("S", $_POST["tipo"])) {
                $solicitacao = Container::getModel("Solicitacao");
                $solicitacoes = $solicitacao->getSolicitacaoFiltrada(null, $pesquisar);
                $html .= $this->montarAnuncio($solicitacoes, "S");
            }
        }

        if (empty($html)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhum anúncio cadastrado corresponde a essa pesquisa.
                </div>";
        }

        echo $html;
    }

    public function montarAnuncio($anuncios, $tipo)
    {
        if (empty($anuncios) || !is_array($anuncios)) {
            return "";
        }

        $html = "";

        switch ($tipo) {
            case 'M':
                foreach ($anuncios as $a) {
                    $html .=
                        "<div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $a["nome"] . "
                                                <br>
                                                <small>" . $a["data_anunciada"] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-12'>
                                            <div>
                                                <h5 class='card-title'>" . $a["titulo"] . "</h5>
    
                                                <div class='row my-3 text-center'>";

                    if ($a["desconto"] && (strtotime($a["data_desconto"]) > time())) {
                        $html .=
                            "<div class='col-6' title='preço'>
                                                            <i class='fa-solid fa-dollar-sign'></i>
                                                            <small>R$</small>
                                                            <span class='text-danger'>
                                                                <s>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"])) . "</s>
                                                            </span>
                                                        </div>
                                                        <div class='col-6' title='promoção'>
                                                            <i class='fa-solid fa-tag'></i>
                                                            <small>R$</small>
                                                            <span class='text-success'>
                                                                " . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"] - $a["desconto"])) . "
                                                            </span>
                                                        </div>";
                    } else {
                        $html .=
                            "<div class='col-6' title='preço'>
                                                            <i class='fa-solid fa-dollar-sign'></i>
                                                            <small>R$</small>
                                                            <span class='text-success'>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"])) . "</span>
                                                        </div>";
                    }

                    $html .=
                        "</div>
                                                <hr>";

                    if (strlen($a["descricao"]) > 250) {
                        $html .= "<p class='card-text justificado mb-3'>" . substr($a["descricao"], 0, 250) . "...</p>";
                    } else {
                        $html .= "<p class='card-text justificado mb-3'>" . $a["descricao"] . "</p>";
                    }

                    $html .=
                        "</div>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-footer text-end'>
                                    <div class='text-start'>
                                        <p class='mb-0'><b>Matéria: </b>" . $a["materia"] . "</p>
                                    </div>
                                    <a href='/monitorias/" . $a["cod_anuncio"] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>";
                }
                break;

            case 'P':
                foreach ($anuncios as $a) {
                    $html .=
                        "<div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $a["nome"] . "
                                                <br>
                                                <small>" . $a["data_anunciada"] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-4 align-self-center'>
                                            <img src='https://raw.githubusercontent.com/Edssaac/cf_storage/main/produtos/" . $a["foto_name"] . "' class='img-fluid rounded m-lg-2' alt='produto'>
                                        </div>
                                        <div class='col-md-8'>
                                            <div>
                                                <h5 class='card-title'>" . $a["titulo"] . "</h5>
    
                                                <div class='row my-3 text-center'>";

                    if ($a["desconto"] && (strtotime($a["data_desconto"]) > time())) {
                        $html .=
                            "<div class='col-6' title='preço'>
                                                            <i class='fa-solid fa-dollar-sign'></i>
                                                            <small>R$</small>
                                                            <span class='text-danger'>
                                                                <s>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"])) . "</s>
                                                            </span>
                                                        </div>
                                                        <div class='col-6' title='promoção'>
                                                            <i class='fa-solid fa-tag'></i>
                                                            <small>R$</small>
                                                            <span class='text-success'>
                                                                " . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"] - $a["desconto"])) . "
                                                            </span>
                                                        </div>";
                    } else {
                        $html .=
                            "<div class='col-6' title='preço'>
                                                            <i class='fa-solid fa-dollar-sign'></i>
                                                            <small>R$</small>
                                                            <span class='text-success'>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $a["valor"])) . "</span>
                                                        </div>";
                    }

                    $html .=
                        "</div>
                                                <hr>";

                    if (strlen($a["descricao"]) > 250) {
                        $html .= "<p class='card-text justificado mb-3'>" . substr($a["descricao"], 0, 250) . "...</p>";
                    } else {
                        $html .= "<p class='card-text justificado mb-3'>" . $a["descricao"] . "</p>";
                    }

                    $html .=
                        "</div>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-footer text-end'>
                                    <a href='/produtos/" . $a["cod_anuncio"] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>";
                }
                break;

            case 'S':
                $categorias = [
                    "P" => "Produto",
                    "M" => "Monitoria",
                ];

                foreach ($anuncios as $a) {
                    $html .=
                        "<div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $a["nome"] . "
                                                <br>
                                                <small>" . $a["data"] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-12'>
                                            <div>
                                                <h5 class='card-title'>" . $a["titulo"] . "</h5>";

                    $html .= "<hr>";

                    if (strlen($a["descricao"]) > 250) {
                        $html .= "<p class='card-text justificado mb-3'>" . substr($a["descricao"], 0, 250) . "...</p>";
                    } else {
                        $html .= "<p class='card-text justificado mb-3'>" . $a["descricao"] . "</p>";
                    }

                    $html .=
                        "</div>
                                        </div>
                                    </div>
                                </div>
    
                                <div class='card-footer text-end'>
                                    <div class='text-start'>
                                        <p class='mb-0'><b>Categoria: </b>" . $categorias[$a["tipo"]] . "</p>
                                    </div>
                                    <a href='/solicitados/" . $a["cod_solicitacao"] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>";
                }
                break;
        }

        return $html;
    }
}

?>