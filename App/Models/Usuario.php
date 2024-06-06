<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Usuario extends Model
{
    protected $cod_usuario;
    protected $admin;
    protected $nome;
    protected $data_nascimento;
    protected $telefone;
    protected $instituicao;
    protected $email;
    protected $senha;
    protected $token;
    protected $ativo;
    protected $ultimo_acesso;

    public function registrar($token)
    {
        $this->db->insert([
            "nome"               => $this->nome,
            "data_nascimento"    => $this->data_nascimento,
            "telefone"           => $this->telefone,
            "instituicao"        => $this->instituicao,
            "email"              => $this->email,
            "senha"              => password_hash($this->senha, PASSWORD_DEFAULT),
            "token"              => $token
        ]);

        return true;
    }

    public function getUsuarioPorEmail()
    {
        $usuario = $this->db->select("email", "email = '{$this->email}'")->fetchAll(PDO::FETCH_ASSOC);

        return $usuario;
    }

    public function getUsuarioPorRecuperacao()
    {
        $usuario = $this->db->select("cod_usuario, nome", "email = '{$this->email}' and data_nascimento = '{$this->data_nascimento}'")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario["cod_usuario"])) {
            $this->__set("cod_usuario", $usuario["cod_usuario"]);
            $this->__set("nome",        $usuario["nome"]);
            return true;
        }

        return false;
    }

    public function getPerfil()
    {
        $fields = "nome, data_nascimento, telefone, instituicao, email, COUNT(avaliacao) AS avaliacoes, SUM(avaliacao) AS notas";
        $join   = "u INNER JOIN tb_anuncios a ON u.cod_usuario = a.cod_usuario INNER JOIN tb_avaliacoes av ON a.cod_anuncio = av.cod_anuncio";
        $where  = "u.cod_usuario = $this->cod_usuario";

        $usuario = $this->db->select($fields, $where, null, null, $join)->fetch(PDO::FETCH_ASSOC);

        return $usuario;
    }

    public function atualizarPerfil()
    {
        return $this->db->update("cod_usuario = $this->cod_usuario", [
            "nome"              => $this->nome,
            "data_nascimento"   => $this->data_nascimento,
            "telefone"          => $this->telefone,
            "instituicao"       => $this->instituicao
        ]);
    }

    public function autenticar()
    {
        $usuario = $this->db->select("cod_usuario, nome, senha", "email = '{$this->email}'")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['cod_usuario']) && password_verify($this->senha, $usuario['senha'])) {
            $this->__set("cod_usuario", $usuario["cod_usuario"]);
            $this->__set("nome", $usuario["nome"]);

            return true;
        } else if (!isset($usuario['cod_usuario'])) {
            return null;
        }

        return false;
    }

    public function bloquear()
    {
        $usuario = $this->db->select("cod_usuario", "email = '{$this->email}'")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['cod_usuario'])) {
            $this->__set("cod_usuario", $usuario["cod_usuario"]);

            $this->db->update("cod_usuario = {$this->cod_usuario}", [
                "senha" => password_hash(time(), PASSWORD_DEFAULT)
            ]);
        }
    }

    public function salvarToken($limpar = false)
    {
        if ($limpar) {
            $token = "";
        } else {
            $token = sha1(uniqid(mt_rand(), true));
        }

        $this->db->update("cod_usuario = {$this->cod_usuario}", [
            "token" => $token
        ]);

        $this->__set("token", $token);

        return true;
    }

    public function getUsuarioPorToken()
    {
        $usuario = $this->db->select("cod_usuario", "token = '{$this->token}'")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario["cod_usuario"])) {
            $this->__set("cod_usuario", $usuario["cod_usuario"]);
            return true;
        }

        return false;
    }

    public function alterarSenha()
    {
        $this->db->update("cod_usuario = {$this->cod_usuario}", [
            "senha" => password_hash($this->senha, PASSWORD_DEFAULT)
        ]);
    }

    public function verificarInatividade()
    {
        $usuario = $this->db->select("cod_usuario", "email = '{$this->email}' AND ativo = 1")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['cod_usuario'])) {
            return false;
        }

        return true;
    }

    public function validarHash($hash)
    {
        $usuario = $this->db->select("cod_usuario", "email = '{$this->email}' AND token = '{$hash}'")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['cod_usuario'])) {
            $this->db->update("cod_usuario = {$usuario['cod_usuario']}", [
                "token" => "",
                "ativo" => 1,
            ]);

            return true;
        }

        return false;
    }

    public function atualizarAcesso()
    {
        $this->db->update("cod_usuario = {$this->cod_usuario}", [
            "ultimo_acesso" => date('Y-m-d H:i:s')
        ]);
    }

    public function administrador()
    {
        $usuario = $this->db->select("cod_usuario", "email = '{$this->email}' AND admin = 1")->fetch(PDO::FETCH_ASSOC);

        if (isset($usuario['cod_usuario'])) {
            return true;
        }

        return false;
    }

    public function relatorioUsuarios($campos)
    {
        $campos_banco = [
            "chkDataNascimento" => "DATE_FORMAT(data_nascimento, '%d/%m/%Y') as data_nascimento",
            "chkTelefone" => "telefone",
            "chkEmail" => "email",
            "chkUltimoAcesso" => "DATE_FORMAT(ultimo_acesso, '%d/%m/%Y | %Hh%i') as ultimo_acesso",
        ];

        $campos_apresentacao = [
            "chkDataNascimento" => "Data de Nascimento",
            "chkTelefone" => "Telefone",
            "chkEmail" => "E-mail",
            "chkUltimoAcesso" => "Último Acesso",
        ];

        $selecionados = ["nome"];
        $apresentacao = ["Nome"];

        foreach ($campos as $campo) {
            if (isset($campos_banco[$campo])) {
                $selecionados[] = $campos_banco[$campo];
                $apresentacao[] = $campos_apresentacao[$campo];
            }
        }

        $rows = $this->db->select(implode(", ", $selecionados))->fetchAll(PDO::FETCH_ASSOC);

        $tabela["cabecalhos"] = $apresentacao;
        $tabela["linhas"] = $rows;

        return $tabela;
    }
}

?>