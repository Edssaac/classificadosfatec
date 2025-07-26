<?php

namespace App\Controller;

use App\Controller;
use Library\Mail;

class AboutController extends Controller
{
    public function contact()
    {
        $this->render('contact');
    }

    public function message()
    {
        $information = [
            'name'      => $_POST['name'] ?? 'nenhum nome anexado',
            'email'     => $_POST['email'] ?? 'nenhum email anexado',
            'phone'     => $_POST['phone'] ?? 'nenhum telefone anexado',
            'message'   => $_POST['message'] ?? 'nenhuma mensagem anexada'
        ];

        $address = 'classificadosfatec@gmail.com';
        $subject = 'Nova Mensagem - Fale Conosco';
        $body = Mail::createDefaultBody($information);

        return Mail::send($address, $subject, $body);
    }

    public function policies()
    {
        $this->render('policies');
    }

    public function team()
    {
        $this->render('team');
    }
}
