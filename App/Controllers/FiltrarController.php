<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class FiltrarController extends Action {

        public function filtrarPorMonitoria() {
            $this->autenticarPagina();
            $monitoria = Container::getModel("Monitoria");

            $materia    = $_POST["materia"] ?? "";
            $dias       = $_POST["dias"] ?? "";
            
            $monitorias = $monitoria->getMonitoriaFiltrada($materia, $dias);
            $html = "";

            if ( empty($monitorias) ) {
                $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhuma monitoria cadastrada corresponde a esse filtro.
                </div>";
            } else {
                foreach ($monitorias as $m) {
                    $html .= 
                    "<div class='col-12 col-lg-6 mb-3'>
                        <div class='card h-100'>
                            <div class='card-header'>
                                <div class='row align-items-center'>
                                    <div class='col-2'>
                                        <i class='fa-regular fa-circle-user usuario'></i>
                                    </div>
                                    <div class='col-10'>
                                        <p class='my-0'>". $m["nome"] ."
                                            <br>
                                            <small>". $m["data_anunciada"] ."</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class='card-body pb-0'>
                                <div class='row g-4 text-center h-100'>
                                    <div class='col-md-12'>
                                        <div>
                                            <h5 class='card-title'>". $m["titulo"] ."</h5>

                                            <div class='row my-3 text-center'>";

                                                if ($m["desconto"] && (strtotime($m["data_desconto"]) > time())) {
                                                    $html .=
                                                    "<div class='col-6' title='preço'>
                                                        <i class='fa-solid fa-dollar-sign'></i>
                                                        <small>R$</small>
                                                        <span class='text-danger'>
                                                            <s>". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $m["valor"])) ."</s>
                                                        </span>
                                                    </div>
                                                    <div class='col-6' title='promoção'>
                                                        <i class='fa-solid fa-tag'></i>
                                                        <small>R$</small>
                                                        <span class='text-success'>
                                                            ". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $m["valor"] - $m["desconto"])) ."
                                                        </span>
                                                    </div>";
                                                } else {
                                                    $html .= 
                                                    "<div class='col-6' title='preço'>
                                                        <i class='fa-solid fa-dollar-sign'></i>
                                                        <small>R$</small>
                                                        <span class='text-success'>". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $m["valor"])) ."</span>
                                                    </div>";
                                                }
                                            
                                            $html .= 
                                            "</div>
                                            <hr>";

                                            if (strlen($m["descricao"]) > 250) {
                                                $html .= "<p class='card-text justificado mb-3'>". substr($m["descricao"], 0, 250) ."...</p>";
                                            } else {
                                                $html .= "<p class='card-text justificado mb-3'>". $m["descricao"] ."</p>";
                                            }

                                        $html .= 
                                        "</div>
                                    </div>
                                </div>
                            </div>

                            <div class='card-footer text-end'>
                                <div class='text-start'>
                                    <p class='mb-0'><b>Matéria: </b>". $m["materia"] ."</p>
                                </div>
                                <a href='/monitorias/". $m["cod_anuncio"] ."'>
                                    <button type='button' class='button-input text-light'>Ver Mais</button>
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            }

            echo $html;
        }

        public function filtrarPorProduto() {
            $this->autenticarPagina();
            $produto = Container::getModel("Produto");

            $estado     = $_POST["estado"]   ?? "";
            $operacao   = $_POST["operacao"] ?? "";

            $produtos = $produto->getProdutoFiltrada($estado, $operacao);
            $html = "";

            if ( empty($produtos) ) {
                $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhum produto cadastrado corresponde a esse filtro.
                </div>";
            } else {
                foreach ($produtos as $p) {
                    $html .= 
                    "<div class='col-12 col-lg-6 mb-3'>
                        <div class='card h-100'>
                            <div class='card-header'>
                                <div class='row align-items-center'>
                                    <div class='col-2'>
                                        <i class='fa-regular fa-circle-user usuario'></i>
                                    </div>
                                    <div class='col-10'>
                                        <p class='my-0'>". $p["nome"] ."
                                            <br>
                                            <small>". $p["data_anunciada"] ."</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class='card-body pb-0'>
                                <div class='row g-4 text-center h-100'>
                                    <div class='col-md-4 align-self-center'>
                                        <img src='https://raw.githubusercontent.com/Edssaac/cf_storage/main/produtos/". $p["foto_name"] ."' class='img-fluid rounded m-lg-2' alt='produto'>
                                    </div>
                                    <div class='col-md-8'>
                                        <div>
                                            <h5 class='card-title'>". $p["titulo"] ."</h5>

                                            <div class='row my-3 text-center'>";

                                                if ($p["desconto"] && (strtotime($p["data_desconto"]) > time())) {
                                                    $html .=
                                                    "<div class='col-6' title='preço'>
                                                        <i class='fa-solid fa-dollar-sign'></i>
                                                        <small>R$</small>
                                                        <span class='text-danger'>
                                                            <s>". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $p["valor"])) ."</s>
                                                        </span>
                                                    </div>
                                                    <div class='col-6' title='promoção'>
                                                        <i class='fa-solid fa-tag'></i>
                                                        <small>R$</small>
                                                        <span class='text-success'>
                                                            ". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $p["valor"] - $p["desconto"])) ."
                                                        </span>
                                                    </div>";
                                                } else {
                                                    $html .= 
                                                    "<div class='col-6' title='preço'>
                                                        <i class='fa-solid fa-dollar-sign'></i>
                                                        <small>R$</small>
                                                        <span class='text-success'>". preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $p["valor"])) ."</span>
                                                    </div>";
                                                }
                                            
                                            $html .= 
                                            "</div>
                                            <hr>";

                                            if (strlen($p["descricao"]) > 250) {
                                                $html .= "<p class='card-text justificado mb-3'>". substr($p["descricao"], 0, 250) ."...</p>";
                                            } else {
                                                $html .= "<p class='card-text justificado mb-3'>". $p["descricao"] ."</p>";
                                            }

                                        $html .= 
                                        "</div>
                                    </div>
                                </div>
                            </div>

                            <div class='card-footer text-end'>
                                <a href='/produtos/". $p["cod_anuncio"] ."'>
                                    <button type='button' class='button-input text-light'>Ver Mais</button>
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            }

            echo $html;
        }

        public function filtrarPorSolicitacao() {
            $this->autenticarPagina();
            $solicitacao = Container::getModel("Solicitacao");

            $tipo = $_POST["tipo"] ?? "";
            $categorias = [
                "P" => "Produto",
                "M" => "Monitoria",
            ];

            $solicitacoes = $solicitacao->getSolicitacaoFiltrada($tipo);
            $html = "";

            if ( empty($solicitacoes) ) {
                $html =
                "<div class='alert alert-primary' role='alert'>
                    Nenhuma solicitação cadastrada corresponde a esse filtro.
                </div>";
            } else {
                foreach ($solicitacoes as $s) {
                    $html .= 
                    "<div class='col-12 col-lg-6 mb-3'>
                        <div class='card h-100'>
                            <div class='card-header'>
                                <div class='row align-items-center'>
                                    <div class='col-2'>
                                        <i class='fa-regular fa-circle-user usuario'></i>
                                    </div>
                                    <div class='col-10'>
                                        <p class='my-0'>". $s["nome"] ."
                                            <br>
                                            <small>". $s["data"] ."</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class='card-body pb-0'>
                                <div class='row g-4 text-center h-100'>
                                    <div class='col-md-12'>
                                        <div>
                                            <h5 class='card-title'>". $s["titulo"] ."</h5>";
                                            
                                            $html .= "<hr>";

                                            if (strlen($s["descricao"]) > 250) {
                                                $html .= "<p class='card-text justificado mb-3'>". substr($s["descricao"], 0, 250) ."...</p>";
                                            } else {
                                                $html .= "<p class='card-text justificado mb-3'>". $s["descricao"] ."</p>";
                                            }

                                        $html .= 
                                        "</div>
                                    </div>
                                </div>
                            </div>

                            <div class='card-footer text-end'>
                                <div class='text-start'>
                                    <p class='mb-0'><b>Categoria: </b>". $categorias[$s["tipo"]] ."</p>
                                </div>
                                <a href='/produtos/". $s["cod_solicitacao"] ."'>
                                    <button type='button' class='button-input text-light'>Ver Mais</button>
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            }

            echo $html;
        }

    }

?>