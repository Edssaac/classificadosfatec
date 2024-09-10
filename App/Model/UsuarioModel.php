<?php

namespace App\Model;

use App\Model;
use PDO;

class UsuarioModel extends Model
{
    public function registrar(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO usuario (
                nome, data_nascimento, telefone, instituicao, email, senha, token
            ) VALUES (
                :nome, :data_nascimento, :telefone, :instituicao, :email, :senha, :token
            )",
            $this->mapToBind([
                'nome'              => $data['nome'],
                'data_nascimento'   => $data['data_nascimento'],
                'telefone'          => $data['telefone'],
                'instituicao'       => $data['instituicao'],
                'email'             => $data['email'],
                'senha'             => password_hash($data['senha'], PASSWORD_DEFAULT),
                'token'             => $data['token']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function getUsuarioPorEmail(string $email): array
    {
        $result = $this->query(
            "SELECT *
                FROM usuario
                WHERE email = :email
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function getUsuarioPorRecuperacao(string $email, string $data_nascimento): array
    {
        $result = $this->query(
            "SELECT cod_usuario, nome
                FROM usuario
                WHERE email = :email AND data_nascimento = :data_nascimento
            ",
            $this->mapToBind([
                'email'             => $email,
                'data_nascimento'   => $data_nascimento
            ])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function getPerfil(int $cod_usuario): array
    {   
        $result = $this->query(
            "SELECT nome, data_nascimento, telefone, instituicao, email, COUNT(avaliacao) AS avaliacoes, SUM(avaliacao) AS notas
                FROM usuario u
                INNER JOIN anuncio a ON (u.cod_usuario = a.cod_usuario) 
                LEFT JOIN avaliacao av ON (a.cod_anuncio = av.cod_anuncio)
                WHERE u.cod_usuario = :cod_usuario
            ",
            $this->mapToBind(['cod_usuario' => $cod_usuario])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function atualizarPerfil(array $data): bool
    {
        $result = $this->query(
            "UPDATE usuario 
                SET nome = :nome,
                    data_nascimento = :data_nascimento,
                    telefone = :telefone,
                    instituicao = :instituicao
                WHERE cod_usuario = :cod_usuario
            ",
            $this->mapToBind([
                'nome'              => $data['nome'],
                'data_nascimento'   => $data['data_nascimento'],
                'telefone'          => $data['telefone'],
                'instituicao'       => $data['instituicao'],
                'cod_usuario'       => $data['cod_usuario']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function autenticar(string $email, string $senha): bool
    {
        $result = $this->query(
            "SELECT senha
                FROM usuario
                WHERE email = :email
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return (isset($fetch['senha']) && password_verify($senha, $fetch['senha']));
    }

    public function bloquear(string $email): bool
    {
        $result = $this->query(
            "UPDATE usuario 
                SET ativo = 0,
                    senha = :senha
                WHERE cod_usuario = :cod_usuario
            ",
            $this->mapToBind([
                'email' => $email,
                'senha' => password_hash(time(), PASSWORD_DEFAULT)
            ])
        );

        return (bool) $result->rowCount();
    }

    public function salvarToken(int $cod_usuario, bool $limpar = false): string
    {
        $token = $limpar ? '' : sha1(uniqid(mt_rand(), true));

        $result = $this->query(
            "UPDATE usuario 
                SET token = :token
                WHERE cod_usuario = :cod_usuario
            ",
            $this->mapToBind([
                'cod_usuario'   => $cod_usuario,
                'token'         => $token
            ])
        );

        return $token;
    }

    public function getUsuarioPorToken(string $token): array
    {
        $result = $this->query(
            "SELECT *
                FROM usuario
                WHERE token = :token
            ",
            $this->mapToBind(['token' => $token])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetch : []);
    }

    public function alterarSenha(int $cod_usuario, string $senha): bool
    {
        $result = $this->query(
            "UPDATE usuario 
                SET cod_usuario = :cod_usuario,
                    senha = :senha
                WHERE cod_usuario = :cod_usuario
            ",
            $this->mapToBind([
                'cod_usuario'   => $cod_usuario,
                'senha'         => password_hash($senha, PASSWORD_DEFAULT)
            ])
        );

        return (bool) $result->rowCount();
    }

    public function verificarInatividade(string $email): bool
    {
        $result = $this->query(
            "SELECT cod_usuario
                FROM usuario
                WHERE email = :email AND ativo = 1
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return empty($fetch['cod_usuario']);
    }

    public function validarHash(string $email, string $token): bool
    {
        $result = $this->query(
            "UPDATE usuario 
                SET token = '',
                    ativo = 1
                WHERE email = :email AND token = :token
            ",
            $this->mapToBind([
                'email' => $email,
                'token' => $token
            ])
        );

        return (bool) $result->rowCount();
    }

    public function atualizarAcesso(int $cod_usuario): bool
    {
        $result = $this->query(
            "UPDATE usuario 
                SET ultimo_acesso = NOW()
                WHERE cod_usuario = :cod_usuario
            ",
            $this->mapToBind(['cod_usuario' => $cod_usuario])
        );

        return (bool) $result->rowCount();
    }

    public function administrador(string $email): bool
    {
        $result = $this->query(
            "SELECT cod_usuario
                FROM usuario
                WHERE email = :email AND admin = 1
            ",
            $this->mapToBind(['email' => $email])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return isset($fetch['cod_usuario']);
    }

    public function relatorioUsuarios(array $campos_selecionados): array
    {
        $campos_disponiveis = [
            'cb_data_nascimento' => 'DATE_FORMAT(data_nascimento, "%d/%m/%Y") as data_nascimento',
            'cb_telefone' => 'telefone',
            'cb_email' => 'email',
            'cb_data_acesso' => 'DATE_FORMAT(ultimo_acesso, "%d/%m/%Y | %Hh%i") as ultimo_acesso'
        ];

        $selecionados = ['nome'];

        foreach ($campos_selecionados as $campo) {
            if (isset($campos_disponiveis[$campo])) {
                $selecionados[] = $campos_disponiveis[$campo];
            }
        }

        $campos = implode(', ', $selecionados);

        $result = $this->query(
            "SELECT $campos
                FROM usuario
            "
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount() ? $fetches : []);
    }
}
