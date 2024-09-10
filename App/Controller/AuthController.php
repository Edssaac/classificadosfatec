<?php

namespace App\Controller;

use App\Controller;
use App\Model\UsuarioModel;
use Library\Mail;

class AuthController extends Controller
{
    public function registrar()
    {
        $sucesso = false;

        if ($this->validarRegistro($_POST)) {
            $usuario = new UsuarioModel();

            if ($usuario->getUsuarioPorEmail($_POST['email'])) {
                $this->erro = "Este e-mail já foi cadastrado!";
            } else {
                $_POST['token'] = sha1(uniqid(mt_rand(), true));

                if ($usuario->registrar($_POST)) {
                    $informacao = [
                        "nome"  => $_POST['nome'],
                        "token" => $_POST['token']
                    ];

                    $destinatario = $_POST['email'];
                    $assunto = "Confirme seu e-mail";
                    $corpo = Mail::confirmacaoEmail($informacao);

                    if (Mail::send($destinatario, $assunto, $corpo)) {
                        $sucesso = true;
                        $this->erro = "Não foi possivel enviar o email!";
                    }
                } else {
                    $this->erro = "Não foi possivel realizar o cadastro!";
                }
            }
        }

        $this->output([
            "sucesso" => $sucesso,
            "mensagem" => $this->erro
        ]);
    }

    public function validarRegistro(array $registro): bool
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

        // Mail:
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

        $usuario = new UsuarioModel();

        /*if ( isset($_POST['bloquear']) && $_POST['bloquear'] ) {
            $usuario->bloquear();
        } else */
        if ($usuario->autenticar($_POST['email'], $_POST['senha'])) {
            if (!empty($_POST['hash']) && !$usuario->validarHash($_POST['email'], $_POST['hash'])) {
                $this->erro = "Código de segurança inválido!<br>Verifique novamente o link enviado pelo e-mail ou entre em contato com o suporte se persistir.";
            } else if ($usuario->verificarInatividade($_POST['email'])) {
                $this->erro = "Usuário não ativo, verifique o seu e-mail ou entre em contato com o suporte se persistir!";
            }

            if (empty($this->erro)) {
                session_start();

                $usuario_data = $usuario->getUsuarioPorEmail($_POST['email']);

                $_SESSION['cod_usuario'] = $usuario_data['cod_usuario'];
                $_SESSION['nome'] = $usuario_data['nome'];
                $_SESSION['admin'] = !empty($usuario_data['admin']);

                $usuario->atualizarAcesso($usuario_data['cod_usuario']);

                $sucesso = true;
            }
        } else {
            $this->erro = "E-mail ou senha incorretos!";
        }

        $this->output([
            "sucesso" => $sucesso,
            "mensagem" => $this->erro
        ]);
    }

    public function redefinirSenha()
    {
        $this->autenticarPagina(true);

        $sucesso = true;

        $usuario = new UsuarioModel();

        if (isset($_POST['token'])) {
            if ($_POST["senha"] !== $_POST["senha_confirmacao"]) {
                $sucesso = false;
                $this->erro = "As senhas não conferem!";
            }

            $usuario_data = $usuario->getUsuarioPorToken($_POST['token']);

            if ($usuario_data) {
                $usuario->salvarToken($usuario_data['cod_usuario'], true);
                $usuario->alterarSenha($usuario_data['cod_usuario'], $_POST['senha']);
            } else {
                $sucesso = false;
                $this->erro = "Token inválido!";
            }

            $this->output([
                "sucesso" => $sucesso,
                "mensagem" => $this->erro
            ]);
        } else {
            $usuario_data = $usuario->getUsuarioPorRecuperacao($_POST['email'], $_POST['data_nascimento']);

            if ($usuario_data) {
                $token = $usuario->salvarToken($usuario_data['cod_usuario']);

                $informacao = [
                    "nome"   => $usuario_data['nome'],
                    "token"  => $token
                ];

                $destinatario = $usuario_data['email'];
                $assunto = "Redefinição de Senha";
                $corpo = Mail::criarCorpoRedefinicao($informacao);

                $sucesso = (Mail::send($destinatario, $assunto, $corpo) === true);

                if (!$sucesso) {
                    $this->erro = "Não foi possível enviar o e-mail.";
                }
            } else {
                $sucesso = false;
                $this->erro = "E-mail ou Data de Nascimento incorretos.";
            }

            $this->output([
                "sucesso" => $sucesso,
                "mensagem" => $this->erro
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
