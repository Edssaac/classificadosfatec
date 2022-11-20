<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Duvida extends Model
{
    protected $cod_duvida;
    protected $cod_anuncio;
    protected $cod_usuario;
    protected $pergunta;
    protected $data_pergunta;
    protected $resposta;
    protected $data_resposta;

    // Obter todas as Perguntas e Respostas de um anúncio em específico:
    public function getDuvidas()
    {
        $fields = "d.cod_duvida, d.cod_anuncio, a.cod_usuario AS anunciador, d.cod_usuario, u.nome, d.pergunta, DATE_FORMAT(d.data_pergunta, '%d/%m/%Y | %Hh%i') as data_pergunta, d.resposta, DATE_FORMAT(d.data_resposta, '%d/%m/%Y | %Hh%i') as data_resposta";
        $join   = "d INNER JOIN tb_usuarios u ON d.cod_usuario = u.cod_usuario INNER JOIN tb_anuncios a ON d.cod_anuncio = a.cod_anuncio";
        $where  = "d.cod_anuncio = $this->cod_anuncio";
        $order  = "d.cod_duvida DESC";

        $duvidas = $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

        return $duvidas;
    }

    // Cadastrar Pergunta:
    public function perguntar()
    {
        $this->db->insert([
            "cod_anuncio"   => $this->cod_anuncio,
            "cod_usuario"   => $this->cod_usuario,
            "pergunta"      => $this->pergunta,
            "data_pergunta" => date("Y-m-d H:i:s"),
        ]);

        return true;
    }

    // Cadastrar Resposta para a Pergunta:
    public function responder()
    {
        $this->db->update("cod_duvida = $this->cod_duvida", [
            "resposta"      => $this->resposta,
            "data_resposta" => date("Y-m-d H:i:s")
        ]);
    }
}

?>