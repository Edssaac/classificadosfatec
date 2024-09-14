<?php

namespace App\Controller;

use App\Controller;
use App\Model\UserModel;
use Library\Mail;

class AuthController extends Controller
{
    public function register()
    {
        $success = false;

        if ($this->validateRegister($_POST)) {
            $user = new UserModel();

            if ($user->getUserByEmail($_POST['email'])) {
                $this->error = 'Este e-mail já foi cadastrado!';
            } else {
                $_POST['token'] = sha1(uniqid(mt_rand(), true));

                if ($user->insertUser($_POST)) {
                    $information = [
                        'name'  => $_POST['name'],
                        'token' => $_POST['token']
                    ];

                    $address = $_POST['email'];
                    $subject = 'Confirme seu e-mail';
                    $body = Mail::createConfirmationBody($information);

                    if (Mail::send($address, $subject, $body)) {
                        $success = true;
                    } else {
                        $success = false;
                        $this->error = 'Não foi possivel enviar o email!';
                    }
                } else {
                    $this->error = 'Não foi possivel realizar o cadastro!';
                }
            }
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    private function validateRegister(array $register): bool
    {
        $required_fields = [
            'name',
            'birth_date',
            'phone',
            'institution',
            'email',
            'password',
            'confirm_password'
        ];

        foreach ($required_fields as $field) {
            if (!isset($register[$field])) {
                $this->error = 'Verifique se todos os campos foram preenchidos!';
                return false;
            }
        }

        if (!strpos($register['email'], '@fatec.sp.gov.br')) {
            $this->error = 'E-mail institucional inválido! Deve pertencer ao dominío @fatec.sp.gov.br';
            return false;
        }

        if ($register['password'] !== $register['confirm_password']) {
            $this->error = 'As senhas não conferem!';
            return false;
        }

        return true;
    }

    public function authenticate()
    {
        $success = false;

        $user = new UserModel();

        /*if ( isset($_POST['block']) && $_POST['block'] ) {
            $user->block();
        } else */
        if ($user->authenticate($_POST['email'], $_POST['password'])) {
            if (!empty($_POST['hash']) && !$user->validateHash($_POST['email'], $_POST['hash'])) {
                $this->error = 'Código de segurança inválido!<br>Verifique novamente o link enviado pelo e-mail ou entre em contato com o suporte se persistir.';
            } else if ($user->checkInactivity($_POST['email'])) {
                $this->error = 'Usuário não ativo, verifique o seu e-mail ou entre em contato com o suporte se persistir!';
            }

            if (empty($this->error)) {
                session_start();

                $user_data = $user->getUserByEmail($_POST['email']);

                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['name'] = $user_data['name'];
                $_SESSION['admin'] = !empty($user_data['admin']);

                $user->updateAccess($user_data['user_id']);

                $success = true;
            }
        } else {
            $this->error = 'E-mail ou senha incorretos!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function recoverPassword()
    {
        $this->authenticatePage(true);

        $success = true;

        $user = new UserModel();

        if (isset($_POST['token'])) {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $success = false;
                $this->error = 'As senhas não conferem!';
            }

            $user_data = $user->getUserByToken($_POST['token']);

            if ($user_data) {
                $user->updateToken($user_data['user_id'], true);
                $user->updatePassword($user_data['user_id'], $_POST['password']);
            } else {
                $success = false;
                $this->error = 'Token inválido!';
            }
        } else {
            $user_data = $user->getUserByRecover($_POST['email'], $_POST['birth_date']);

            if ($user_data) {
                $token = $user->updateToken($user_data['user_id']);

                $information = [
                    'name'   => $user_data['name'],
                    'token'  => $token
                ];

                $address = $user_data['email'];
                $subject = 'Redefinição de Senha';
                $body = Mail::createRecoverBody($information);

                if (Mail::send($address, $subject, $body)) {
                    $success = true;
                } else {
                    $this->error = 'Não foi possível enviar o e-mail.';
                }
            } else {
                $success = false;
                $this->error = 'E-mail ou Data de Nascimento incorretos.';
            }
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
