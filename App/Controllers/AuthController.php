<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Controller\Email;
use MF\Model\Container;

class AuthController extends Action
{
    public function registrar()
    {
        $sucesso = false;

        if ($this->validarRegistro($_POST)) {
            $usuario = Container::getModel("Usuario");
            $usuario->__set("nome",             $_POST['nome']);
            $usuario->__set("data_nascimento",  $_POST['data_nascimento']);
            $usuario->__set("telefone",         $_POST['telefone']);
            $usuario->__set("instituicao",      $_POST['instituicao']);
            $usuario->__set("email",            $_POST['email']);
            $usuario->__set("senha",            $_POST['senha']);

            if (count($usuario->getUsuarioPorEmail())) {
                $this->erro = "Este e-mail já foi cadastrado!";
            } else {
                $token = sha1(uniqid(mt_rand(), true));

                if ($usuario->registrar($token)) {
                    $informacao = [
                        "nome" => $usuario->__get("nome"),
                        "token" => $token
                    ];

                    $destinatario   = $usuario->__get("email");
                    $assunto        = "Confirme seu e-mail";
                    $corpo          = Email::confirmacaoEmail($informacao);

                    Email::sendEmail($destinatario, $assunto, $corpo);

                    $sucesso = true;
                } else {
                    $this->erro = "Não foi possivel realizar o cadastro!";
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function validarRegistro($registro)
    {
        $obrigatorios = [
            "nome",
            "data_nascimento",
            "telefone",
            "instituicao",
            "email",
            "senha",
            "senha_confirmacao",
        ];

        // Verificando se todos os campos foram preenchidos:
        foreach ($obrigatorios as $obrigatorio) {
            if (!isset($registro[$obrigatorio])) {
                $this->erro = "Verifique se todos os campos foram preenchidos!";
                return false;
            }
        }

        // Email:
        if (!strpos($registro["email"], "@fatec.sp.gov.br")) {
            $this->erro = "E-mail institucional inválido! Deve pertencer ao dominío @fatec.sp.gov.br";
            return false;
        }

        // Senha:
        if ($registro["senha"] !== $registro["senha_confirmacao"]) {
            $this->erro = "As senhas não conferem!";
            return false;
        }

        return true;
    }

    public function autenticar()
    {
        $sucesso = false;
        $usuario = Container::getModel("Usuario");
        $usuario->__set("email", $_POST['email']);
        $usuario->__set("senha", $_POST['senha']);

        /*if ( isset($_POST['bloquear']) && $_POST['bloquear'] ) {
                $usuario->bloquear();
            } else */
        if ($usuario->autenticar()) {
            if (isset($_POST['hash'])) {
                if (!$usuario->validarHash(filter_var($_POST['hash']))) {
                    $this->erro = "Código de segurança inválido!<br>Verifique novamente o link enviado pelo e-mail ou entre em contato com o suporte se persistir.";
                }
            } else if ($usuario->verificarInatividade()) {
                $this->erro = "Usuário não ativo, verifique o seu e-mail ou entre em contato com o suporte se persistir!";
            }

            if (empty($this->erro)) {
                session_start();

                $_SESSION['cod_usuario'] = $usuario->__get("cod_usuario");
                $_SESSION['nome'] = $usuario->__get("nome");

                if ($usuario->administrador()) {
                    $_SESSION['admin'] = true;
                }

                $usuario->atualizarAcesso();
                $sucesso = true;
            }
        } else {
            $this->erro = "E-mail ou senha incorretos!";
        }

        header('Content-Type: application/json');
        echo json_encode([
            "sucesso"   => $sucesso,
            "mensagem"  => $this->erro
        ]);
    }

    public function redefinirSenha()
    {
        $this->autenticarPagina(true);
        $usuario = Container::getModel("Usuario");
        $sucesso = true;

        if (isset($_POST['token'])) {
            $usuario->__set("token", $_POST['token']);

            if ($_POST["senha"] !== $_POST["senha_confirmacao"]) {
                $sucesso = false;
                $this->erro = "As senhas não conferem!";
            } else if ($usuario->getUsuarioPorToken()) {
                $usuario->__set("senha", $_POST['senha']);
                $usuario->salvarToken(true);
                $usuario->alterarSenha();
            } else {
                $sucesso = false;
                $this->erro = "Token inválido!";
            }

            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        } else {
            $usuario->__set("email",            $_POST['email']);
            $usuario->__set("data_nascimento",  $_POST['data_nascimento']);

            if ($usuario->getUsuarioPorRecuperacao()) {
                $usuario->salvarToken();

                $informacao = [
                    "nome" => $usuario->__get("nome"),
                    "token" => $usuario->__get("token")
                ];

                $destinatario   = $usuario->__get("email");
                $assunto        = "Redefinição de Senha";
                $corpo          = Email::criarCorpoRedefinicao($informacao);

                $sucesso = (Email::sendEmail($destinatario, $assunto, $corpo) === true);

                if (!$sucesso) {
                    $this->erro = "Não foi possível enviar o e-mail.";
                }
            } else {
                $sucesso = false;
                $this->erro = "E-mail ou Data de Nascimento incorretos.";
            }

            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        }
    }

    public function sair()
    {
        session_start();
        session_destroy();
        header("Location: /");
    }
}

?>