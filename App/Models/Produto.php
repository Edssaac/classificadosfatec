<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Produto extends Anuncio
{
    protected $fk_cod_anuncio;
    protected $foto_name;
    protected $foto_token;
    protected $estado;
    protected $operacao;
    protected $quantidade;

    // Cadastrar produto:
    public function anunciar()
    {
        $this->__set("status", 1);
        $this->__set("tipo", "P");

        $cod_anuncio = $this->inserirAnuncio();
        $this->__set("cod_anuncio", $cod_anuncio);

        $this->db->insert([
            "cod_anuncio"   => $this->cod_anuncio,
            "foto_name"     => $this->foto_name,
            "foto_token"    => $this->foto_token,
            "estado"        => $this->estado,
            "operacao"      => $this->operacao,
            "quantidade"    => $this->quantidade
        ]);

        return true;
    }

    // Atualizar produto:
    public function atualizar()
    {
        $this->atualizarAnuncio();

        $this->db->update("cod_anuncio = $this->cod_anuncio", [
            "foto_name"     => $this->foto_name,
            "foto_token"    => $this->foto_token,
            "estado"        => $this->estado,
            "operacao"      => $this->operacao,
            "quantidade"    => $this->quantidade
        ]);

        return true;
    }

    // Excluir produto:
    public function excluir()
    {
        $this->db->delete("cod_anuncio = $this->cod_anuncio");
        $this->deletarAnuncio();

        return true;
    }

    // Obter todos os produtos:
    public function getProdutos()
    {
        $fields = "u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, p.foto_name";
        $join   = "p INNER JOIN tb_anuncios a ON p.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";
        $order  = "data_anunciada DESC";

        return $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obter um produto em especifico:
    public function getProduto($cod_anuncio)
    {
        $fields = "u.nome, u.telefone, a.cod_anuncio, a.cod_usuario, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, p.foto_name, p.estado, p.operacao, p.quantidade";
        $join   = "p INNER JOIN tb_anuncios a ON p.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 and a.cod_anuncio = $cod_anuncio AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";

        return $this->db->select($fields, $where, null, null, $join)->fetch(PDO::FETCH_ASSOC);
    }

    public function getProdutoFiltrada($estado = null, $operacao = null, $titulo = null)
    {
        $fields = "u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, p.foto_name";
        $join   = "p INNER JOIN tb_anuncios a ON p.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";
        $order  = "data_anunciada DESC";

        if (!empty($estado) && is_array($estado)) {
            $where .= " AND p.estado IN ('" . implode("', '", $estado)  . "')";
        }

        if (!empty($operacao) && is_array($operacao)) {

            if (in_array("A", $operacao)) {
                $operacao[] = "V";
                $operacao[] = "T";

                $operacao = array_unique($operacao);
            }

            $where .= " AND p.operacao IN ('" . implode("', '", $operacao)  . "')";
        }

        if (!empty($titulo)) {
            $where .= " AND a.titulo LIKE '%$titulo%'";
        }

        return $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>