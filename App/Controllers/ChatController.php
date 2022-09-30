<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class ChatController extends Action {

        public function getDuvidas() {
            $this->autenticarPagina();

            $duvida = Container::getModel("Duvida");
            $duvida->__set("cod_anuncio", $_POST["anuncio"]);
            $duvidas = $duvida->getDuvidas();
            $html = "";
            $usuario = [
                "cod_usuario"   => $_SESSION["cod_usuario"],
                "nome"          => $_SESSION["nome"]
            ];

            foreach ($duvidas as $d) {
                $html .= "
                <div class='mb-3'>

                    <!-- Pergunta -->
                    <div class='row g-0 align-items-center'>
                        <div class='col-2 col-md-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-10 col-md-11'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $d["nome"] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $d["data_pergunta"] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza justificado' disabled>" . $d["pergunta"] . "?</textarea>
                                </div>
                            </div>
                        </div>
                    </div>";

                if ( !empty($d["resposta"]) ) {
                    $html .= "<!-- Resposta -->
                    <div class='row g-0 align-items-center resposta'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $usuario["nome"] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $d["data_resposta"] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza resposta justificado' disabled>" . $d["resposta"] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>";
                } else if ( $usuario["cod_usuario"] === $d["anunciador"] ) {
                    $html .= "<!-- Resposta -->
                    <div class='row g-0 align-items-center resposta'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $usuario["nome"] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                <div class='row align-items-center'>
                                    <div class='col-12 col-lg-7'></div>
                                    <div class='col-6 col-lg-2 text-end' id='status-" . $d["cod_duvida"] . "'></div>
                                    <div class='col-6 col-lg-3 text-end'>
                                        <button type='button' class='button-input text-light mt-2 responder' data-duvida='" . $d["cod_duvida"] . "'>Responder</button>
                                    </div>
                                </div>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza justificado resposta-" . $d["cod_duvida"] . "'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                $html .= "</div>";
            }

            echo $html;
        }

        public function comentarDuvida() {
            $this->autenticarPagina();

            $sucesso = true;
            $duvida = Container::getModel("Duvida");
            $duvida->__set("cod_anuncio",   $_POST["anuncio"]);
            $duvida->__set("cod_usuario",   $_SESSION["cod_usuario"]);
            $duvida->__set("pergunta",      $_POST["texto"]);

            if ( !$duvida->perguntar() ) {
                $sucesso = false;
                $this->erro = "Não foi possível comentar!";
            }

            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        }

        public function responderDuvida() {
            $this->autenticarPagina();

            $sucesso = true;
            $duvida = Container::getModel("Duvida");
            $duvida->__set("cod_duvida",    $_POST["duvida"]);
            $duvida->__set("resposta",      $_POST["resposta"]);

            if ( !$duvida->responder() ) {
                $sucesso = false;
                $this->erro = "Não foi possível responder!";
            }

            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        }

    }

?>