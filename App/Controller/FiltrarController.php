<?php

namespace App\Controller;

use App\Controller;
use App\Model\MonitoriaModel;
use App\Model\ProdutoModel;
use App\Model\SolicitacaoModel;

class FiltrarController extends Controller
{
    public function filtrarPorMonitoria()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();

        $filters = [];

        if (!empty($_POST['materia'])) {
            $filters['materia'] = $_POST['materia'];
        }

        if (!empty($_POST['dias'])) {
            $filters['dias'] = $_POST['dias'];
        }

        $monitorias = $monitoria->getMonitorias($filters);

        if (empty($monitorias)) {
            $html = "
                <div class='alert alert-primary' role='alert'>
                    Nenhuma monitoria cadastrada corresponde a esse filtro.
                </div>
            ";
        } else {
            $html = $this->montarAnuncio($monitorias, 'monitoria');
        }

        echo $html;
    }

    public function filtrarPorProduto()
    {
        $this->autenticarPagina();

        $produto = new ProdutoModel();

        $filters = [];

        if (!empty($_POST['estado'])) {
            $filters['estado'] = $_POST['estado'];
        }

        if (!empty($_POST['operacao'])) {
            $filters['operacao'] = $_POST['operacao'];
        }

        $produtos = $produto->getProdutos($filters);

        if (empty($produtos)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhum produto cadastrado corresponde a esse filtro.
                </div>";
        } else {
            $html = $this->montarAnuncio($produtos, 'produto');
        }

        echo $html;
    }

    public function filtrarPorSolicitacao()
    {
        $this->autenticarPagina();

        $solicitacao = new SolicitacaoModel();

        $filters = [];

        if (!empty($_POST['tipo'])) {
            $filters['tipo'] = $_POST['tipo'];
        }

        $solicitacoes = $solicitacao->getSolicitacoes($filters);

        if (empty($solicitacoes)) {
            $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhuma solicitação cadastrada corresponde a esse filtro.
                </div>";
        } else {
            $html = $this->montarAnuncio($solicitacoes, 'solicitacao');
        }

        echo $html;
    }

    public function pesquisar()
    {
        $this->autenticarPagina();

        $monitoria = new MonitoriaModel();
        $produto = new ProdutoModel();
        $solicitacao = new SolicitacaoModel();

        $filters = [];

        if (!empty($_POST['pesquisar'])) {
            $filters['titulo'] = $_POST['pesquisar'];
        }

        $monitorias = $monitoria->getMonitorias($filters);
        $produtos = $produto->getProdutos($filters);
        $solicitacoes = $solicitacao->getSolicitacoes($filters);

        $html = $this->montarAnuncio($monitorias, 'monitoria');
        $html .= $this->montarAnuncio($produtos, 'produto');
        $html .= $this->montarAnuncio($solicitacoes, 'solicitacao');

        if (empty($html)) {
            $html = "
                <div class='alert alert-primary' role='alert'>
                    Nenhum anúncio cadastrado corresponde a essa pesquisa.
                </div>
            ";
        }

        $this->view->filtrar = true;
        $this->view->pesquisar = $_POST['pesquisar'] ?? '';
        $this->view->anuncios = $html;

        $this->render('anuncios');
    }

    public function filtrarPorPesquisa()
    {
        $this->autenticarPagina();

        $filters = [];

        if (!empty($_POST['pesquisar'])) {
            $filters['titulo'] = $_POST['pesquisar'];
        }

        $html = '';

        if (isset($_POST['tipo'])) {
            if (in_array('monitoria', $_POST['tipo'])) {
                $monitoria = new MonitoriaModel();
                $monitorias = $monitoria->getMonitorias($filters);
                $html .= $this->montarAnuncio($monitorias, 'monitoria');
            }

            if (in_array('produto', $_POST['tipo'])) {
                $produto = new ProdutoModel();
                $produtos = $produto->getProdutos($filters);
                $html .= $this->montarAnuncio($produtos, 'produto');
            }

            if (in_array('solicitacao', $_POST['tipo'])) {
                $solicitacao = new SolicitacaoModel();
                $solicitacoes = $solicitacao->getSolicitacoes($filters);
                $html .= $this->montarAnuncio($solicitacoes, 'solicitacao');
            }
        }

        if (empty($html)) {
            $html = "
                <div class='alert alert-primary' role='alert'>
                    Nenhum anúncio cadastrado corresponde a essa pesquisa.
                </div>
            ";
        }

        echo $html;
    }

    public function montarAnuncio(array $anuncios, string $tipo): string
    {
        if (empty($anuncios)) {
            return '';
        }

        $html = '';

        switch ($tipo) {
            case 'monitoria':
                foreach ($anuncios as $anuncio) {
                    if ($anuncio['desconto'] && (strtotime($anuncio['data_desconto']) > time())) {
                        $precificacao = "
                            <div class='col-6' title='preço'>
                                <i class='fa-solid fa-dollar-sign'></i>
                                <small>R$</small>
                                <span class='text-danger'>
                                    <s>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'])) . "</s>
                                </span>
                            </div>
                            <div class='col-6' title='promoção'>
                                <i class='fa-solid fa-tag'></i>
                                <small>R$</small>
                                <span class='text-success'>
                                    " . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'] - $anuncio['desconto'])) . "
                                </span>
                            </div>
                        ";
                    } else {
                        $precificacao =
                            "<div class='col-6' title='preço'>
                                <i class='fa-solid fa-dollar-sign'></i>
                                <small>R$</small>
                                <span class='text-success'>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'])) . "</span>
                            </div>
                        ";
                    }

                    if (strlen($anuncio["descricao"]) > 250) {
                        $descricao = "<p class='card-text justificado mb-3'>" . substr($anuncio["descricao"], 0, 250) . "...</p>";
                    } else {
                        $descricao = "<p class='card-text justificado mb-3'>" . $anuncio["descricao"] . "</p>";
                    }

                    $html .= "
                        <div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $anuncio['nome'] . "
                                                <br>
                                                <small>" . $anuncio['data_anunciada'] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-12'>
                                            <div>
                                                <h5 class='card-title'>" . $anuncio["titulo"] . "</h5>
                                                <div class='row my-3 text-center'>
                                                    $precificacao
                                                </div>
                                                <hr>
                                                $descricao
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-footer text-end'>
                                    <div class='text-start'>
                                        <p class='mb-0'><b>Matéria: </b>" . $anuncio["materia"] . "</p>
                                    </div>
                                    <a href='/monitorias/" . $anuncio["cod_anuncio"] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";
                }

                break;

            case 'produto':
                foreach ($anuncios as $anuncio) {
                    if ($anuncio["desconto"] && (strtotime($anuncio["data_desconto"]) > time())) {
                        $precificacao = "
                            <div class='col-6' title='preço'>
                                <i class='fa-solid fa-dollar-sign'></i>
                                <small>R$</small>
                                <span class='text-danger'>
                                    <s>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'])) . "</s>
                                </span>
                            </div>
                            <div class='col-6' title='promoção'>
                                <i class='fa-solid fa-tag'></i>
                                <small>R$</small>
                                <span class='text-success'>
                                    " . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'] - $anuncio['desconto'])) . "
                                </span>
                            </div>
                        ";
                    } else {
                        $precificacao = "
                            <div class='col-6' title='preço'>
                                <i class='fa-solid fa-dollar-sign'></i>
                                <small>R$</small>
                                <span class='text-success'>" . preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $anuncio['valor'])) . "</span>
                            </div>
                        ";
                    }

                    if (strlen($anuncio["descricao"]) > 250) {
                        $descricao = "<p class='card-text justificado mb-3'>" . substr($anuncio['descricao'], 0, 250) . "...</p>";
                    } else {
                        $descricao = "<p class='card-text justificado mb-3'>" . $anuncio['descricao'] . "</p>";
                    }

                    $html .=
                        "<div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $anuncio["nome"] . "
                                                <br>
                                                <small>" . $anuncio["data_anunciada"] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-4 align-self-center'>
                                            <img src='https://raw.githubusercontent.com/Edssaac/cf_storage/main/produtos/" . $anuncio["foto_name"] . "' class='img-fluid rounded m-lg-2' alt='produto'>
                                        </div>
                                        <div class='col-md-8'>
                                            <div>
                                                <h5 class='card-title'>" . $anuncio['titulo'] . "</h5>
                                                <div class='row my-3 text-center'>
                                                    $precificacao
                                                </div>
                                                <hr>
                                                $descricao
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-footer text-end'>
                                    <a href='/produtos/" . $anuncio['cod_anuncio'] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";
                }

                break;

            case 'solicitacao':
                foreach ($anuncios as $anuncio) {
                    if (strlen($anuncio["descricao"]) > 250) {
                        $descricao = "<p class='card-text justificado mb-3'>" . substr($anuncio['descricao'], 0, 250) . "...</p>";
                    } else {
                        $descricao = "<p class='card-text justificado mb-3'>" . $anuncio['descricao'] . "</p>";
                    }

                    $html .= "
                        <div class='col-12 col-lg-6 mb-3'>
                            <div class='card h-100'>
                                <div class='card-header'>
                                    <div class='row align-items-center'>
                                        <div class='col-2'>
                                            <i class='fa-regular fa-circle-user usuario'></i>
                                        </div>
                                        <div class='col-10'>
                                            <p class='my-0'>" . $anuncio['nome'] . "
                                                <br>
                                                <small>" . $anuncio['data'] . "</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-body pb-0'>
                                    <div class='row g-4 text-center h-100'>
                                        <div class='col-md-12'>
                                            <div>
                                                <h5 class='card-title'>" . $anuncio["titulo"] . "</h5>
                                                <hr>
                                                $descricao
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='card-footer text-end'>
                                    <div class='text-start'>
                                        <p class='mb-0'><b>Categoria: </b>" . ucfirst($anuncio['tipo']) . "</p>
                                    </div>
                                    <a href='/solicitados/" . $anuncio["cod_solicitacao"] . "'>
                                        <button type='button' class='button-input text-light'>Ver Mais</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";
                }

                break;
        }

        return $html;
    }
}
