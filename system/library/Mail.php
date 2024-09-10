<?php

namespace Library;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    public static function send($addresses, $subject, $body, $alt_body = '', $attachments = [], $ccs = [], $bccs = []): bool
    {
        $mail = new PHPMailer(true);

        if (empty($alt_body)) {
            $alt_body = 'Erro ao carregar HTML!';
        }

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->Username = $_ENV['MAIL_ADDRESS'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->setFrom($_ENV['MAIL_ADDRESS'], $_ENV['MAIL_USERNAME']);
            $mail->addReplyTo($_ENV['MAIL_ADDRESS'], $_ENV['MAIL_USERNAME']);

            $addresses = is_array($addresses) ? $addresses : [$addresses];

            foreach ($addresses as $address) {
                $mail->addAddress($address);
            }

            $attachments = is_array($attachments) ? $attachments : [$attachments];

            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment);
            }

            $ccs = is_array($ccs) ? $ccs : [$ccs];

            foreach ($ccs as $cc) {
                $mail->addCC($cc);
            }

            $bccs = is_array($bccs) ? $bccs : [$bccs];

            foreach ($bccs as $bcc) {
                $mail->addBCC($bcc);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $alt_body;

            return $mail->send();
        } catch (Exception $e) {
            Log::write(sprintf(
                'Exceção ao enviar email: %s',
                $e->getMessage()
            ));

            return false;
        }
    }

    public static function criarCorpoPadrao(array $informacao): string
    {
        $mensagem = "
            <div>
                <p>Nome: {$informacao['nome']}</p>
                <p>Email: {$informacao['email']}</p>
                <p>Telefone: {$informacao['telefone']}</p>
                <p>Mensagem: {$informacao['mensagem']} </p>
            </div>
        ";

        return $mensagem;
    }

    public static function criarCorpoRedefinicao(array $informacao): string
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $base = "http://localhost:8080";
        } else {
            $base = "https://classificadosfatec.herokuapp.com";
        }

        $mensagem = "
            <div>
                <p>
                    Olá {$informacao['nome']}, alguém solicitou uma nova senha para a sua conta 
                    <b>Classificados Fatec</b> associada a este e-mail.
                    <br>
                    <br>
                    Nenhuma alteração foi feita em sua conta ainda.
                    <br>
                    Você pode redefinir sua senha clicando no link abaixo:
                    <br>
                    <br>
                    <a href='{$base}/nova_senha?token={$informacao['token']}' target='_blank'>Redefinir Senha</a>
                    <br>
                    <br>
                    Se você não solicitou uma nova senha, por favor ignore este e-mail.
                    <br>
                    <br>
                    <b>Classificados Fatec</b>
                </p>
            </div>
        ";

        return $mensagem;
    }

    public static function confirmacaoEmail(array $informacao): string
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $base = "http://localhost:8080";
        } else {
            $base = "https://classificadosfatec.herokuapp.com";
        }

        $mensagem = "
            <div>
                <p>
                    Olá {$informacao['nome']}, confirme o e-mail para sua conta 
                    <b>Classificados Fatec</b> no link abaixo.
                    <br>
                    <br>
                    <a href='{$base}/entrar?hash={$informacao['token']}' target='_blank'>Confirmar cadastro</a>
                    <br>
                    <br>
                    <b>Classificados Fatec</b>
                </p>
            </div>
        ";

        return $mensagem;
    }
}
