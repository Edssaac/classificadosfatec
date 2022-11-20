<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Solicitacao extends Model
{
    protected $cod_solicitacao;
    protected $cod_usuario;
    protected $titulo;
    protected $descricao;
    protected $data;
    protected $tipo;
    protected $data_vencimento;

    public function solicitar()
    {
        $this->db->insert([
            "cod_usuario"       => $this->cod_usuario,
            "titulo"            => $this->titulo,
            "descricao"         => $this->descricao,
            "data"              => date("Y-m-d H:i:s"),
            "tipo"              => $this->tipo,
            "data_vencimento"   => $this->data_vencimento,
        ]);

        return true;
    }

    public function atualizar()
    {
        $this->db->update("cod_solicitacao = $this->cod_solicitacao", [
            "titulo"            => $this->titulo,
            "descricao"         => $this->descricao,
            // "data"              => date("Y-m-d H:i:s"),
            "tipo"              => $this->tipo,
            "data_vencimento"   => $this->data_vencimento,
        ]);

        return true;
    }

    public function excluir()
    {
        $this->db->delete("cod_solicitacao = $this->cod_solicitacao");

        return true;
    }

    public function getSolicitacoes()
    {
        $fields = "s.cod_solicitacao, s.cod_usuario, u.nome, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.data_vencimento, s.tipo";
        $join   = "s INNER JOIN tb_usuarios u ON s.cod_usuario = u.cod_usuario";
        $order  = "s.cod_solicitacao DESC";
        $where  = "s.data_vencimento > '" . date("Y-m-d") . "' OR s.data_vencimento = '0000-00-00'";

        $solicitacoes = $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

        return $solicitacoes;
    }

    public function getSolicitacao($cod_solicitacao)
    {
        $fields = "s.cod_solicitacao, s.cod_usuario, u.nome, u.telefone, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.data_vencimento, s.tipo";
        $join   = "s INNER JOIN tb_usuarios u ON s.cod_usuario = u.cod_usuario";
        $where  = "s.cod_solicitacao = $cod_solicitacao AND (s.data_vencimento > '" . date("Y-m-d") . "' OR s.data_vencimento = '0000-00-00')";

        $solicitacao = $this->db->select($fields, $where, null, null, $join)->fetch(PDO::FETCH_ASSOC);

        return $solicitacao;
    }

    public function getSolicitacoesPorUsuario()
    {
        $fields = "cod_solicitacao, titulo, tipo";
        $where  = "cod_usuario = $this->cod_usuario AND (data_vencimento > '" . date("Y-m-d") . "' OR data_vencimento = '0000-00-00')";
        $order  = "data desc";

        $solicitacoes = $this->db->select($fields, $where, $order)->fetchAll(PDO::FETCH_ASSOC);

        return $solicitacoes;
    }

    public function getSolicitacaoFiltrada($tipo = null, $titulo = null)
    {
        $fields = "s.cod_solicitacao, s.cod_usuario, u.nome, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.data_vencimento, s.tipo";
        $join   = "s INNER JOIN tb_usuarios u ON s.cod_usuario = u.cod_usuario";
        $order  = "s.cod_solicitacao DESC";
        $where  = "(s.data_vencimento > '" . date("Y-m-d") . "' OR s.data_vencimento = '0000-00-00')";

        if (!empty($tipo) && is_array($tipo)) {
            $where .= "AND s.tipo IN ('" . implode("', '", $tipo)  . "')";

            if (!empty($titulo)) {
                $where .= " AND s.titulo LIKE '%$titulo%'";
            }
        } else if (!empty($titulo)) {
            $where .= "AND s.titulo LIKE '%$titulo%'";
        }

        $solicitacoes = $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

        return $solicitacoes;
    }
}

?>