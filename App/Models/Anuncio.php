<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Anuncio extends Model
{
    protected $cod_anuncio;
    protected $cod_usuario;
    protected $titulo;
    protected $descricao;
    protected $status;
    protected $data_anunciada;
    protected $valor;
    protected $desconto;
    protected $data_desconto;
    protected $tipo;
    protected $data_vencimento;

    // Cadastrar Anuncio:
    public function inserirAnuncio()
    {
        $table = $this->db->getTable();
        $this->db->setTable("tb_anuncios");

        $cod_anuncio = $this->db->insert([
            "cod_usuario"       => $this->cod_usuario,
            "titulo"            => $this->titulo,
            "descricao"         => $this->descricao,
            "valor"             => $this->valor,
            "desconto"          => $this->desconto,
            "data_desconto"     => $this->data_desconto,
            "status"            => $this->status,
            "data_anunciada"    => date("Y-m-d H:i:s"),
            "tipo"              => $this->tipo,
            "data_vencimento"   => $this->data_vencimento,
        ]);

        $this->db->setTable($table);

        return $cod_anuncio;
    }

    // Atualizar Anuncio:
    public function atualizarAnuncio()
    {
        $table = $this->db->getTable();
        $this->db->setTable("tb_anuncios");

        $cod_anuncio = $this->db->update("cod_anuncio = $this->cod_anuncio", [
            "titulo"            => $this->titulo,
            "descricao"         => $this->descricao,
            "valor"             => $this->valor,
            "desconto"          => $this->desconto,
            "data_desconto"     => $this->data_desconto,
            //"status"            => $this->status,
            //"data_anunciada"    => date("Y-m-d H:i:s"),
            "data_vencimento"    => $this->data_vencimento,
        ]);

        $this->db->setTable($table);

        return $cod_anuncio;
    }

    // Excluir um anuncio:
    public function deletarAnuncio()
    {
        $table = $this->db->getTable();
        $this->db->setTable("tb_anuncios");

        $cod_anuncio = $this->db->delete("cod_anuncio = $this->cod_anuncio");

        $this->db->setTable($table);

        return $cod_anuncio;
    }

    // Obter um anuncio em especifico:
    public function getAnunciosPorUsuario()
    {
        $fields = "cod_anuncio, titulo, tipo";
        $where  = "cod_usuario = $this->cod_usuario AND (data_vencimento > '" . date("Y-m-d") . "' OR data_vencimento = '0000-00-00')";
        $order  = "data_anunciada desc";

        $anuncios = $this->db->select($fields, $where, $order)->fetchAll(PDO::FETCH_ASSOC);

        return $anuncios;
    }

    // Obter os últimos 5 anúncios:
    public function getUltimos($quantidade)
    {
        $table = $this->db->getTable();
        $this->db->setTable("tb_anuncios");

        $fields = "a.cod_anuncio, a.titulo, a.descricao, p.foto_name";
        $join   = "a inner join tb_produtos p on a.cod_anuncio = p.cod_anuncio";
        $order  = "a.data_anunciada desc";
        $where  = "a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00'";

        $ultimos = $this->db->select($fields, $where, $order, $quantidade, $join)->fetchAll(PDO::FETCH_ASSOC);

        $this->db->setTable($table);

        return $ultimos;
    }
}

?>