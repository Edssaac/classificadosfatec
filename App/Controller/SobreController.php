<?php

namespace App\Controller;

use App\Controller;
use Library\Mail;

class SobreController extends Controller
{
    public function faleConosco()
    {
        $this->render("faleconosco");
    }

    public function mensagem()
    {
        $informacao = [
            "nome"      => $_POST['nome'] ?? "nenhum nome anexado",
            "email"     => $_POST['email'] ?? "nenhum email anexado",
            "telefone"  => $_POST['telefone'] ?? "nenhum telefone anexado",
            "mensagem"  => $_POST['mensagem'] ?? "nenhuma mensagem anexada",
        ];

        $destinatario = "classificadosfatec@gmail.com";
        $assunto = "Nova Mensagem - Fale Conosco";
        $corpo = Mail::criarCorpoPadrao($informacao);

        $sucesso = (Mail::send($destinatario, $assunto, $corpo) === true);

        return $sucesso;
    }

    public function politicas()
    {
        $this->render("politicas");
    }

    public function equipe()
    {
        $this->render("equipe");
    }
}
