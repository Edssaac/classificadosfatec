<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Avaliacao extends Model
{
    protected $cod_avaliacao;
    protected $cod_anuncio;
    protected $cod_usuario;
    protected $comentario;
    protected $avaliacao;
    protected $data;

    // Obter todas as Avaliações de um anúncio em específico:
    public function getAvaliacoes()
    {
        $fields = "av.cod_usuario, u.nome, av.comentario, av.avaliacao, DATE_FORMAT(av.data, '%d/%m/%Y | %Hh%i') as data";
        $join   = "av INNER JOIN tb_usuarios u ON av.cod_usuario = u.cod_usuario";
        $where  = "av.cod_anuncio = $this->cod_anuncio";
        $order  = "av.cod_avaliacao DESC";

        $avaliacoes = $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

        return $avaliacoes;
    }

    // Cadastrar Avaliação:
    public function avaliar()
    {
        $this->db->insert([
            "cod_anuncio"   => $this->cod_anuncio,
            "cod_usuario"   => $this->cod_usuario,
            "comentario"    => $this->comentario,
            "avaliacao"     => $this->avaliacao,
            "data"          => date("Y-m-d H:i:s"),
        ]);

        return true;
    }
}

?>