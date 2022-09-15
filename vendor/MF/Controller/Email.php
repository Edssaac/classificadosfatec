<?php

    /* CLASSE RESPONSÁVEL PELO ENVIO DE EMAILS */

    namespace MF\Controller;    

    // Para podermos usar o PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Email {
        // Método responsável por enviar o e-mail:
        public static function sendEmail( $addresses, $subject, $body, $altBody="" ) {
            // Intânciando a class PHPMailer:
            $mail = new PHPMailer(true);

            if ( empty($altBody) ) {
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
                $mail->setFrom( getenv("EMAIL_FROM_EMAIL"), getenv("EMAIL_FROM_NAME") );

                // Destinatários:
                $addresses = is_array($addresses) ? $addresses : [$addresses];
                foreach ( $addresses as $address ) {
                    $mail->addAddress($address);
                }

                // Conteúdo do e-mail:
                $mail->isHTML(true);
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

        public static function criarCorpo($informacao) {
            $mensagem = 
            "<div>
                <p>Nome: {$informacao['nome']}</p>
                <p>Email: {$informacao['email']}</p>
                <p>Mensagem: {$informacao['mensagem']} </p>
            </div>
            ";
            
            return $mensagem;
        }

    }
?>