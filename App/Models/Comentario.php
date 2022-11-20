<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Comentario extends Model
{
    protected $cod_comentario;
    protected $cod_solicitacao;
    protected $cod_usuario;
    protected $comentario;
    protected $data;

    // Obter todas os comentários de uma solicitação em específico:
    public function getComentarios()
    {
        $fields = "c.cod_usuario, u.nome, c.comentario, DATE_FORMAT(c.data, '%d/%m/%Y | %Hh%i') as data";
        $join   = "c INNER JOIN tb_usuarios u ON c.cod_usuario = u.cod_usuario";
        $where  = "c.cod_solicitacao = $this->cod_solicitacao";
        $order  = "c.cod_comentario DESC";

        $avaliacoes = $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

        return $avaliacoes;
    }

    // Cadastrar comentário:
    public function comentar()
    {
        $this->db->insert([
            "cod_solicitacao"   => $this->cod_solicitacao,
            "cod_usuario"       => $this->cod_usuario,
            "comentario"        => $this->comentario,
            "data"              => date("Y-m-d H:i:s"),
        ]);

        return true;
    }
}

?>