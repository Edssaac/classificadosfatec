<?php

namespace App\Controller;

use App\Controller;
use App\Model\DuvidaModel;
use App\Model\AvaliacaoModel;
use App\Model\ComentarioModel;

class ChatController extends Controller
{
    public function getDuvidas()
    {
        $this->autenticarPagina();

        $duvida = new DuvidaModel();

        $duvidas = $duvida->getDuvidas($_POST['anuncio']);

        $usuario = [
            'cod_usuario' => $_SESSION['cod_usuario'],
            'nome' => $_SESSION['nome']
        ];

        $html = '';

        foreach ($duvidas as $duvida) {
            $pergunta = "
                <!-- Pergunta -->
                <div class='row g-0'>
                    <div class='col-2 col-md-1'>
                        <i class='fa-regular fa-circle-user usuario'></i>
                    </div>
                    <div class='col-10 col-md-11'>
                        <div class='row'>
                            <div class='col-12 text-start order-1'>
                                " . $duvida['nome'] . "
                            </div>
                            <div class='col-12 text-end order-3'>
                                <small class='text-muted'>" . $duvida['data_pergunta'] . "</small>
                            </div>
                            <div class='col-12 mt-1 order-2'>
                                <textarea class='form-control input-cinza justificado' disabled>" . $duvida['pergunta'] . "</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            ";

            $resposta = '';

            if (!empty($duvida["resposta"])) {
                $resposta = "
                    <!-- Resposta -->
                    <div class='row g-0 resposta'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $usuario['nome'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $duvida['data_resposta'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza resposta justificado' disabled>" . $duvida['resposta'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            } else if ($usuario['cod_usuario'] === $duvida['anunciador']) {
                $resposta = "
                    <!-- Resposta -->
                    <div class='row g-0 resposta'>
                        <div class='col-1'>
                        </div>
                        <div class='col-2 col-lg-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-9 col-lg-10'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $usuario['nome'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                <div class='row align-items-center'>
                                    <div class='col-12 col-lg-7'></div>
                                    <div class='col-6 col-lg-2 text-end' id='status-" . $duvida['cod_duvida'] . "'></div>
                                    <div class='col-6 col-lg-3 text-end'>
                                        <button type='button' class='button-input text-light mt-2 responder' data-duvida='" . $duvida['cod_duvida'] . "'>Responder</button>
                                    </div>
                                </div>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza justificado resposta-" . $duvida['cod_duvida'] . "'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }

            $html .= "
                <div class='mb-3'>
                    $pergunta
                    $resposta
                </div>
            ";
        }

        echo $html;
    }

    public function comentarDuvida()
    {
        $this->autenticarPagina();

        $duvida = new DuvidaModel();

        $data = [
            'cod_anuncio' => $_POST['anuncio'],
            'cod_usuario' => $_POST['cod_usuario'],
            'pergunta' => $_POST['texto']
        ];

        if ($duvida->perguntar($data)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível comentar!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function responderDuvida()
    {
        $this->autenticarPagina();

        $duvida = new DuvidaModel();

        if ($duvida->responder($_POST['duvida'], $_POST['resposta'])) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível responder!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function getAvaliacoes()
    {
        $this->autenticarPagina();

        $avaliacao = new AvaliacaoModel();

        $avaliacoes = $avaliacao->getAvaliacoes($_POST['anuncio']);

        $html = '';

        foreach ($avaliacoes as $avaliacao) {
            $estrelas = '';

            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $avaliacao['avaliacao']) {
                    $estrelas .= "<i class='fa-solid fa-star'></i>";
                } else {
                    $estrelas .= "<i class='fa-regular fa-star'></i>";
                }
            }

            $html .= "
                <div class='mb-3'>
                    <!-- Avaliação -->
                    <div class='row g-0'>
                        <div class='col-2 col-md-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-10 col-md-11'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    <div class='row'>
                                        <div class='col-8'>
                                            <p class='mb-0'>" . $avaliacao['nome'] . "</p>
                                        </div>
                                        <div class='text-danger user-select-none col-4 text-end'>
                                            <small class='estrelas-avaliacao'>
                                                $estrelas
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $avaliacao['data'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea rows='2' class='form-control input-cinza' disabled>" . $avaliacao['comentario'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        echo $html;
    }

    public function avaliar()
    {
        $this->autenticarPagina();

        $avaliacao = new AvaliacaoModel();

        $data = [
            'cod_anuncio' => $_POST['anuncio'],
            'cod_usuario' => $_POST['cod_usuario'],
            'comentario' => $_POST['texto'],
            'avaliacao' => $_POST['avaliacao']
        ];

        if ($avaliacao->avaliar($data)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível avaliar!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }

    public function getComentarios()
    {
        $this->autenticarPagina();

        $comentario = new ComentarioModel();

        $comentarios = $comentario->getComentarios($_POST['solicitacao']);

        $html = '';

        foreach ($comentarios as $c) {
            $html .= "
                <div class='mb-3'>
                    <!-- Pergunta -->
                    <div class='row g-0'>
                        <div class='col-2 col-md-1'>
                            <i class='fa-regular fa-circle-user usuario'></i>
                        </div>
                        <div class='col-10 col-md-11'>
                            <div class='row'>
                                <div class='col-12 text-start order-1'>
                                    " . $c['nome'] . "
                                </div>
                                <div class='col-12 text-end order-3'>
                                    <small class='text-muted'>" . $c['data'] . "</small>
                                </div>
                                <div class='col-12 mt-1 order-2'>
                                    <textarea class='form-control input-cinza justificado' disabled>" . $c['comentario'] . "</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        echo $html;
    }

    public function comentar()
    {
        $this->autenticarPagina();

        $comentario = new ComentarioModel();

        $data = [
            'cod_solicitacao' => $_POST['solicitacao'],
            'cod_usuario' => $_POST['cod_usuario'],
            'comentario' => $_POST['texto']
        ];

        if ($comentario->comentar($data)) {
            $sucesso = true;
        } else {
            $sucesso = false;
            $this->erro = 'Não foi possível comentar!';
        }

        $this->output([
            'sucesso' => $sucesso,
            'mensagem' => $this->erro
        ]);
    }
}
