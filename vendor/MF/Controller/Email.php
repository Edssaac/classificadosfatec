<?php

/* CLASSE RESPONSÁVEL PELO ENVIO DE EMAILS */

namespace MF\Controller;

// Para podermos usar o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    // Método responsável por enviar o e-mail:
    public static function sendEmail($addresses, $subject, $body, $altBody = "")
    {
        // Intânciando a class PHPMailer:
        $mail = new PHPMailer(true);

        if (empty($altBody)) {
            $altBody = "Erro ao carregar HTML!";
        }

        try {
            // Credencias de acesso ao SMTP:
            $mail->isSMTP();
            $mail->Host = getenv("EMAIL_HOST");
            $mail->SMTPAuth = true;
            $mail->Username = getenv("EMAIL_USER");
            $mail->Password = getenv("EMAIL_PASS");
            $mail->SMTPSecure = getenv("EMAIL_SECURE");
            $mail->Port = getenv("EMAIL_PORT");
            $mail->Charset = getenv("EMAIL_CHARSET");

            // Remetente:
            $mail->setFrom(getenv("EMAIL_FROM_EMAIL"), getenv("EMAIL_FROM_NAME"));

            // Destinatários:
            $addresses = is_array($addresses) ? $addresses : [$addresses];
            foreach ($addresses as $address) {
                $mail->addAddress($address);
            }

            // Conteúdo do e-mail:
            $mail->isHTML(true);
            $mail->CharSet  = "UTF-8";
            $mail->Encoding = 'base64';
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $altBody;
            $mail->addReplyTo(getenv("EMAIL_USER"), 'Suporte');

            // Enviando o e-mail:
            return $mail->send();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function criarCorpoPadrao($informacao)
    {
        $mensagem =
            "<div>
                <p>Nome: {$informacao['nome']}</p>
                <p>Email: {$informacao['email']}</p>
                <p>Telefone: {$informacao['telefone']}</p>
                <p>Mensagem: {$informacao['mensagem']} </p>
            </div>
            ";

        return $mensagem;
    }

    public static function criarCorpoRedefinicao($informacao)
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $base = "http://localhost:8088";
        } else {
            $base = "https://classificadosfatec.herokuapp.com";
        }

        $mensagem =
            "<div>
                <p>
                Olá {$informacao['nome']}, alguém solicitou uma nova senha para a sua conta 
                <b>Classificados Fatec</b> associada a este e-mail.
                <br><br>
                Nenhuma alteração foi feita em sua conta ainda.
                <br>
                Você pode redefinir sua senha clicando no link abaixo:
                <br><br>
                <a href='{$base}/nova_senha?token={$informacao['token']}' target='_blank'>Redefinir Senha</a>
                <br><br>
                Se você não solicitou uma nova senha, por favor ignore este e-mail.
                <br><br>
                <b>Classificados Fatec</b>
                </p>
            </div>
            ";

        return $mensagem;
    }

    public static function confirmacaoEmail($informacao)
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $base = "http://localhost:8088";
        } else {
            $base = "https://classificadosfatec.herokuapp.com";
        }

        $mensagem =
            "<div>
                <p>
                Olá {$informacao['nome']}, confirme o e-mail para sua conta 
                <b>Classificados Fatec</b> no link abaixo.
                <br><br>
                <a href='{$base}/entrar?hash={$informacao['token']}' target='_blank'>Confirmar cadastro</a>
                <br><br>
                <b>Classificados Fatec</b>
                </p>
            </div>
            ";

        return $mensagem;
    }
}

?>