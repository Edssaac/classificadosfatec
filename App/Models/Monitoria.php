<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Monitoria extends Anuncio
{
    protected $fk_cod_anuncio;
    protected $materia;
    protected $horarios;

    // Cadastrar monitoria:
    public function anunciar()
    {
        $this->__set("status", 1);
        $this->__set("tipo", "M");

        $cod_anuncio = $this->inserirAnuncio();
        $this->__set("cod_anuncio", $cod_anuncio);

        $this->db->insert([
            "cod_anuncio"   => $this->cod_anuncio,
            "materia"       => $this->materia,
            "horarios"      => $this->horarios
        ]);

        return true;
    }

    // Atualizar monitoria:
    public function atualizar()
    {
        $this->atualizarAnuncio();

        $this->db->update("cod_anuncio = $this->cod_anuncio", [
            "materia"       => $this->materia,
            "horarios"      => $this->horarios
        ]);

        return true;
    }

    // Excluir monitoria:
    public function excluir()
    {
        $this->db->delete("cod_anuncio = $this->cod_anuncio");
        $this->deletarAnuncio();

        return true;
    }

    // Obter todas as monitorias:
    public function getMonitorias()
    {
        $fields = "u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, m.materia";
        $join   = "m INNER JOIN tb_anuncios a ON m.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";
        $order  = "data_anunciada DESC";

        return $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obter uma monitoria em especifico:
    public function getMonitoria($cod_anuncio)
    {
        $fields = "u.nome, u.telefone, a.cod_anuncio, a.cod_usuario, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, m.materia, m.horarios";
        $join   = "m INNER JOIN tb_anuncios a ON m.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 and a.cod_anuncio = $cod_anuncio AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";

        return $this->db->select($fields, $where, null, null, $join)->fetch(PDO::FETCH_ASSOC);
    }

    public function getMonitoriaFiltrada($materia = null, $dias = null, $titulo = null)
    {
        $fields = "u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, m.materia";
        $join   = "m INNER JOIN tb_anuncios a ON m.cod_anuncio = a.cod_anuncio INNER JOIN tb_usuarios u ON a.cod_usuario = u.cod_usuario";
        $where  = "a.status = 1 AND (a.data_vencimento > '" . date("Y-m-d") . "' OR a.data_vencimento = '0000-00-00')";
        $order  = "data_anunciada DESC";

        if (!empty($materia)) {
            $where .= " AND m.materia LIKE '%" . filter_var($materia) . "%'";
        }

        if (!empty($dias) && is_array($dias)) {
            $where .= " AND (";

            foreach ($dias as $key => $dia) {
                if ($key > 0) {
                    $where .= " || m.horarios LIKE '%:\"" . $dia . "\"%'";
                } else {
                    $where .= "m.horarios LIKE '%:\"" . $dia . "\"%'";
                }
            }

            $where .= ")";
        }

        if (!empty($titulo)) {
            $where .= " AND a.titulo LIKE '%$titulo%'";
        }

        return $this->db->select($fields, $where, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>